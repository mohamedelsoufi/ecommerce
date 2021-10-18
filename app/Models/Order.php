<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'orders';

    protected $guarded = [];

    protected $casts = [
        'id'                => 'integer',
        'user_id'           => 'integer',
        'total'             => 'integer',
        'final_total'       => 'integer',
        'promoCode_id'     => 'integer',
        'payment_status'    => 'integer',
    ];

    public function Orderdetail(){
        return $this->hasMany(Orderdetail::class, 'order_id');
    }
}
