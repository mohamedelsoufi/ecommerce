<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class addressResource extends JsonResource
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
            'country'           => $this->country,
            'city'              => $this->city,
            'Neighborhood'      => $this->Neighborhood,
            'region'            => $this->region,
            'street_name'       => $this->street_name,
            'building_number'   => $this->building_number,
            'notes'             => $this->notes,
        ];
    }
}
