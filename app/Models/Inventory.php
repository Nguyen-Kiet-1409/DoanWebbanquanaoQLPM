<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    
    // Khai báo tên bảng rõ ràng (đề phòng Laravel không tự tìm ra số nhiều của inventory)
    protected $table = 'inventory'; 

    protected $fillable = ['branch_id', 'product_variant_id', 'quantity'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}