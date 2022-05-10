<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class vendorResource extends JsonResource
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
            'fullName'          => $this->fullName,
            'phone'             => $this->phone,
            'email'             => $this->email,
            'birth'             => $this->birth,
            'image'             => $this->getImage(),
            'gender'            => [
                                    'boolean' => $this->gender,
                                    'string'  => $this->getGender(),
                                ],
            'status'            => [
                                        'boolean' => $this->status,
                                        'string'  => $this->getStatus(),
                                    ],
        ];
    }
}
