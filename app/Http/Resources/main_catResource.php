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
        return [
            'id'                => $this->id,
            'name'              => $this->translate('en')->name,
            'image'             => $this->getImage(),
            'status'            => [
                'boolean' => $this->status,
                'string'  => $this->getStatus(),
            ],
        ];
    }
}