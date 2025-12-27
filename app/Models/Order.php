<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['total_price', 'status'];

    // Um pedido tem muitos itens
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
