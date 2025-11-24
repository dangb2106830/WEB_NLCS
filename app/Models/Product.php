<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
    
    public function product_image() {
        return $this->hasMany(Product_Image::class, 'product_id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function order_detail() {
        return $this->hasMany(Order_Detail::class, 'product_id');
    }

    public function brand() {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    
}
