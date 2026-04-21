<?php

namespace App\Services\AI;

use App\Models\Blog;
use App\Models\Policy;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SearchService
{
    public function __construct(
        protected EmbeddingService $embeddingService
    ) {
    }

    public function search(string $question, int $limit = 3): array
    {
        $queryEmbedding = $this->embeddingService->embedQuery($question);
        $signals = $this->extractSignals($question);

        return $this->collectCatalogInsightMatches($signals)
            ->concat($this->collectProductMatches($queryEmbedding, $signals))
            ->concat($this->collectBlogMatches($queryEmbedding, $signals))
            ->concat($this->collectPolicyMatches($queryEmbedding, $signals))
            ->sortByDesc('score')
            ->take($limit)
            ->values()
            ->all();
    }

    public function cosineSimilarity(array $a, array $b): float
    {
        if ($a === [] || $b === [] || count($a) !== count($b)) {
            return 0.0;
        }

        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach ($a as $index => $valueA) {
            $valueB = $b[$index];

            if (! is_numeric($valueA) || ! is_numeric($valueB)) {
                return 0.0;
            }

            $floatA = (float) $valueA;
            $floatB = (float) $valueB;

            $dotProduct += $floatA * $floatB;
            $normA += $floatA ** 2;
            $normB += $floatB ** 2;
        }

        if ($normA <= 0.0 || $normB <= 0.0) {
            return 0.0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    protected function collectProductMatches(array $queryEmbedding, array $signals): Collection
    {
        $products = Product::query()
            ->with([
                'brand:id,name_bra',
                'category:id,name_cate',
            ])
            ->whereNotNull('embedding')
            ->get();

        if ($products->isEmpty()) {
            return collect();
        }

        $priceStats = $this->buildProductPriceStats($products);
        $soldStats = $this->buildSoldStats($products);

        return $products
            ->map(function (Product $product) use ($queryEmbedding, $signals, $priceStats, $soldStats) {
                $productEmbedding = $this->decodeEmbedding($product->embedding);

                if ($productEmbedding === null) {
                    return null;
                }

                $semanticScore = $this->cosineSimilarity($queryEmbedding, $productEmbedding);
                $keywordScore = $this->calculateKeywordOverlap(
                    $signals['tokens'],
                    $this->buildProductSearchTokens($product)
                );

                $score = $this->scoreProductMatch(
                    $product,
                    $semanticScore,
                    $keywordScore,
                    $signals,
                    $priceStats,
                    $soldStats
                );

                $effectivePrice = $product->getEffectivePrice();
                $originalPrice = is_numeric($product->price) ? (float) $product->price : $effectivePrice;

                return [
                    'type' => 'product',
                    'id' => (int) $product->id,
                    'title' => $product->name_pr,
                    'score' => $score,
                    'semantic_score' => $semanticScore,
                    'keyword_score' => $keywordScore,
                    'url' => $product->getProductUrl(),
                    'context' => $product->toSearchableText(),
                    'snippet' => Str::limit($this->normalizeText($product->description), 300),
                    'brand' => $product->getBrandName(),
                    'category' => $product->getCategoryName(),
                    'price' => $effectivePrice,
                    'formatted_price' => $this->formatMoney($effectivePrice),
                    'original_price' => $originalPrice,
                    'formatted_original_price' => $this->formatMoney($originalPrice),
                    'discount_ratio' => $this->calculateDiscountRatio($product),
                    'sold' => (int) ($product->sold ?? 0),
                    'quantity' => (int) ($product->quantity ?? 0),
                ];
            })
            ->filter();
    }

    protected function collectBlogMatches(array $queryEmbedding, array $signals): Collection
    {
        return Blog::query()
            ->get()
            ->map(function (Blog $blog) use ($queryEmbedding, $signals) {
                $blogEmbedding = $this->decodeEmbedding($blog->embedding);
                $context = $this->buildBlogContext($blog);
                $keywordScore = $this->calculateKeywordOverlap(
                    $signals['tokens'],
                    $this->tokenize($blog->title . ' ' . $blog->intro . ' ' . $blog->content . ' ' . $blog->author)
                );
                $semanticScore = $blogEmbedding !== null
                    ? $this->cosineSimilarity($queryEmbedding, $blogEmbedding)
                    : 0.0;

                return [
                    'type' => 'blog',
                    'id' => (int) $blog->id,
                    'title' => $blog->title,
                    'score' => $this->scoreDocumentMatch($semanticScore, $keywordScore, $signals, 'blog'),
                    'semantic_score' => $semanticScore,
                    'keyword_score' => $keywordScore,
                    'url' => $this->buildBlogUrl($blog),
                    'context' => $context,
                    'snippet' => Str::limit($this->normalizeText($blog->intro ?: $blog->content), 300),
                ];
            })
            ->filter();
    }

    protected function collectPolicyMatches(array $queryEmbedding, array $signals): Collection
    {
        return Policy::query()
            ->get()
            ->map(function (Policy $policy) use ($queryEmbedding, $signals) {
                $policyEmbedding = $this->decodeEmbedding($policy->embedding);
                $context = $this->buildPolicyContext($policy);
                $keywordScore = $this->calculateKeywordOverlap(
                    $signals['tokens'],
                    $this->tokenize($policy->title . ' ' . $policy->content)
                );
                $semanticScore = $policyEmbedding !== null
                    ? $this->cosineSimilarity($queryEmbedding, $policyEmbedding)
                    : 0.0;

                return [
                    'type' => 'policy',
                    'id' => (int) $policy->id,
                    'title' => $policy->title,
                    'score' => $this->scoreDocumentMatch($semanticScore, $keywordScore, $signals, 'policy'),
                    'semantic_score' => $semanticScore,
                    'keyword_score' => $keywordScore,
                    'url' => null,
                    'context' => $context,
                    'snippet' => Str::limit($this->normalizeText($policy->content), 300),
                ];
            })
            ->filter();
    }

    protected function collectCatalogInsightMatches(array $signals): Collection
    {
        if (! $signals['asks_analytics']) {
            return collect();
        }

        $products = Product::query()
            ->with([
                'brand:id,name_bra',
                'category:id,name_cate',
            ])
            ->get();

        if ($products->isEmpty()) {
            return collect();
        }

        $brandStats = $this->buildGroupedStats(
            $products,
            fn (Product $product): string => $product->getBrandName()
        );
        $categoryStats = $this->buildGroupedStats(
            $products,
            fn (Product $product): string => $product->getCategoryName()
        );

        $documents = collect([
            [
                'id' => 'store-overview',
                'title' => 'Tổng quan cửa hàng',
                'focus' => 'store',
                'context' => $this->buildStoreOverviewContext($products, $brandStats, $categoryStats),
            ],
            [
                'id' => 'brand-stats',
                'title' => 'Thống kê thương hiệu',
                'focus' => 'brand',
                'context' => $this->buildStatsContext('Thống kê thương hiệu', $brandStats),
            ],
            [
                'id' => 'category-stats',
                'title' => 'Thống kê danh mục',
                'focus' => 'category',
                'context' => $this->buildStatsContext('Thống kê danh mục', $categoryStats),
            ],
        ]);

        return $documents
            ->map(function (array $document) use ($signals) {
                $keywordScore = $this->calculateKeywordOverlap(
                    $signals['tokens'],
                    $this->tokenize($document['title'] . ' ' . $document['context'])
                );
                $score = $this->scoreInsightMatch($keywordScore, $signals, $document['focus']);

                return [
                    'type' => 'insight',
                    'id' => $document['id'],
                    'title' => $document['title'],
                    'score' => $score,
                    'semantic_score' => 0.0,
                    'keyword_score' => $keywordScore,
                    'url' => null,
                    'context' => $document['context'],
                    'snippet' => Str::limit($document['context'], 300),
                ];
            })
            ->filter(fn (array $document): bool => ($document['score'] ?? 0.0) >= 0.45);
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
                    'count' => $group->count(),
                    'avg_price' => $prices->avg() ?? 0.0,
                    'min_price' => $prices->min() ?? 0.0,
                    'max_price' => $prices->max() ?? 0.0,
                    'total_sold' => $group->sum(fn (Product $product): int => (int) ($product->sold ?? 0)),
                ];
            })
            ->sortBy('name')
            ->values();
    }

    protected function buildStoreOverviewContext(Collection $products, Collection $brandStats, Collection $categoryStats): string
    {
        $averagePrice = $products
            ->map(fn (Product $product): float => $product->getEffectivePrice())
            ->filter(fn (float $price): bool => $price > 0)
            ->avg() ?? 0.0;

        $topBrandByAveragePrice = $brandStats->sortByDesc('avg_price')->first();
        $topBrandByCount = $brandStats->sortByDesc('count')->first();
        $topCategoryByCount = $categoryStats->sortByDesc('count')->first();

        $segments = [
            'Tổng số sản phẩm: ' . number_format($products->count(), 0, ',', '.'),
            'Tổng số thương hiệu: ' . number_format($brandStats->count(), 0, ',', '.'),
            'Tổng số danh mục: ' . number_format($categoryStats->count(), 0, ',', '.'),
            'Giá trung bình toàn cửa hàng: ' . $this->formatMoney($averagePrice),
        ];

        if ($topBrandByAveragePrice !== null) {
            $segments[] = 'Thương hiệu có giá trung bình cao nhất: ' . $topBrandByAveragePrice['name']
                . ' với ' . $this->formatMoney($topBrandByAveragePrice['avg_price']);
        }

        if ($topBrandByCount !== null) {
            $segments[] = 'Thương hiệu có nhiều sản phẩm nhất: ' . $topBrandByCount['name']
                . ' với ' . number_format((int) $topBrandByCount['count'], 0, ',', '.') . ' sản phẩm';
        }

        if ($topCategoryByCount !== null) {
            $segments[] = 'Danh mục có nhiều sản phẩm nhất: ' . $topCategoryByCount['name']
                . ' với ' . number_format((int) $topCategoryByCount['count'], 0, ',', '.') . ' sản phẩm';
        }

        return implode('. ', $segments) . '.';
    }

    protected function buildStatsContext(string $title, Collection $stats): string
    {
        $lines = [$title . ':'];

        foreach ($stats as $item) {
            $lines[] = $item['name']
                . ' - số sản phẩm: ' . number_format((int) $item['count'], 0, ',', '.')
                . ', giá trung bình: ' . $this->formatMoney((float) $item['avg_price'])
                . ', giá thấp nhất: ' . $this->formatMoney((float) $item['min_price'])
                . ', giá cao nhất: ' . $this->formatMoney((float) $item['max_price'])
                . ', đã bán: ' . number_format((int) $item['total_sold'], 0, ',', '.')
                . '.';
        }

        return implode(' ', $lines);
    }

    protected function scoreInsightMatch(float $keywordScore, array $signals, string $focus): float
    {
        $score = 0.22 + ($keywordScore * 0.38);

        if ($signals['asks_analytics']) {
            $score += 0.28;
        }

        if ($focus === 'brand' && $signals['mentions_brand']) {
            $score += 0.08;
        }

        if ($focus === 'category' && $signals['mentions_category']) {
            $score += 0.08;
        }

        if ($focus === 'store' && $signals['mentions_store']) {
            $score += 0.08;
        }

        return round(max(0.0, min(1.0, $score)), 6);
    }

    protected function scoreDocumentMatch(
        float $semanticScore,
        float $keywordScore,
        array $signals,
        string $type
    ): float {
        $score = $semanticScore > 0.0
            ? ($semanticScore * 0.78) + ($keywordScore * 0.22)
            : ($keywordScore * 0.72);

        if ($signals['asks_policy'] && in_array($type, ['policy', 'blog'], true)) {
            $score += 0.18;
        }

        if ($signals['asks_analytics'] && $type === 'product') {
            $score -= 0.20;
        }

        return round(max(0.0, min(1.0, $score)), 6);
    }

    protected function scoreProductMatch(
        Product $product,
        float $semanticScore,
        float $keywordScore,
        array $signals,
        array $priceStats,
        array $soldStats
    ): float {
        $score = ($semanticScore * 0.72) + ($keywordScore * 0.28);
        $brandMatch = $this->queryMentionsValue($signals['normalized'], $product->getBrandName());
        $categoryMatch = $this->queryMentionsValue($signals['normalized'], $product->getCategoryName());
        $soldScore = $this->normalizeNumber((int) ($product->sold ?? 0), $soldStats['min'], $soldStats['max']);
        $cheapScore = $this->reverseNormalizeNumber($product->getEffectivePrice(), $priceStats['min'], $priceStats['max']);
        $premiumScore = $this->normalizeNumber($product->getEffectivePrice(), $priceStats['min'], $priceStats['max']);
        $discountRatio = $this->calculateDiscountRatio($product);

        if ($brandMatch) {
            $score += 0.12;
        }

        if ($categoryMatch) {
            $score += 0.08;
        }

        if ($signals['asks_recommendation']) {
            $score += ($soldScore * 0.12) + ($discountRatio * 0.06);
        }

        if ($signals['asks_best']) {
            $score += ($soldScore * 0.14) + ($discountRatio * 0.05);
        }

        if ($signals['asks_bestseller']) {
            $score += $soldScore * 0.24;
        }

        if ($signals['asks_cheapest']) {
            $score += $cheapScore * 0.18;
        }

        if ($signals['asks_premium']) {
            $score += $premiumScore * 0.16;
        }

        if ($signals['asks_policy']) {
            $score -= 0.18;
        }

        if ($signals['asks_analytics']) {
            $score -= 0.22;
        }

        if ((int) ($product->quantity ?? 0) > 0) {
            $score += 0.02;
        }

        return round(max(0.0, min(1.0, $score)), 6);
    }

    protected function buildProductPriceStats(Collection $products): array
    {
        $prices = $products
            ->map(fn (Product $product): float => $product->getEffectivePrice())
            ->filter(fn (float $price): bool => $price > 0)
            ->values();

        return [
            'min' => $prices->min() ?? 0.0,
            'max' => $prices->max() ?? 0.0,
        ];
    }

    protected function buildSoldStats(Collection $products): array
    {
        $sold = $products
            ->map(fn (Product $product): int => (int) ($product->sold ?? 0))
            ->values();

        return [
            'min' => $sold->min() ?? 0,
            'max' => $sold->max() ?? 0,
        ];
    }

    protected function buildProductSearchTokens(Product $product): array
    {
        return $this->tokenize(implode(' ', [
            $product->name_pr,
            $product->getBrandName(),
            $product->getCategoryName(),
            $product->gift,
            $this->normalizeText($product->description),
        ]));
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

    protected function extractSignals(string $question): array
    {
        $normalized = $this->normalizeForMatching($question);
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
        $mentionsBrand = $this->containsAnyPhrase($normalized, [
            'thuong hieu',
            'hang',
        ]);
        $mentionsCategory = $this->containsAnyPhrase($normalized, [
            'danh muc',
            'nganh hang',
            'loai',
        ]);
        $mentionsStore = $this->containsAnyPhrase($normalized, [
            'cua hang',
            'shop',
            'sieu thi',
            'minhdang',
            'plaza',
        ]);

        return [
            'normalized' => $normalized,
            'tokens' => $this->tokenize($question),
            'asks_count' => $asksCount,
            'asks_average' => $asksAverage,
            'asks_recommendation' => $this->containsAnyPhrase($normalized, [
                'goi y',
                'de xuat',
                'nen mua',
                'tham khao',
                'phu hop',
                'tot nhat',
                'noi bat',
                'ban chay',
            ]),
            'asks_best' => $this->containsAnyPhrase($normalized, [
                'tot nhat',
                'noi bat',
                'dang mua',
                'tot hon',
            ]),
            'asks_bestseller' => $this->containsAnyPhrase($normalized, [
                'ban chay',
                'pho bien',
                'mua nhieu',
            ]),
            'asks_cheapest' => $this->containsAnyPhrase($normalized, [
                'gia re',
                're nhat',
                'tiet kiem',
                'thap nhat',
            ]),
            'asks_premium' => $this->containsAnyPhrase($normalized, [
                'cao cap',
                'dat nhat',
                'premium',
                'xin',
            ]),
            'asks_policy' => $this->containsAnyPhrase($normalized, [
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
            ]),
            'asks_analytics' => $asksCount
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
                || ($mentionsBrand && $asksAverage)
                || ($mentionsBrand && $asksCount)
                || ($mentionsCategory && $asksCount),
            'mentions_brand' => $mentionsBrand,
            'mentions_category' => $mentionsCategory,
            'mentions_store' => $mentionsStore,
        ];
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

    protected function queryMentionsValue(string $normalizedQuery, ?string $value): bool
    {
        if ($value === null || trim($value) === '') {
            return false;
        }

        return str_contains($normalizedQuery, $this->normalizeForMatching($value));
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

    protected function normalizeNumber(float|int $value, float|int $min, float|int $max): float
    {
        $floatValue = (float) $value;
        $floatMin = (float) $min;
        $floatMax = (float) $max;

        if ($floatMax <= $floatMin) {
            return $floatValue > 0 ? 1.0 : 0.0;
        }

        return max(0.0, min(1.0, ($floatValue - $floatMin) / ($floatMax - $floatMin)));
    }

    protected function reverseNormalizeNumber(float|int $value, float|int $min, float|int $max): float
    {
        return 1.0 - $this->normalizeNumber($value, $min, $max);
    }

    protected function calculateDiscountRatio(Product $product): float
    {
        if (! $product->hasDiscount()) {
            return 0.0;
        }

        $price = is_numeric($product->price) ? (float) $product->price : 0.0;

        if ($price <= 0) {
            return 0.0;
        }

        return max(0.0, min(1.0, ($price - $product->getEffectivePrice()) / $price));
    }

    protected function decodeEmbedding(mixed $embedding): ?array
    {
        if (! is_string($embedding) || trim($embedding) === '') {
            return null;
        }

        $decoded = json_decode($embedding, true);

        if (! is_array($decoded) || $decoded === []) {
            return null;
        }

        foreach ($decoded as $value) {
            if (! is_numeric($value)) {
                return null;
            }
        }

        return array_map(static fn ($value): float => (float) $value, array_values($decoded));
    }

    protected function buildBlogUrl(Blog $blog): string
    {
        if (empty($blog->slug) || empty($blog->id)) {
            return url('/blog');
        }

        if (Route::has('index.getBlog')) {
            return route('index.getBlog', [
                'blog_slug' => $blog->slug,
                'blog_id' => $blog->id,
            ]);
        }

        return url('/blog/' . $blog->slug . '&id=' . $blog->id);
    }

    protected function buildBlogContext(Blog $blog): string
    {
        $title = $this->normalizeText($blog->title, 'Bài viết không tiêu đề');
        $intro = $this->normalizeText($blog->intro, 'Không có phần giới thiệu');
        $content = $this->normalizeText($blog->content, 'Không có nội dung');
        $author = $this->normalizeText($blog->author, 'Không rõ tác giả');
        $url = $this->buildBlogUrl($blog);

        return "Bài viết: {$title}. Giới thiệu: {$intro}. Nội dung: {$content}. Tác giả: {$author}. Link bài viết: {$url}.";
    }

    protected function buildPolicyContext(Policy $policy): string
    {
        $title = $this->normalizeText($policy->title, 'Chính sách không tiêu đề');
        $content = $this->normalizeText($policy->content, 'Không có nội dung');

        return "Chính sách: {$title}. Nội dung: {$content}.";
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
