<?php

namespace App\Http\Resources;

use App\Models\Rating;
use App\Models\Sub_category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class productResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'describe'          => $this->describe,
            'number_of_sell'    => $this->number_of_sell,
            'discound'          => $this->discound,
            'quantity'          => $this->quantity,
            'comments_count'    => $this->comments->count(),
            'colors'            => $this->colors,
            'sizes'             => $this->sizes,
            'image'             => $this->getImage(),
            'price'             => [
                                    'value'     => $this->price,
                                    'currency'  => '$',
                                ],
            'status'            => [
                                        'boolean' => $this->status,
                                        'string'  => $this->getStatus(),
                                    ],
            'gender'            => [
                                        'boolean' => $this->gender,
                                        'string'  => $this->getGender(),
                                    ],
            'rating'            => [
                                        'count'     => $this->Ratings->count(),
                                        'rating'    => $this->getRating(),
                                    ],
            'sub_category'      => [
                                        'id'    => $this->Sub_category->id,
                                        'name'  => $this->Sub_category->translate('en')->name,
                                    ],
            'vendor'            => [
                                        'id'        => $this->Vendor->id,
                                        'fullName'  => $this->Vendor->fullName
                                ],
        ];
    }
}
