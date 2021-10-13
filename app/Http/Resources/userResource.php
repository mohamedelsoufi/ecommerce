<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class userResource extends JsonResource
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

        return [
            'id'                => $this->id,
            'fullName'          => $this->fullName,
            'phone'             => $this->phone,
            'email'             => $this->email,
            'status'            => ($this->status == 1) ? trans('guest.active'): trans('guest.not active'),
            'gender'            => $gender,
            'birth'             => $this->birth,
            'iamge'             => ($this->image != null)? url('public/uploads/users/' . $this->image->image) : url('public/uploads/users/default.jpg'),
            'address'           => $this->address,
        ];
    }
}
