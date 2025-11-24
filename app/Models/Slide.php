<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;
    protected $table = 'slides';
    protected $fillable = [
        'name_slide',
        'image',
        'content',
        'slug'
    ];
    
    public function staff() {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
