<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $table = 'policies';

    protected $fillable = [
        'title',
        'content',
        'embedding',
    ];

    public function toSearchableText(): string
    {
        $title = $this->normalizeSearchValue($this->title, 'Chính sách không tiêu đề');
        $content = $this->normalizeHtmlText($this->content, 'Không có nội dung');

        return "Chính sách: {$title}. Nội dung: {$content}.";
    }

    protected function normalizeHtmlText(?string $value, string $fallback = 'Không có'): string
    {
        $plainText = $value !== null
            ? html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')
            : null;

        return $this->normalizeSearchValue($plainText, $fallback);
    }

    protected function normalizeSearchValue(?string $value, string $fallback = 'Không có'): string
    {
        $normalized = preg_replace('/\s+/u', ' ', trim((string) $value));

        return $normalized !== null && $normalized !== '' ? $normalized : $fallback;
    }
}
