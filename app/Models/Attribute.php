<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Một thuộc tính (Màu sắc) có nhiều giá trị (Xanh, Đỏ)
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}