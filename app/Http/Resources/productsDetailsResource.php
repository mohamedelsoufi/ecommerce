<?php

namespace App\Http\Resources;

use App\Models\Orderdetail;
use App\Models\Sub_category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class productsDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //gender
        if($this->gender == 0){
            $gender = trans('guest.male');
        } else if($this->gender == 1){
            $gender = trans('guest.famale');
        } else {
            $gender = trans('guest.other');
        }

        //rating
        $ratnig = $this->Ratings;
        $count = $ratnig->count();
        $allRatnig = $ratnig->sum('rating');

        //get product images
        if($this->image->first() != null){
            $images = $this->image->transform(function ($item, $key) {
                return url('public/uploads/products/' . $item->image);
            });
        } else {
            $images = array(url('public/uploads/products/default.jpg'));
        }

        //get returned products count
        $returnedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 3);
        })->whereHas('Product', function($q){
            $q->notDelete()->where('vender_id', $this->Vender->id);
        })->get();
        
        $returned_count = $returnedOrderdetails->sum(function ($product) {
            return $product['quantity'];
        });

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'describe'          => $this->describe,
            'price'             => $this->price,
            'status'            => ($this->status == 1) ? trans('guest.active'): trans('guest.not active'),
            'number_of_sell'    => $this->number_of_sell,
            'discound'          => $this->discound,
            'gender'            => $gender,
            'comments_count'    => $this->comments->count(),
            'date'              => date("Y-m-d H:i:s", strtotime($this->created_at)),
            'quantity'          => [
                                    'quantity'           => $this->quantity + $this->number_of_sell,
                                    'number_of_sell'     => $this->number_of_sell, 
                                    'returned_count'     => $returned_count,
                                    'remaining_quantity' => $this->quantity,
                                ],
            'images'            => $images,
            'colors'            => $this->colors,
            'sizes'             => $this->sizes,
            'rating'            => [
                                        'count'     => $count,
                                        'rating'    => ($count != 0) ? $allRatnig / $count : 0,
                                    ],
            'sub_category'      => [
                                        'id'    => $this->Sub_category->id,     //relation
                                        'name'  => Sub_category::where('locale', Config::get('app.locale'))->where('parent', $this->Sub_category->id)->first()->name,
                                    ],
        ];
    }
}
