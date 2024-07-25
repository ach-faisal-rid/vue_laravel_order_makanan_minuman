<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'customer_name',
        'table_no',
        'status',
        'total',
        'waitress_id',
        'chasier_id'
    ];

    public function orderStatus()
    {
        return $this->hasMany(OrderStatus::class); // Assuming 'OrderStatus' is the model name
    }
    public function waitress()
    {
        return $this->belongsTo(User::class, 'waitress_id', 'id'); // Assuming 'Role' is the model name
    }
    public function chasier()
    {
        return $this->belongsTo(User::class, 'chasier_id', 'id'); // Assuming 'Role' is the model name
    }
}
