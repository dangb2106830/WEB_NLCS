<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Image extends Model
{
    use HasFactory;
    protected $table = 'product_images';
    protected $fillable = [
        'product_id',
        'pro_image',
    ];

    public function setProImageAttribute($value){
        $this->attributes['pro_image'] = json_encode($value);
    }
    
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
