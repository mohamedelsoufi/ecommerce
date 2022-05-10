<?php

namespace App\Http\Controllers\Api\site\users;

use App\Http\Controllers\Controller;
use App\Http\Resources\addressResource;
use App\Models\Address;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class addresses extends Controller
{
    use response;
    public function index(){
        if (! $user = auth('user')->user()) 
            return response::faild(trans('user.user not found'), 404, 'E04');

        return response::success(
            trans('all.update address success'),
            200,
            'addresses',
            addressResource::collection($user->Addresses)
        );
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'country'           => 'required|string',
            'city'              => 'required|string',
            'Neighborhood'      => 'required|string',
            'region'            => 'required|string',
            'street_name'       => 'required|string',
            'building_number'   => 'required|string',
            'notes'             => 'required|string',
        ]);

        if($validator->fails())
            return response::faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user()) 
            return response::faild(trans('user.user not found'), 404, 'E04');

        $new_address = $this->insert_address($request, $user);

        return response::success(
            trans('all.add address success'),
            200,
            'address',
            new addressResource($new_address)
        );
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'address_id'        => 'required|string|exists:addresses,id',
            'country'           => 'nullable|string',
            'city'              => 'nullable|string',
            'Neighborhood'      => 'nullable|string',
            'region'            => 'nullable|string',
            'street_name'       => 'nullable|string',
            'building_number'   => 'nullable|string',
            'notes'             => 'nullable|string',
        ]);

        if($validator->fails())
            return response::faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        if(!$address = $user->Addresses->find($request->get('address_id')))
            return response::faild(trans('auth.address not found'), 404, 'E04');

        return $this->update_address($request, $address);
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'address_id'        => 'required|string|exists:addresses,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        if(!$address = $user->Addresses->find($request->get('address_id')))
            return response::faild(trans('auth.address not found'), 404, 'E04');

        $address->delete();
        
        return response::success(trans('all.success'), 200);
    }

    public function insert_address($request, $user){
        $new_address = Address::create([
            'addressable_id'        => $user->id,
            'addressable_type'      => 'App\Models\User',
            'country'               => $request->get('country'),
            'city'                  => $request->get('city'),
            'Neighborhood'          => $request->get('Neighborhood'),
            'region'                => $request->get('region'),
            'street_name'           => $request->get('street_name'),
            'building_number'       => $request->get('building_number'),
            'notes'                 => $request->get('notes'),
        ]);

        return $new_address;
    }

    public function update_address($request, $address){
        $input = $request->only(
            'country', 'city', 'Neighborhood', 'region', 'street_name' ,'building_number','notes'
        );

        if($address->update($input))
            return response::success(trans('all.update address success'), 200, 'address', new addressResource($address));

        return response::faild(trans('all.update address faild'), 200);
    }
}
