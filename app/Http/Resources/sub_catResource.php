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
        //get main category (parent of each main category)
        $main_cat = Sub_category::where('id', '=' ,$this->parent)->first();

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'main_category_id'  => $this->main_cate_id,
            'status'            => $this->status,
            'iamge'             => ($main_cat->image != null) ? $main_cat->image->image : 'default.jpg',
            'locale'            => $this->locale,
        ];
    }
}
