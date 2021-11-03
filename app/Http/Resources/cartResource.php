<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class cartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //get product images
        if($this->Product->image->first() != null){
            $images = $this->Product->image->transform(function ($item, $key){
                return url('public/uploads/products/' . $item->image);
            });;
        } else {
            $images = array(url('public/uploads/products/default.jpg'));
        }

        return [
            'id'                => $this->id,
            'quantity'          => $this->quantity,
            'color'             => $this->color,
            'size'              => $this->size,
            'product'           => [
                                    'id'                => $this->Product->id,
                                    'name'              => $this->Product->name,
                                    'describe'          => $this->Product->describe,
                                    'price'             => $this->Product->price,
                                    'discound'          => $this->Product->discound,
                                    'images'            => $images,
                                    ],
        ];
    }
}
