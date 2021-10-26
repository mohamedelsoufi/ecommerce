<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Main_category extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'main_categories';

    protected $guarded = [];

    protected $casts = [
        'id'        => 'integer',
        'status'    => 'integer',
        'parent'    => 'integer',
    ];

    //relations
    public function Sub_category(){
        return $this->hasMany(Sub_category::class, 'main_cate_id');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function Products()
    {
        return $this->hasManyThrough(Product::class, Sub_category::class, 'main_cate_id', 'sub_categoriesId');
    }

    ////
    public function getStatus()
    {
        return $this->status == 1 ? 'active': 'un active';
    }

    public function getChangStatus()
    {
        return $this->status == 0 ? 'active': 'un active';
    }

    //scope
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
