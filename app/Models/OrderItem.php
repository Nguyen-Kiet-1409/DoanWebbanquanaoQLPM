<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Khai báo các cột được phép lưu
    protected $fillable = [
        'order_id',
        'product_variant_id',
        'quantity',
        'price'
    ];

    // Quan hệ: Chi tiết này thuộc về Đơn hàng nào
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Quan hệ: Chi tiết này là của Biến thể sản phẩm nào
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}