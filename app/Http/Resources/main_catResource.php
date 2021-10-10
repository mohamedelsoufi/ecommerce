<?php

namespace App\Http\Resources;

use App\Models\Main_category;
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
        //get main category (parent of each main category)
        $main_cat = Main_category::where('id', '=' ,$this->parent)->first();

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'status'            => $this->status,
            'locale'            => $this->locale,
            'image'             => ($main_cat->image != null) ? $main_cat->image->image : 'default.jpg',
            'sub_categorys'     => sub_catResource::collection(Sub_category::where('locale', '=', Config::get('app.locale'))->where('main_cate_id', $main_cat->id)->get()),
        ];
    }
}
