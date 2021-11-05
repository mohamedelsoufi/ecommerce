<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class orderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'order_id'              => $this->order_id,
            'quantity'              => $this->quantity,
            'product_price'         => $this->product_price,
            'discound'              => $this->product_discound,
            'product_total_price'   => $this->product_total_price,
            'color'                 => $this->color,
            'size'                  => $this->size,
            'product'               => [
                'id'                => $this->Product->id,
                'name'              => $this->Product->name,
                'describe'          => $this->Product->describe,
                'price'             => $this->Product->price,
                'status'            => ($this->Product->status == 1) ? trans('guest.active'): trans('guest.not active'),
                'number_of_sell'    => $this->Product->number_of_sell,
                'discound'          => $this->Product->discound,
                'quantity'          => $this->Product->quantity,
                'image'             => ($this->Product->image->first() != null) ? $this->Product->image : array(['image' => 'default.jpg']),
                ],
        ];
    }
}
