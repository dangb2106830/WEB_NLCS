<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'intro',
        'content',
        'created_date',
        'image',
        'slug',
        'author',
        'embedding',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function toSearchableText(): string
    {
        $title = $this->normalizeSearchValue($this->title, 'Bài viết không tiêu đề');
        $intro = $this->normalizeHtmlText($this->intro, 'Không có phần giới thiệu');
        $content = $this->normalizeHtmlText($this->content, 'Không có nội dung');
        $author = $this->normalizeSearchValue($this->author, 'Không rõ tác giả');
        $createdDate = $this->normalizeSearchValue($this->created_date, 'Không rõ ngày đăng');
        $url = $this->getBlogUrl();

        return implode('. ', [
            "Bài viết: {$title}",
            "Giới thiệu: {$intro}",
            "Nội dung: {$content}",
            "Tác giả: {$author}",
            "Ngày đăng: {$createdDate}",
            "Link bài viết: {$url}",
        ]) . '.';
    }

    public function getBlogUrl(): string
    {
        if (empty($this->slug) || empty($this->getKey())) {
            return url('/blog');
        }

        if (Route::has('index.getBlog')) {
            return route('index.getBlog', [
                'blog_slug' => $this->slug,
                'blog_id' => $this->getKey(),
            ]);
        }

        return url('/blog/' . $this->slug . '&id=' . $this->getKey());
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
