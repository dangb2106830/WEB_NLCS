<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class ChatService
{
    protected string $apiKey;

    protected string $baseUrl;

    protected string $model;

    protected float $temperature;

    protected int $maxOutputTokens;

    protected float $minScore;

    public function __construct(
        protected SearchService $searchService,
        protected CatalogInsightService $catalogInsightService
    ) {
        $this->apiKey = (string) config('services.gemini.api_key', '');
        $this->baseUrl = rtrim((string) config('services.gemini.base_url', ''), '/');
        $this->model = (string) config('services.gemini.chat_model', 'gemini-2.5-flash');
        $this->temperature = (float) config('services.gemini.chat_temperature', 0.2);
        $this->maxOutputTokens = (int) config('services.gemini.chat_max_output_tokens', 512);
        $this->minScore = (float) config('services.gemini.chat_min_score', 0.65);
    }

    public function answer(string $question, int $limit = 3): array
    {
        $structuredAnswer = $this->catalogInsightService->answer($question);

        if ($structuredAnswer !== null) {
            return $structuredAnswer;
        }

        $intent = $this->detectIntent($question);
        $searchLimit = max($limit, $intent['is_recommendation'] ? 5 : 3);
        $matches = $this->searchService->search($question, $searchLimit);
        $relevantMatches = $this->filterRelevantMatches($matches, $intent);

        if ($relevantMatches === []) {
            return [
                'answer' => $this->buildNoInformationResponse(),
                'sources' => [],
                'context' => '',
            ];
        }

        $ruleBasedAnswer = $this->buildRuleBasedAnswer($relevantMatches, $intent);
        $context = $this->buildContext($relevantMatches);

        if ($ruleBasedAnswer !== null) {
            return [
                'answer' => $ruleBasedAnswer,
                'sources' => $relevantMatches,
                'context' => $context,
            ];
        }

        $answer = $this->generateAnswer($question, $context);

        if ($this->looksLikeRefusal($answer)) {
            $fallbackAnswer = $this->buildRuleBasedAnswer($relevantMatches, [
                ...$intent,
                'is_recommendation' => true,
            ]);

            if ($fallbackAnswer !== null) {
                $answer = $fallbackAnswer;
            }
        }

        return [
            'answer' => $answer,
            'sources' => $relevantMatches,
            'context' => $context,
        ];
    }

    protected function generateAnswer(string $question, string $context): string
    {
        if ($this->apiKey === '') {
            throw new RuntimeException('Gemini API key is not configured.');
        }

        if ($this->baseUrl === '') {
            throw new RuntimeException('Gemini API base URL is not configured.');
        }

        $response = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->asJson()
            ->timeout(60)
            ->retry(2, 1000)
            ->withHeaders([
                'x-goog-api-key' => $this->apiKey,
            ])
            ->post('models/' . $this->model . ':generateContent', [
                'systemInstruction' => [
                    'parts' => [
                        [
                            'text' => $this->buildSystemInstruction($context),
                        ],
                    ],
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            [
                                'text' => $question,
                            ],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => $this->temperature,
                    'maxOutputTokens' => $this->maxOutputTokens,
                ],
            ]);

        if ($response->status() === 404) {
            throw new RuntimeException(
                'Gemini model ' . $this->model . ' is unavailable. Set GEMINI_CHAT_MODEL to a supported model such as gemini-2.5-flash.'
            );
        }

        $response->throw();

        $text = $this->extractTextFromResponse($response->json('candidates.0.content.parts', []));

        if ($text === '') {
            throw new RuntimeException('Gemini chat response did not contain any text.');
        }

        return $text;
    }

    protected function filterRelevantMatches(array $matches, array $intent): array
    {
        $threshold = $this->resolveMinScore($intent);

        $filtered = array_values(array_filter($matches, function (array $match) use ($threshold): bool {
            return isset($match['score'], $match['context'])
                && is_numeric($match['score'])
                && (float) $match['score'] >= $threshold
                && trim((string) ($match['context'] ?? '')) !== '';
        }));

        if ($filtered !== []) {
            return $filtered;
        }

        if ($matches === []) {
            return [];
        }

        $fallbackThreshold = max(0.5, $threshold - 0.08);

        return array_values(array_filter($matches, function (array $match) use ($fallbackThreshold): bool {
            return isset($match['score'])
                && is_numeric($match['score'])
                && (float) $match['score'] >= $fallbackThreshold;
        }));
    }

    protected function resolveMinScore(array $intent): float
    {
        $threshold = $this->minScore;

        if ($intent['is_recommendation']) {
            $threshold -= 0.08;
        }

        if ($intent['asks_brand_specific']) {
            $threshold -= 0.04;
        }

        if ($intent['is_analytic'] || $intent['asks_policy']) {
            $threshold -= 0.08;
        }

        return max(0.5, $threshold);
    }

    protected function buildContext(array $matches): string
    {
        $blocks = [];

        foreach ($matches as $index => $match) {
            $lines = [
                'Nguồn ' . ($index + 1) . ':',
                'Loại: ' . (string) ($match['type'] ?? 'unknown'),
                'Tiêu đề: ' . (string) ($match['title'] ?? 'Không rõ'),
                'Độ phù hợp: ' . number_format((float) ($match['score'] ?? 0), 3, '.', ''),
            ];

            if (! empty($match['brand'])) {
                $lines[] = 'Thương hiệu: ' . $match['brand'];
            }

            if (! empty($match['category'])) {
                $lines[] = 'Danh mục: ' . $match['category'];
            }

            if (! empty($match['formatted_price'])) {
                $lines[] = 'Giá: ' . $match['formatted_price'];
            }

            if (isset($match['sold'])) {
                $lines[] = 'Đã bán: ' . number_format((int) $match['sold'], 0, ',', '.');
            }

            if (! empty($match['url'])) {
                $lines[] = 'URL: ' . $match['url'];
            }

            $lines[] = 'Nội dung: ' . Str::limit((string) ($match['context'] ?? ''), 1400);

            $blocks[] = implode("\n", $lines);
        }

        return implode("\n\n", $blocks);
    }

    protected function buildSystemInstruction(string $context): string
    {
        return "Bạn là trợ lý của MinhDang.\n"
            . "Chỉ được trả lời dựa trên dữ liệu sau:\n"
            . $context
            . "\n\n"
            . "Nếu người dùng hỏi gợi ý, nổi bật, tốt nhất, nên mua hoặc bán chạy:\n"
            . "- Được phép đề xuất dựa trên dữ liệu hiện có như sold, giá, discount, brand, category.\n"
            . "- Phải nói rõ kết luận chỉ dựa trên dữ liệu hiện có của MinhDang.\n"
            . "- Không được khẳng định chất lượng tuyệt đối nếu dữ liệu không có đánh giá.\n\n"
            . "Nếu người dùng hỏi câu thống kê, tổng hợp, đếm, trung bình, cao nhất hoặc thấp nhất:\n"
            . "- Chỉ được kết luận khi trong ngữ cảnh có số liệu tổng hợp hoặc thống kê rõ ràng.\n"
            . "- Không được suy diễn từ một vài sản phẩm riêng lẻ để trả lời cho toàn cửa hàng.\n\n"
            . "Nếu không có thông tin phù hợp thì từ chối lịch sự.\n\n"
            . "Luôn trả lời:\n"
            . "- Ngắn gọn\n"
            . "- Chính xác\n"
            . "- Nếu là sản phẩm, thêm link dạng Markdown [Tên sản phẩm](url)\n"
            . "- Nếu liệt kê nhiều sản phẩm, ưu tiên tối đa 3 mục\n"
            . "- Viết tiếng Việt có dấu tự nhiên";
    }

    protected function detectIntent(string $question): array
    {
        $normalized = $this->normalizeForMatching($question);
        $asksPolicy = $this->containsAnyPhrase($normalized, [
            'chinh sach',
            'doi tra',
            'tra hang',
            'hoan tien',
            'hoan tra',
            'bao hanh',
            'giao hang',
            'van chuyen',
            'ship',
            'doi cu',
            'lay moi',
        ]);
        $asksCount = $this->containsAnyPhrase($normalized, [
            'bao nhieu',
            'co may',
            'tong so',
            'tong cong',
        ]);
        $asksAverage = $this->containsAnyPhrase($normalized, [
            'trung binh',
            'binh quan',
            'average',
        ]);
        $asksRecommendation = $this->containsAnyPhrase($normalized, [
            'goi y',
            'nen mua',
            'tham khao',
            'de xuat',
            'tot nhat',
            'noi bat',
            'ban chay',
        ]);

        return [
            'normalized' => $normalized,
            'is_recommendation' => $asksRecommendation,
            'is_analytic' => ! $asksPolicy && (
                $asksCount
                || $asksAverage
                || $this->containsAnyPhrase($normalized, [
                    'cao nhat',
                    'lon nhat',
                    'nhieu nhat',
                    'thap nhat',
                    'it nhat',
                    'dat nhat',
                    're nhat',
                ])
            ),
            'asks_policy' => $asksPolicy,
            'asks_best' => $this->containsAnyPhrase($normalized, ['tot nhat', 'noi bat', 'dang mua']),
            'asks_bestseller' => $this->containsAnyPhrase($normalized, ['ban chay', 'pho bien', 'mua nhieu']),
            'asks_cheapest' => $this->containsAnyPhrase($normalized, ['gia re', 're nhat', 'tiet kiem', 'thap nhat']),
            'asks_brand_specific' => $this->containsAnyPhrase($normalized, [
                'sunhouse',
                'panasonic',
                'sharp',
                'electrolux',
                'philips',
            ]),
        ];
    }

    protected function buildRuleBasedAnswer(array $matches, array $intent): ?string
    {
        if ($intent['is_analytic'] || $intent['asks_policy']) {
            return null;
        }

        $productMatches = array_values(array_filter($matches, fn (array $match): bool => ($match['type'] ?? null) === 'product'));

        if ($productMatches === []) {
            return null;
        }

        $filteredProducts = $this->filterProductsByMentionedBrand($productMatches, $intent['normalized']);

        if ($filteredProducts === []) {
            $filteredProducts = $productMatches;
        }

        if ($intent['asks_bestseller']) {
            usort($filteredProducts, fn (array $a, array $b): int => [$b['sold'] ?? 0, $b['score'] ?? 0] <=> [$a['sold'] ?? 0, $a['score'] ?? 0]);

            return $this->buildBestProductAnswer('sản phẩm bán chạy nhất', $filteredProducts, true);
        }

        if ($intent['asks_best']) {
            usort($filteredProducts, fn (array $a, array $b): int => [$b['score'] ?? 0, $b['sold'] ?? 0] <=> [$a['score'] ?? 0, $a['sold'] ?? 0]);

            return $this->buildBestProductAnswer('sản phẩm nổi bật nhất', $filteredProducts, true);
        }

        if ($intent['asks_cheapest']) {
            usort($filteredProducts, fn (array $a, array $b): int => [$a['price'] ?? INF, $b['score'] ?? 0] <=> [$b['price'] ?? INF, $a['score'] ?? 0]);

            return $this->buildBestProductAnswer('sản phẩm tiết kiệm nhất', $filteredProducts, false);
        }

        if ($intent['is_recommendation']) {
            usort($filteredProducts, fn (array $a, array $b): int => [$b['score'] ?? 0, $b['sold'] ?? 0] <=> [$a['score'] ?? 0, $a['sold'] ?? 0]);

            return $this->buildRecommendationListAnswer($filteredProducts);
        }

        return null;
    }

    protected function buildBestProductAnswer(string $label, array $products, bool $includeAlternatives): string
    {
        $topProduct = $products[0] ?? null;

        if ($topProduct === null) {
            return $this->buildNoInformationResponse();
        }

        $brandSegment = ! empty($topProduct['brand']) ? ' của ' . $topProduct['brand'] : '';
        $reasonParts = [];

        if (($topProduct['sold'] ?? 0) > 0) {
            $reasonParts[] = 'đã bán ' . number_format((int) $topProduct['sold'], 0, ',', '.') . ' sản phẩm';
        }

        if (! empty($topProduct['formatted_price'])) {
            $reasonParts[] = 'giá hiện tại ' . $topProduct['formatted_price'];
        }

        if (! empty($topProduct['category'])) {
            $reasonParts[] = 'thuộc nhóm ' . $topProduct['category'];
        }

        $answer = 'Dựa trên dữ liệu hiện có của MinhDang, ' . $label . $brandSegment . ' là '
            . $this->formatMarkdownProduct($topProduct)
            . '.';

        if ($reasonParts !== []) {
            $answer .= ' Lý do: ' . implode(', ', $reasonParts) . '.';
        }

        if (! $includeAlternatives || count($products) < 2) {
            return $answer;
        }

        $alternatives = array_slice($products, 1, 2);
        $answer .= "\n\nBạn có thể tham khảo thêm:";

        foreach ($alternatives as $product) {
            $answer .= "\n- " . $this->formatMarkdownProduct($product)
                . ' - Giá: ' . ($product['formatted_price'] ?? 'Không rõ')
                . (! empty($product['category']) ? ' - ' . $product['category'] : '');
        }

        return $answer;
    }

    protected function buildRecommendationListAnswer(array $products): string
    {
        $topProducts = array_slice($products, 0, 3);
        $answer = 'Dựa trên dữ liệu hiện có của MinhDang, bạn có thể tham khảo:';

        foreach ($topProducts as $product) {
            $line = "\n- " . $this->formatMarkdownProduct($product);

            if (! empty($product['formatted_price'])) {
                $line .= ' - Giá: ' . $product['formatted_price'];
            }

            if (! empty($product['category'])) {
                $line .= ' - ' . $product['category'];
            }

            if (($product['sold'] ?? 0) > 0) {
                $line .= ' - Đã bán: ' . number_format((int) $product['sold'], 0, ',', '.');
            }

            $answer .= $line;
        }

        return $answer;
    }

    protected function filterProductsByMentionedBrand(array $products, string $normalizedQuestion): array
    {
        $matched = array_values(array_filter($products, function (array $product) use ($normalizedQuestion): bool {
            if (empty($product['brand'])) {
                return false;
            }

            return str_contains($normalizedQuestion, $this->normalizeForMatching((string) $product['brand']));
        }));

        return $matched;
    }

    protected function formatMarkdownProduct(array $product): string
    {
        $title = (string) ($product['title'] ?? 'Sản phẩm');
        $url = (string) ($product['url'] ?? '');

        if ($url === '') {
            return $title;
        }

        return '[' . $title . '](' . $url . ')';
    }

    protected function looksLikeRefusal(string $answer): bool
    {
        $normalized = $this->normalizeForMatching($answer);

        return $this->containsAnyPhrase($normalized, [
            'xin loi',
            'khong co thong tin',
            'khong du thong tin',
            'khong the xac dinh',
            'khong tim thay thong tin',
        ]);
    }

    protected function containsAnyPhrase(string $haystack, array $phrases): bool
    {
        foreach ($phrases as $phrase) {
            if (str_contains($haystack, $phrase)) {
                return true;
            }
        }

        return false;
    }

    protected function normalizeForMatching(string $text): string
    {
        $ascii = Str::of($text)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->trim()
            ->value();

        return preg_replace('/\s+/', ' ', $ascii) ?? '';
    }

    protected function extractTextFromResponse(array $parts): string
    {
        $segments = [];

        foreach ($parts as $part) {
            if (is_array($part) && isset($part['text']) && is_string($part['text'])) {
                $segments[] = trim($part['text']);
            }
        }

        return trim(implode("\n", array_filter($segments)));
    }

    protected function buildNoInformationResponse(): string
    {
        return 'Xin lỗi, tôi chưa tìm thấy thông tin phù hợp trong dữ liệu hiện có của MinhDang.';
    }
}
