<?php

namespace App\Services\AI;

use App\Models\Blog;
use App\Models\Policy;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CatalogInsightService
{
    public function answer(string $question): ?array
    {
        $signals = $this->extractSignals($question);

        if ($signals['asks_policy']) {
            return $this->answerPolicyQuestion($question, $signals);
        }

        if ($signals['asks_analytics']) {
            return $this->answerAnalyticalQuestion($signals);
        }

        return null;
    }

    protected function answerAnalyticalQuestion(array $signals): ?array
    {
        $products = Product::query()
            ->with([
                'brand:id,name_bra',
                'category:id,name_cate',
            ])
            ->get();

        if ($products->isEmpty()) {
            return null;
        }

        $brandStats = $this->buildGroupedStats($products, fn (Product $product): string => $product->getBrandName());
        $categoryStats = $this->buildGroupedStats($products, fn (Product $product): string => $product->getCategoryName());
        $mentionedBrand = $this->findMentionedGroup($brandStats, $signals['normalized']);
        $mentionedCategory = $this->findMentionedGroup($categoryStats, $signals['normalized']);

        if ($mentionedBrand !== null && $signals['asks_count'] && $signals['mentions_product']) {
            $answer = 'Hiện có ' . number_format((int) $mentionedBrand['product_count'], 0, ',', '.')
                . ' sản phẩm thuộc thương hiệu ' . $mentionedBrand['name'] . ' trong dữ liệu cửa hàng.';

            return $this->buildInsightResponse(
                'Số lượng sản phẩm theo thương hiệu',
                $answer,
                $this->buildGroupContext('Thống kê thương hiệu', $mentionedBrand)
            );
        }

        if ($mentionedCategory !== null && $signals['asks_count'] && $signals['mentions_product']) {
            $answer = 'Hiện có ' . number_format((int) $mentionedCategory['product_count'], 0, ',', '.')
                . ' sản phẩm thuộc danh mục ' . $mentionedCategory['name'] . ' trong dữ liệu cửa hàng.';

            return $this->buildInsightResponse(
                'Số lượng sản phẩm theo danh mục',
                $answer,
                $this->buildGroupContext('Thống kê danh mục', $mentionedCategory)
            );
        }

        if ($mentionedBrand !== null && $signals['asks_average'] && $signals['mentions_price']) {
            $answer = 'Giá trung bình của thương hiệu ' . $mentionedBrand['name']
                . ' hiện khoảng ' . $this->formatMoney($mentionedBrand['average_price'])
                . ' trên ' . number_format((int) $mentionedBrand['product_count'], 0, ',', '.') . ' sản phẩm.';

            return $this->buildInsightResponse(
                'Giá trung bình theo thương hiệu',
                $answer,
                $this->buildGroupContext('Thống kê thương hiệu', $mentionedBrand)
            );
        }

        if ($mentionedCategory !== null && $signals['asks_average'] && $signals['mentions_price']) {
            $answer = 'Giá trung bình của danh mục ' . $mentionedCategory['name']
                . ' hiện khoảng ' . $this->formatMoney($mentionedCategory['average_price'])
                . ' trên ' . number_format((int) $mentionedCategory['product_count'], 0, ',', '.') . ' sản phẩm.';

            return $this->buildInsightResponse(
                'Giá trung bình theo danh mục',
                $answer,
                $this->buildGroupContext('Thống kê danh mục', $mentionedCategory)
            );
        }

        if ($signals['mentions_brand'] && $signals['asks_count'] && $mentionedBrand === null) {
            $brandNames = $brandStats
                ->pluck('name')
                ->sort()
                ->values()
                ->all();

            $answer = 'Hiện cửa hàng có ' . number_format($brandStats->count(), 0, ',', '.')
                . ' thương hiệu trong dữ liệu sản phẩm: ' . implode(', ', $brandNames) . '.';

            return $this->buildInsightResponse(
                'Tổng số thương hiệu',
                $answer,
                $this->buildStoreContext($products, $brandStats, $categoryStats)
            );
        }

        if ($signals['mentions_category'] && $signals['asks_count'] && $mentionedCategory === null) {
            $answer = 'Hiện cửa hàng có ' . number_format($categoryStats->count(), 0, ',', '.')
                . ' danh mục trong dữ liệu sản phẩm.';

            return $this->buildInsightResponse(
                'Tổng số danh mục',
                $answer,
                $this->buildStoreContext($products, $brandStats, $categoryStats)
            );
        }

        if ($signals['mentions_product'] && $signals['asks_count'] && $mentionedBrand === null && $mentionedCategory === null) {
            $answer = 'Hiện cửa hàng đang có ' . number_format($products->count(), 0, ',', '.')
                . ' sản phẩm trong dữ liệu được chatbot sử dụng.';

            return $this->buildInsightResponse(
                'Tổng số sản phẩm',
                $answer,
                $this->buildStoreContext($products, $brandStats, $categoryStats)
            );
        }

        if ($signals['mentions_brand'] && $signals['mentions_price'] && $signals['asks_average'] && $signals['asks_highest']) {
            $topBrand = $brandStats
                ->sortByDesc('average_price')
                ->values()
                ->first();

            if ($topBrand !== null) {
                $answer = 'Dựa trên ' . number_format($products->count(), 0, ',', '.')
                    . ' sản phẩm hiện có, thương hiệu có giá trung bình cao nhất là '
                    . $topBrand['name'] . ', khoảng ' . $this->formatMoney($topBrand['average_price'])
                    . '/sản phẩm (' . number_format((int) $topBrand['product_count'], 0, ',', '.') . ' sản phẩm).';

                return $this->buildInsightResponse(
                    'Thương hiệu có giá trung bình cao nhất',
                    $answer,
                    $this->buildRankingContext('Thống kê thương hiệu theo giá trung bình', $brandStats, 'average_price')
                );
            }
        }

        if ($signals['mentions_brand'] && $signals['mentions_price'] && $signals['asks_average'] && $signals['asks_lowest']) {
            $topBrand = $brandStats
                ->sortBy('average_price')
                ->values()
                ->first();

            if ($topBrand !== null) {
                $answer = 'Dựa trên ' . number_format($products->count(), 0, ',', '.')
                    . ' sản phẩm hiện có, thương hiệu có giá trung bình thấp nhất là '
                    . $topBrand['name'] . ', khoảng ' . $this->formatMoney($topBrand['average_price'])
                    . '/sản phẩm (' . number_format((int) $topBrand['product_count'], 0, ',', '.') . ' sản phẩm).';

                return $this->buildInsightResponse(
                    'Thương hiệu có giá trung bình thấp nhất',
                    $answer,
                    $this->buildRankingContext('Thống kê thương hiệu theo giá trung bình', $brandStats, 'average_price', true)
                );
            }
        }

        if ($signals['mentions_brand'] && $signals['mentions_sold'] && $signals['asks_highest']) {
            $topBrand = $brandStats
                ->sortByDesc('total_sold')
                ->values()
                ->first();

            if ($topBrand !== null) {
                $answer = 'Nếu xét theo tổng số lượng đã bán trong dữ liệu hiện có, thương hiệu dẫn đầu là '
                    . $topBrand['name'] . ' với ' . number_format((int) $topBrand['total_sold'], 0, ',', '.')
                    . ' sản phẩm đã bán.';

                return $this->buildInsightResponse(
                    'Thương hiệu bán chạy nhất theo dữ liệu hiện có',
                    $answer,
                    $this->buildRankingContext('Thống kê thương hiệu theo số lượng đã bán', $brandStats, 'total_sold')
                );
            }
        }

        if ($signals['mentions_brand'] && $signals['asks_highest']) {
            $topBrand = $brandStats
                ->sortByDesc('product_count')
                ->values()
                ->first();

            if ($topBrand !== null) {
                $answer = 'Thương hiệu đang có nhiều sản phẩm nhất trong dữ liệu cửa hàng là '
                    . $topBrand['name'] . ' với ' . number_format((int) $topBrand['product_count'], 0, ',', '.')
                    . ' sản phẩm.';

                return $this->buildInsightResponse(
                    'Thương hiệu có nhiều sản phẩm nhất',
                    $answer,
                    $this->buildRankingContext('Thống kê thương hiệu theo số lượng sản phẩm', $brandStats, 'product_count')
                );
            }
        }

        if ($signals['mentions_category'] && $signals['asks_highest']) {
            $topCategory = $categoryStats
                ->sortByDesc('product_count')
                ->values()
                ->first();

            if ($topCategory !== null) {
                $answer = 'Danh mục đang có nhiều sản phẩm nhất trong dữ liệu cửa hàng là '
                    . $topCategory['name'] . ' với ' . number_format((int) $topCategory['product_count'], 0, ',', '.')
                    . ' sản phẩm.';

                return $this->buildInsightResponse(
                    'Danh mục có nhiều sản phẩm nhất',
                    $answer,
                    $this->buildRankingContext('Thống kê danh mục theo số lượng sản phẩm', $categoryStats, 'product_count')
                );
            }
        }

        return null;
    }

    protected function answerPolicyQuestion(string $question, array $signals): array
    {
        $topic = $this->detectPolicyTopic($signals['normalized']);
        $documents = $this->buildPolicyDocuments();
        $matches = $documents
            ->map(function (array $document) use ($question, $topic) {
                $text = (string) ($document['context'] ?? '');
                $normalizedTitle = $this->normalizeForMatching((string) ($document['title'] ?? ''));
                $tokens = $this->tokenize($text);
                $queryTokens = $this->tokenize($question);
                $keywordScore = $this->calculateKeywordOverlap($queryTokens, $tokens);
                $topicScore = $this->calculateTopicScore($topic, $this->normalizeForMatching($text));
                $titleScore = $this->calculateTopicScore($topic, $normalizedTitle);
                $score = ($keywordScore * 0.4) + ($topicScore * 0.35) + ($titleScore * 0.25);

                if (($document['type'] ?? null) === 'policy') {
                    $score += 0.08;
                }

                $document['score'] = round(min(1.0, $score), 6);
                $document['keyword_score'] = $keywordScore;
                $document['topic_score'] = $topicScore;
                $document['title_score'] = $titleScore;

                return $document;
            })
            ->filter(function (array $document) use ($topic): bool {
                if ($topic !== 'policy'
                    && ((float) ($document['topic_score'] ?? 0.0) + (float) ($document['title_score'] ?? 0.0)) <= 0.0
                ) {
                    return false;
                }

                return (float) ($document['score'] ?? 0.0) >= 0.18;
            })
            ->sortByDesc('score')
            ->values();

        if ($matches->isEmpty()) {
            return [
                'answer' => $this->buildMissingPolicyAnswer($topic),
                'sources' => [],
                'context' => '',
            ];
        }

        $topMatches = $matches->take(2)->values()->all();
        $topMatch = $topMatches[0];
        $title = $this->formatDocumentTitle($topMatch);
        $snippet = Str::limit((string) ($topMatch['snippet'] ?? $topMatch['context'] ?? ''), 220);
        $answerPrefix = match ($topic) {
            'trade_in' => 'Mình có tìm thấy thông tin liên quan đến chương trình đổi cũ lấy mới',
            'warranty' => 'Mình có tìm thấy thông tin liên quan đến bảo hành',
            'shipping' => 'Mình có tìm thấy thông tin liên quan đến giao hàng',
            'return' => 'Theo dữ liệu hiện có, mình chỉ có thể xác nhận thông tin đổi trả/trả hàng như sau',
            default => 'Mình có tìm thấy thông tin chính sách liên quan',
        };

        $answer = $answerPrefix . ': ' . $title . '. ' . $snippet;

        if (count($topMatches) > 1) {
            $answer .= ' Nguồn tham khảo thêm: ' . $this->formatDocumentTitle($topMatches[1]) . '.';
        }

        return [
            'answer' => trim($answer),
            'sources' => $topMatches,
            'context' => $this->buildDocumentContext($topMatches),
        ];
    }

    protected function buildPolicyDocuments(): Collection
    {
        $policies = Policy::query()
            ->get()
            ->map(function (Policy $policy): array {
                $context = $policy->toSearchableText();

                return [
                    'type' => 'policy',
                    'id' => (int) $policy->id,
                    'title' => $policy->title,
                    'score' => 0.0,
                    'url' => null,
                    'context' => $context,
                    'snippet' => Str::limit($this->normalizeText($policy->content), 260),
                ];
            });

        $blogs = Blog::query()
            ->get()
            ->map(function (Blog $blog): array {
                $context = $blog->toSearchableText();

                return [
                    'type' => 'blog',
                    'id' => (int) $blog->id,
                    'title' => $blog->title,
                    'score' => 0.0,
                    'url' => $blog->getBlogUrl(),
                    'context' => $context,
                    'snippet' => Str::limit($this->normalizeText($blog->intro ?: $blog->content), 260),
                ];
            });

        return $policies->concat($blogs);
    }

    protected function buildGroupedStats(Collection $products, callable $resolver): Collection
    {
        return $products
            ->groupBy(function (Product $product) use ($resolver): string {
                return (string) $resolver($product);
            })
            ->map(function (Collection $group, string $name): array {
                $prices = $group
                    ->map(fn (Product $product): float => $product->getEffectivePrice())
                    ->filter(fn (float $price): bool => $price > 0)
                    ->values();

                return [
                    'name' => $name,
                    'normalized_name' => $this->normalizeForMatching($name),
                    'product_count' => $group->count(),
                    'average_price' => $prices->avg() ?? 0.0,
                    'min_price' => $prices->min() ?? 0.0,
                    'max_price' => $prices->max() ?? 0.0,
                    'total_sold' => $group->sum(fn (Product $product): int => (int) ($product->sold ?? 0)),
                ];
            })
            ->sortBy('name')
            ->values();
    }

    protected function findMentionedGroup(Collection $stats, string $normalizedQuestion): ?array
    {
        return $stats->first(function (array $group) use ($normalizedQuestion): bool {
            $normalizedName = (string) ($group['normalized_name'] ?? '');

            return $normalizedName !== '' && str_contains($normalizedQuestion, $normalizedName);
        });
    }

    protected function buildInsightResponse(string $title, string $answer, string $context): array
    {
        return [
            'answer' => $answer,
            'sources' => [
                [
                    'type' => 'insight',
                    'id' => null,
                    'title' => $title,
                    'score' => 1.0,
                    'url' => null,
                    'context' => $context,
                    'snippet' => Str::limit($context, 260),
                ],
            ],
            'context' => $context,
        ];
    }

    protected function buildStoreContext(Collection $products, Collection $brandStats, Collection $categoryStats): string
    {
        $averagePrice = $products
            ->map(fn (Product $product): float => $product->getEffectivePrice())
            ->filter(fn (float $price): bool => $price > 0)
            ->avg() ?? 0.0;

        $topBrandByCount = $brandStats->sortByDesc('product_count')->first();
        $topBrandByAveragePrice = $brandStats->sortByDesc('average_price')->first();

        $segments = [
            'Tổng số sản phẩm: ' . number_format($products->count(), 0, ',', '.'),
            'Tổng số thương hiệu: ' . number_format($brandStats->count(), 0, ',', '.'),
            'Tổng số danh mục: ' . number_format($categoryStats->count(), 0, ',', '.'),
            'Giá trung bình toàn cửa hàng: ' . $this->formatMoney($averagePrice),
        ];

        if ($topBrandByCount !== null) {
            $segments[] = 'Thương hiệu có nhiều sản phẩm nhất: ' . $topBrandByCount['name']
                . ' (' . number_format((int) $topBrandByCount['product_count'], 0, ',', '.') . ' sản phẩm)';
        }

        if ($topBrandByAveragePrice !== null) {
            $segments[] = 'Thương hiệu có giá trung bình cao nhất: ' . $topBrandByAveragePrice['name']
                . ' (' . $this->formatMoney($topBrandByAveragePrice['average_price']) . ')';
        }

        return implode('. ', $segments) . '.';
    }

    protected function buildGroupContext(string $title, array $group): string
    {
        return $title . ': '
            . $group['name']
            . ' - số sản phẩm: ' . number_format((int) $group['product_count'], 0, ',', '.')
            . ', giá trung bình: ' . $this->formatMoney((float) $group['average_price'])
            . ', giá thấp nhất: ' . $this->formatMoney((float) $group['min_price'])
            . ', giá cao nhất: ' . $this->formatMoney((float) $group['max_price'])
            . ', đã bán: ' . number_format((int) $group['total_sold'], 0, ',', '.')
            . '.';
    }

    protected function buildRankingContext(string $title, Collection $stats, string $metric, bool $ascending = false): string
    {
        $sorted = $ascending
            ? $stats->sortBy($metric)->values()
            : $stats->sortByDesc($metric)->values();

        $lines = [$title . ':'];

        foreach ($sorted->take(5) as $group) {
            $metricLabel = match ($metric) {
                'average_price' => $this->formatMoney((float) $group[$metric]),
                default => number_format((int) $group[$metric], 0, ',', '.'),
            };

            $lines[] = $group['name']
                . ' - số sản phẩm: ' . number_format((int) $group['product_count'], 0, ',', '.')
                . ', giá trung bình: ' . $this->formatMoney((float) $group['average_price'])
                . ', đã bán: ' . number_format((int) $group['total_sold'], 0, ',', '.')
                . ', chỉ số đang xét: ' . $metricLabel . '.';
        }

        return implode(' ', $lines);
    }

    protected function buildDocumentContext(array $matches): string
    {
        $segments = [];

        foreach ($matches as $index => $match) {
            $segments[] = 'Nguồn ' . ($index + 1)
                . ': loại ' . ($match['type'] ?? 'unknown')
                . ', tiêu đề ' . ($match['title'] ?? 'Không rõ')
                . ', nội dung ' . Str::limit((string) ($match['context'] ?? ''), 1200)
                . '.';
        }

        return implode(' ', $segments);
    }

    protected function formatDocumentTitle(array $document): string
    {
        $title = (string) ($document['title'] ?? 'Tài liệu');
        $url = (string) ($document['url'] ?? '');

        if ($url === '') {
            return $title;
        }

        return '[' . $title . '](' . $url . ')';
    }

    protected function buildMissingPolicyAnswer(string $topic): string
    {
        return match ($topic) {
            'trade_in' => 'Xin lỗi, hiện mình chưa tìm thấy thông tin chương trình đổi cũ lấy mới phù hợp trong dữ liệu hiện có của MinhDang.',
            'warranty' => 'Xin lỗi, hiện mình chưa tìm thấy thông tin bảo hành phù hợp trong dữ liệu hiện có của MinhDang.',
            'shipping' => 'Xin lỗi, hiện mình chưa tìm thấy thông tin giao hàng phù hợp trong dữ liệu hiện có của MinhDang.',
            'return' => 'Xin lỗi, hiện mình chưa tìm thấy chính sách đổi trả hoặc trả hàng phù hợp trong dữ liệu hiện có của MinhDang, nên chưa thể xác nhận sản phẩm này có trả hàng được hay không.',
            default => 'Xin lỗi, hiện mình chưa tìm thấy thông tin chính sách phù hợp trong dữ liệu hiện có của MinhDang.',
        };
    }

    protected function calculateTopicScore(string $topic, string $normalizedDocument): float
    {
        $keywords = match ($topic) {
            'trade_in' => ['doi cu lay moi', 'doi cu', 'lay moi'],
            'warranty' => ['bao hanh'],
            'shipping' => ['giao hang', 'van chuyen', 'ship'],
            'return' => ['doi tra', 'tra hang', 'hoan tien', 'hoan tra'],
            default => ['chinh sach', 'quy dinh', 'huong dan'],
        };

        $score = 0.0;

        foreach ($keywords as $keyword) {
            if (str_contains($normalizedDocument, $keyword)) {
                $score += 0.34;
            }
        }

        return min(1.0, $score);
    }

    protected function detectPolicyTopic(string $normalizedQuestion): string
    {
        if ($this->containsAnyPhrase($normalizedQuestion, ['doi cu lay moi', 'doi cu', 'lay moi'])) {
            return 'trade_in';
        }

        if ($this->containsAnyPhrase($normalizedQuestion, ['bao hanh'])) {
            return 'warranty';
        }

        if ($this->containsAnyPhrase($normalizedQuestion, ['giao hang', 'van chuyen', 'ship'])) {
            return 'shipping';
        }

        if ($this->containsAnyPhrase($normalizedQuestion, ['doi tra', 'tra hang', 'hoan tien', 'hoan tra'])) {
            return 'return';
        }

        return 'policy';
    }

    protected function extractSignals(string $question): array
    {
        $normalized = $this->normalizeForMatching($question);
        $asksCount = $this->containsAnyPhrase($normalized, ['bao nhieu', 'co may', 'tong so', 'tong cong']);
        $asksAverage = $this->containsAnyPhrase($normalized, ['trung binh', 'binh quan', 'average']);
        $asksHighest = $this->containsAnyPhrase($normalized, ['cao nhat', 'lon nhat', 'nhieu nhat', 'dat nhat', 'ban chay nhat']);
        $asksLowest = $this->containsAnyPhrase($normalized, ['thap nhat', 'it nhat', 're nhat']);
        $mentionsBrand = $this->containsAnyPhrase($normalized, ['thuong hieu', 'hang']);
        $mentionsCategory = $this->containsAnyPhrase($normalized, ['danh muc', 'nganh hang', 'loai']);
        $mentionsProduct = $this->containsAnyPhrase($normalized, ['san pham', 'mat hang']);
        $mentionsPrice = $this->containsAnyPhrase($normalized, ['gia', 'muc gia']);
        $mentionsSold = $this->containsAnyPhrase($normalized, ['ban chay', 'da ban', 'mua nhieu', 'so luong ban']);
        $asksPolicy = $this->containsAnyPhrase($normalized, [
            'chinh sach',
            'doi tra',
            'tra hang',
            'hoan tien',
            'hoan tra',
            'bao hanh',
            'giao hang',
            'van chuyen',
            'doi cu',
            'lay moi',
        ]);

        return [
            'normalized' => $normalized,
            'asks_policy' => $asksPolicy,
            'asks_count' => $asksCount,
            'asks_average' => $asksAverage,
            'asks_highest' => $asksHighest,
            'asks_lowest' => $asksLowest,
            'mentions_brand' => $mentionsBrand,
            'mentions_category' => $mentionsCategory,
            'mentions_product' => $mentionsProduct,
            'mentions_price' => $mentionsPrice,
            'mentions_sold' => $mentionsSold,
            'asks_analytics' => ! $asksPolicy && (
                $asksCount
                || $asksAverage
                || $asksHighest
                || $asksLowest
                || ($mentionsBrand && $mentionsPrice)
                || ($mentionsBrand && $mentionsProduct)
                || ($mentionsCategory && $mentionsProduct)
            ),
        ];
    }

    protected function calculateKeywordOverlap(array $queryTokens, array $targetTokens): float
    {
        if ($queryTokens === [] || $targetTokens === []) {
            return 0.0;
        }

        $uniqueQueryTokens = array_values(array_unique($queryTokens));
        $targetLookup = array_flip(array_values(array_unique($targetTokens)));
        $matched = 0;

        foreach ($uniqueQueryTokens as $token) {
            if (isset($targetLookup[$token])) {
                $matched++;
            }
        }

        return $matched === 0 ? 0.0 : $matched / count($uniqueQueryTokens);
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

    protected function tokenize(?string $text): array
    {
        $normalized = $this->normalizeForMatching((string) $text);

        if ($normalized === '') {
            return [];
        }

        return array_values(array_filter(explode(' ', $normalized)));
    }

    protected function normalizeForMatching(string $text): string
    {
        $ascii = Str::of($this->normalizeText($text, ''))
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->trim()
            ->value();

        return preg_replace('/\s+/', ' ', $ascii) ?? '';
    }

    protected function normalizeText(?string $text, string $fallback = 'Không có'): string
    {
        $plainText = $text !== null
            ? html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8')
            : null;

        $normalized = preg_replace('/\s+/u', ' ', trim((string) $plainText));

        return $normalized !== null && $normalized !== '' ? $normalized : $fallback;
    }

    protected function formatMoney(float|int $value): string
    {
        return number_format((float) $value, 0, ',', '.') . ' VND';
    }
}
