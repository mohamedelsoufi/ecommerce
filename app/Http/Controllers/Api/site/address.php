<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Http\Resources\addressResource;
use App\Models\Address as ModelsAddress;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class address extends Controller
{
    use response;
    public function getAddress(Request $request){
        $validator = Validator::make($request->all(), [
            'address_id'        => 'required|exists:addresses,id',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors(), 403);
        }

        $address = ModelsAddress::find($request->address_id);

        return $this::success(trans('auth.success'), 200, 'address', $address);
    }

    public function addAddress(Request $request){
        // validate registeration request
        $validator = Validator::make($request->all(), [
            'country'           => 'required|string',
            'city'              => 'required|string',
            'Neighborhood'      => 'required|string',
            'region'            => 'required|string',
            'street_name'       => 'required|string',
            'building_number'   => 'required|string',
            'notes'             => 'required|string',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors(), 403, 'E03');
        }

        $address = ModelsAddress::create([
            'country'               => $request->get('country'),
            'city'                  => $request->get('city'),
            'Neighborhood'          => $request->get('Neighborhood'),
            'region'                => $request->get('region'),
            'street_name'           => $request->get('street_name'),
            'building_number'       => $request->get('building_number'),
            'notes'                 => $request->get('notes'),
        ]);

        return $this::success(trans('all.add address success'), 200, 'address', new addressResource($address));
    }

    public function editAddress(Request $request){
        // validate registeration request
        $validator = Validator::make($request->all(), [
            'address_id'        => 'required|exists:addresses,id',
            'country'           => 'nullable|string',
            'city'              => 'nullable|string',
            'Neighborhood'      => 'nullable|string',
            'region'            => 'nullable|string',
            'street_name'       => 'nullable|string',
            'building_number'   => 'nullable|string',
            'notes'             => 'nullable|string',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors(), 403);
        }

        $input = $request->only(
            'country', 'city', 'Neighborhood', 'region', 'street_name' ,'building_number','notes'
        );

        $address = ModelsAddress::find($request->get('address_id'));

        if($address->update($input)){
            return $this::success(trans('all.edit address success'), 200, 'address', new addressResource($address));
        }

        return $this::faild(trans('all.edit address faild'), 200);
    }
}
