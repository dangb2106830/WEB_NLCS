<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $table = 'staffs';
    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'birth_day',
        'address',
        'phone',
    ];
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order() {
        return $this->hasMany(Order::class, 'staff_id');
    }
}
