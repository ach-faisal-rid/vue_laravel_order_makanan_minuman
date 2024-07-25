<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_status';

    protected $fillable = [
        'order_id',
        'item_id',
        'qty',
        'price',
        'total'
    ];

    public function Item()
    {
        return $this->belongsTo(Item::class); // Assuming 'Item' is the model name
    }
    public function order()
    {
        return $this->belongsTo(Order::class); // Assuming 'Order' is the model name
    }
}
