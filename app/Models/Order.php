<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'staff_id',
        'customer_id',
        'created_date',
        'total_money',
    ];
    
    public function staff() {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function order_detail() {
        return $this->hasMany(Order_Detail::class, 'order_id');
    }
}
