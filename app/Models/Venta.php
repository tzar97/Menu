<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'preparation_time', 'sale_date'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
