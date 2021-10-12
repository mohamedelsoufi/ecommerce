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
        //gender
        if($this->gender == 0){
            $gender = trans('guest.male');
        } else if($this->gender == 1){
            $gender = trans('guest.famale');
        } else {
            $gender = trans('guest.other');
        }

        //rating
        $ratnig = $this->Ratings;
        $count = $ratnig->count();
        $allRatnig = $ratnig->sum('rating');

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'describe'          => $this->describe,
            'price'             => $this->price,
            'status'            => ($this->status == 1) ? trans('guest.active'): trans('guest.not active'),
            'number_of_sell'    => $this->number_of_sell,
            'discound'          => $this->discound,
            'quantity'          => $this->quantity,
            'gender'            => $gender,
            'rating'            => [
                                        'count'     => $count,
                                        'rating'    => $allRatnig / $count,
                                    ],
            'sub_category'      => [
                                        'id'    => $this->Sub_category->id,     //relation
                                        'name'  => Sub_category::where('locale', Config::get('app.locale'))->where('parent', $this->Sub_category->id)->first()->name,
                                    ],
            'vendor'            => [
                                        'id'        => $this->Vender->id,       //relation
                                        'fullName'  => $this->Vender->fullName, //relation
                                ],
            'comments'          =>  $this->comments,
        ];
    }
}
