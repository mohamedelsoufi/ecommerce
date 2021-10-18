<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'address_id'            => $this->address_id,
            'status'                => $this->status,
            'total'                 => $this->total,
            'final_total'           => $this->final_total,
            'promoCode_id'          => $this->promoCode_id,
            'payment_method'        => $this->payment_method,
            'item_count'            => $this->Orderdetail->count(), //from relation
            'date'                  => date("Y-m-d H:i:s", strtotime($this->created_at)),
        ];
    }
}
