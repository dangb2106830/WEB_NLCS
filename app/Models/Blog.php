<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $table = 'blogs';
    protected $fillable = [
        'title',
        'intro',
        'content',
        'created_date' ,
        'image',
        'slug',
        'author' 
    ];

    public function staff() {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
