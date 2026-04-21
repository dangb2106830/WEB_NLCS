<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Policy;
use App\Models\Product;
use App\Services\AI\EmbeddingService;
use Illuminate\Console\Command;
use Throwable;

class AiIndexCommand extends Command
{
    protected $signature = 'ai:index
        {--force : Re-index records even if they already have embeddings}
        {--only=* : Limit indexing to one or more sources: products, blogs, policies}';

    protected $description = 'Generate and store embeddings for products, blogs, and policies.';

    public function __construct(
        protected EmbeddingService $embeddingService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $targets = $this->resolveTargets();

        if ($targets === []) {
            $this->error('No valid sources were selected. Use products, blogs, or policies.');

            return self::FAILURE;
        }

        $hasFailures = false;

        foreach ($targets as $target) {
            $result = $this->indexTarget($target);
            $hasFailures = $hasFailures || ! $result;
        }

        if ($hasFailures) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    protected function indexTarget(array $target): bool
    {
        $query = $target['query']();

        if (! $this->option('force')) {
            $query->whereNull('embedding');
        }

        $total = (clone $query)->count();
        $label = $target['label'];
        $modelLabel = $target['model_label'];

        if ($total === 0) {
            $this->info("No {$label} need indexing.");

            return true;
        }

        $this->info("Indexing {$total} {$label}...");
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $indexedCount = 0;
        $failedIds = [];

        $query->chunkById(50, function ($records) use ($target, &$indexedCount, &$failedIds, $progressBar, $modelLabel) {
            foreach ($records as $record) {
                try {
                    $embedding = $this->embeddingService->embedDocument(
                        $target['text']($record),
                        $target['title']($record)
                    );

                    $record->forceFill([
                        'embedding' => json_encode($embedding, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ])->saveQuietly();

                    $indexedCount++;
                } catch (Throwable $exception) {
                    $failedIds[] = $record->getKey();
                    $this->newLine();
                    $this->error("Failed to index {$modelLabel} ID {$record->getKey()}: {$exception->getMessage()}");
                } finally {
                    $progressBar->advance();
                }
            }
        });

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Indexed {$indexedCount}/{$total} {$label}.");

        if ($failedIds !== []) {
            $this->warn('Failed ' . $label . ' IDs: ' . implode(', ', $failedIds));

            return false;
        }

        $this->info(ucfirst($label) . ' embeddings have been stored successfully.');

        return true;
    }

    protected function resolveTargets(): array
    {
        $requestedTargets = collect((array) $this->option('only'))
            ->filter(fn (mixed $value): bool => is_string($value) && trim($value) !== '')
            ->map(fn (string $value): string => strtolower(trim($value)))
            ->values()
            ->all();

        $definitions = [
            'products' => [
                'label' => 'products',
                'model_label' => 'product',
                'query' => fn () => Product::query()
                    ->with([
                        'brand:id,name_bra',
                        'category:id,name_cate',
                    ])
                    ->orderBy('id'),
                'title' => fn (Product $product): string => (string) $product->name_pr,
                'text' => fn (Product $product): string => $product->toSearchableText(),
            ],
            'blogs' => [
                'label' => 'blogs',
                'model_label' => 'blog',
                'query' => fn () => Blog::query()->orderBy('id'),
                'title' => fn (Blog $blog): string => (string) $blog->title,
                'text' => fn (Blog $blog): string => $blog->toSearchableText(),
            ],
            'policies' => [
                'label' => 'policies',
                'model_label' => 'policy',
                'query' => fn () => Policy::query()->orderBy('id'),
                'title' => fn (Policy $policy): string => (string) $policy->title,
                'text' => fn (Policy $policy): string => $policy->toSearchableText(),
            ],
        ];

        if ($requestedTargets === []) {
            return array_values($definitions);
        }

        return collect($requestedTargets)
            ->map(fn (string $target): ?array => $definitions[$target] ?? null)
            ->filter()
            ->values()
            ->all();
    }
}
