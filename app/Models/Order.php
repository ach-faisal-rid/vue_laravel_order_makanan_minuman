<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'table_no',
        'status',
        'total',
        'waitress_id',
        'chasier_id'
    ];

    public function ordersStatus()
    {
        return $this->hasMany(OrderStatus::class); // Assuming 'OrderStatus' is the model name
    }
    public function role()
    {
        return $this->hasMany(Role::class); // Assuming 'Role' is the model name
    }
}
