<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // ADICIONADO: 'user_id' na lista
    protected $fillable = ['user_id', 'total_price', 'status'];

    // Um pedido tem muitos itens
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
