<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items';
    
    protected $fillable = [
        'name',
        'price',
        'category',
        'image',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class); // Assuming 'Order' is the model name
    }
    public function ordersStatus()
    {
        return $this->hasMany(OrderStatus::class); // Assuming 'OrderStatus' is the model name
    }
}
