<?php

namespace App\Http\Resources;

use App\Models\Main_category;
use App\Models\Product;
use App\Models\Sub_category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class main_catResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //get main category (parent of each main category)
        if($this->parent != 0){
            //if it isn't a parent
            $main_cat = Main_category::where('id', '=' ,$this->parent)->first();
        } else {
            //if it is a parent
            $main_cat = Main_category::where('id', '=' ,$this->id)->first();
        }

        $products = Product::whereHas('Sub_category', function($q){
            $q->whereHas('Main_categories', function($query){
                $query->where('id', $this->parent);
            });
        })->get();

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'status'            => ($this->status == 1) ? trans('guest.active'): trans('guest.not active'),
            'iamge'             => ($main_cat->image != null) ? url('public/uploads/main_categories/' . $main_cat->image->src) :  url('public/uploads/main_categories/default.jpg'),
            'locale'            => $this->locale,
        ];
    }
}