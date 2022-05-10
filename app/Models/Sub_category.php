<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Sub_category extends Model implements TranslatableContract
{
    use HasFactory, Notifiable, Translatable;

    public $translatedAttributes = ['name'];
    protected $table = 'sub_categories';

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'main_cate_id'  => 'integer',
        'status'        => 'integer',
        'parent'        => 'integer',
    ];

    //relations
    public function Main_categories(){
        return $this->belongsTo(Main_category::class,'main_cate_id');
    }

    public function Products(){
        return $this->hasMany(Product::class, 'sub_categoriesId');
    }

    public function Image()
    {
        return $this->morphOne(Image::class, 'imageable');
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

    public function getImage(){
        if($this->Image != null){
            return url('public/uploads/main_categories/' . $this->Image->src);
        } else {
            return url('public/uploads/main_categories/default.jpg');
        }
    }

    //scope
    public function scopeActive($query)
    {
        $query->where('status', 1)->whereHas('Main_categories', function($q){
            $q->where('status', 1);
        });
    }
}
