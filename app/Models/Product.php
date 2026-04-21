<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'brand_id',
        'name_pr',
        'slug',
        'quantity',
        'price',
        'image',
        'description',
        'discount',
        'gift',
        'sold',
        'status',
        'embedding',
    ];

    public function product_image()
    {
        return $this->hasMany(Product_Image::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function order_detail()
    {
        return $this->hasMany(Order_Detail::class, 'product_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function toSearchableText(): string
    {
        $productName = $this->normalizeSearchValue($this->name_pr, 'Sản phẩm không tên');
        $brandName = $this->resolveBrandName();
        $categoryName = $this->resolveCategoryName();
        $currentPrice = $this->formatPrice($this->getEffectivePrice());
        $originalPrice = $this->hasDiscount() ? $this->formatPrice($this->price) : null;
        $description = $this->normalizeHtmlText($this->description, 'Không có mô tả');
        $gift = $this->normalizeSearchValue($this->gift, 'Không có quà tặng');
        $sold = $this->formatWholeNumber($this->sold);
        $quantity = $this->formatWholeNumber($this->quantity);
        $productUrl = $this->getProductUrl();

        $segments = [
            "Sản phẩm: {$productName}",
            "Thương hiệu: {$brandName}",
            "Danh mục: {$categoryName}",
            "Giá hiện tại: {$currentPrice}",
            "Đã bán: {$sold}",
            "Tồn kho: {$quantity}",
            "Quà tặng: {$gift}",
            "Mô tả: {$description}",
            "Link sản phẩm: {$productUrl}",
        ];

        if ($originalPrice !== null) {
            $segments[] = "Giá gốc: {$originalPrice}";
        }

        return implode('. ', $segments) . '.';
    }

    public function getEffectivePrice(): float
    {
        $discountPrice = is_numeric($this->discount) ? (float) $this->discount : 0.0;
        $price = is_numeric($this->price) ? (float) $this->price : 0.0;

        return $discountPrice > 0 ? $discountPrice : $price;
    }

    public function hasDiscount(): bool
    {
        return is_numeric($this->discount)
            && is_numeric($this->price)
            && (float) $this->discount > 0
            && (float) $this->price > (float) $this->discount;
    }

    public function getProductUrl(): string
    {
        if (empty($this->slug) || empty($this->getKey())) {
            return url('/product');
        }

        if (Route::has('index.getProductDetail')) {
            return route('index.getProductDetail', [
                'product_slug' => $this->slug,
                'product_id' => $this->getKey(),
            ]);
        }

        return url('/product/' . $this->slug . '&id=' . $this->getKey());
    }

    public function getBrandName(): string
    {
        return $this->resolveBrandName();
    }

    public function getCategoryName(): string
    {
        return $this->resolveCategoryName();
    }

    protected function resolveBrandName(): string
    {
        if (! empty($this->brand?->name_bra)) {
            return $this->normalizeSearchValue($this->brand->name_bra);
        }

        if (! empty($this->brand_id)) {
            return 'Thương hiệu ID ' . $this->brand_id;
        }

        return 'Không rõ thương hiệu';
    }

    protected function resolveCategoryName(): string
    {
        if (! empty($this->category?->name_cate)) {
            return $this->normalizeSearchValue($this->category->name_cate);
        }

        if (! empty($this->category_id)) {
            return 'Danh mục ID ' . $this->category_id;
        }

        return 'Không rõ danh mục';
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

    protected function formatPrice(float|int|string|null $price): string
    {
        if (! is_numeric($price)) {
            return 'Không rõ';
        }

        return number_format((float) $price, 0, ',', '.') . ' VND';
    }

    protected function formatWholeNumber(float|int|string|null $value): string
    {
        if (! is_numeric($value)) {
            return '0';
        }

        return number_format((float) $value, 0, ',', '.');
    }
}
