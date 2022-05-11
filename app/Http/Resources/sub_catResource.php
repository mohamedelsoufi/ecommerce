<?php

namespace App\Http\Resources;

use App\Models\Sub_category;
use Illuminate\Http\Resources\Json\JsonResource;

class sub_catResource extends JsonResource
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
            'name'              => $this->translate('en')->name,
            'image'             => $this->getImage(),
            'status'            => [
                                        'boolean' => $this->status,
                                        'string'  => $this->getStatus(),
                                    ],
            'main_category'     => new main_catResource($this->Main_categories),
            
        ];
    }
}
