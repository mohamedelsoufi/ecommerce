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

    //relations
    public function Orderdetail(){
        return $this->hasMany(Orderdetail::class, 'order_id');
    }

    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function PromoCode(){
        return $this->belongsTo(Promo_code::class,'promoCode_id');
    }

    ////

    public function getStatus()
    {
        if($this->status == -1){
            return 'cancel';
        } else if ($this->status == 0){
            return 'not active';
        } else if($this->status == 1){
            return 'delivery';
        } elseif ($this->status== 2){
            return 'finished';
        }
    }

    public function getDiscound(){
        if($this->PromoCode != null){
            return $this->PromoCode->discound;
        } else {
            return 0;
        }
    }
}
