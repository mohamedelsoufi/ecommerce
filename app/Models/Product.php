<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'products';

    protected $guarded = [];

    protected $casts = [
        'id'                    => 'integer',
        'price'                 => 'integer',
        'sub_categoriesId'      => 'integer',
        'vender_id'             => 'integer',
        'number_of_sell'        => 'integer',
        'discound'              => 'integer',
        'quantity'              => 'integer',
        'gender'                => 'integer',
        'sizes'                 => 'array',
        'color'                 => 'array',
    ];

    //relations
    public function Sub_category(){
        return $this->belongsTo(Sub_category::class,'sub_categoriesId');
    }
    public function Vender(){
        return $this->belongsTo(Vender::class,'vender_id');
    }

    public function Comments(){
        return $this->hasMany(Comment::class, 'product_id');
    }

    public function Favourites(){
        return $this->hasMany(Favourit::class, 'product_id');
    }

    public function Loves(){
        return $this->hasMany(Love::class, 'product_id');
    }

    public function Ratings(){
        return $this->hasMany(Rating::class, 'product_id');
    }

    public function image()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    ////
    /////
    public function getGender()
    {
        return $this->gender == 0 ? 'male': 'famale';
    }

    public function getStatus()
    {
        return $this->status == 1 ? 'active': 'un active';
    }

    public function getChangStatus()
    {
        return $this->status == 0 ? 'active': 'un active';
    }

    public function getImage()
    {
        if($this->image->first() != null){
            return url('public/uploads/products'). '/'  . $this->image->first()->image;
        } else {
            return url('public/uploads/products/default.jpg');
        }
    }

    //scop
    public function scopeActive($query)
    {
        return $query->where('quantity', '>', 0)
                        ->where('status', 1)
                        ->whereHas('Sub_category', function($q){
                                $q->where('status', 1)->whereHas('Main_categories', function($query){
                                    $query->where('status', 1);
                                });
                        });
    }
}
