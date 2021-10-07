<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Sub_category extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'sub_categories';

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'main_cate_id'  => 'integer',
        'status'        => 'integer',
    ];

    //relations
    public function Main_categories(){
        return $this->belongsTo(Main_category::class,'main_cate_id');
    }

    public function Products(){
        return $this->hasMany(Product::class, 'sub_categoriesId');
    }
}
