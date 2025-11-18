<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name', 'email', 'password', 'phone', 'address', 'role', 'branch_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Quan hệ: User có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // User thuộc về 1 chi nhánh
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}