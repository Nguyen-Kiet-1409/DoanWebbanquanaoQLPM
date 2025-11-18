<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'phone'];

    // Chi nhánh có nhiều dòng tồn kho
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    // Chi nhánh xử lý nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}