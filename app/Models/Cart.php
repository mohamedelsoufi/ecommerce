<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Cart extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'carts';

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'product_id'    => 'integer',
        'quantity'       => 'integer',
    ];

    public function Product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
