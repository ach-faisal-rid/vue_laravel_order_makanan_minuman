<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id'
    ];

    public function Item()
    {
        return $this->hasMany(Item::class); // Assuming 'Item' is the model name
    }
    public function order()
    {
        return $this->hasMany(Order::class); // Assuming 'Order' is the model name
    }
}
