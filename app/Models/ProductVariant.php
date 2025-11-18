<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'original_price', 'price', 'sale_price', 'sku', 'image'];

    // Thuộc về sản phẩm gốc
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Có nhiều giá trị thuộc tính (Màu Xanh, Size L...)
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product_variant');
    }

    // Có thông tin tồn kho ở nhiều chi nhánh
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}