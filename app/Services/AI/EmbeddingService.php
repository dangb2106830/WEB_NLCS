<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class EmbeddingService
{
    protected string $apiKey;

    protected string $baseUrl;

    protected string $model;

    public function __construct()
    {
        $this->apiKey = (string) config('services.gemini.api_key', '');
        $this->baseUrl = rtrim((string) config('services.gemini.base_url', ''), '/');
        $this->model = (string) config('services.gemini.embedding_model', 'gemini-embedding-001');
    }

    public function embed(string $text, ?string $taskType = null, ?string $title = null): array
    {
        $normalizedText = $this->normalizeText($text);

        if ($normalizedText === '') {
            throw new InvalidArgumentException('Embedding text must not be empty.');
        }

        if ($this->apiKey === '') {
            throw new RuntimeException('Gemini API key is not configured.');
        }

        if ($this->baseUrl === '') {
            throw new RuntimeException('Gemini API base URL is not configured.');
        }

        $payload = [
            'model' => 'models/' . $this->model,
            'content' => [
                'parts' => [
                    [
                        'text' => $normalizedText,
                    ],
                ],
            ],
        ];

        if ($taskType !== null) {
            $payload['taskType'] = $taskType;
        }

        if ($taskType === 'RETRIEVAL_DOCUMENT' && $title !== null && $title !== '') {
            $payload['title'] = $title;
        }

        $response = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->asJson()
            ->timeout(30)
            ->retry(2, 1000)
            ->withHeaders([
                'x-goog-api-key' => $this->apiKey,
            ])
            ->post('models/' . $this->model . ':embedContent', $payload);

        $response->throw();

        $values = $response->json('embedding.values');

        if (! is_array($values) || $values === []) {
            throw new RuntimeException('Gemini embedding response did not contain embedding values.');
        }

        return array_map(static fn ($value): float => (float) $value, $values);
    }

    public function embedDocument(string $text, ?string $title = null): array
    {
        return $this->embed($text, 'RETRIEVAL_DOCUMENT', $title);
    }

    public function embedQuery(string $text): array
    {
        return $this->embed($text, 'RETRIEVAL_QUERY');
    }

    protected function normalizeText(string $text): string
    {
        $plainText = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalized = preg_replace('/\s+/u', ' ', trim($plainText));

        return $normalized ?? '';
    }
}
