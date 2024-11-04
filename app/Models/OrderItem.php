<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'order_id',    // ID del pedido al que pertenece este item
        'product_id',  // ID del producto
        'quantity',    // Cantidad del producto
        'price',       // Precio del producto
        'status'       // Estado del item
    ];

    
    public function product() {
        return $this->belongsTo(Product::class);
    }

    
    public function order() {
        return $this->belongsTo(Order::class);
    }
}
