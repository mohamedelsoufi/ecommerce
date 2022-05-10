<?php

namespace App\Http\Controllers\Api\site\vendors\authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\vendorResource;
use App\Models\Vendor;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class registration extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'fullName'         => 'required|string',
            'email'            => 'required|string|email|max:255|unique:vendors',
            'password'         => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
            'phone'            => 'required|min:6',
            'gender'           => ['required',Rule::in(0,1,2)],    //0->male  1->female 2->other
            'birth'            => 'required',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors(), 403, 'E03');
        }

        $vendor = $this->insert_vendor_in_dataBase($request);

        $token = JWTAuth::fromUser($vendor);

        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.register success'),
            'vendor'    => new vendorResource($vendor),
            'token'     => $token,
        ], 200);
    }

    public function insert_vendor_in_dataBase($request){
        $vendors = Vendor::create([
            'fullName'          => $request->get('fullName'),
            'email'             => $request->get('email'),
            'password'          => Hash::make($request->get('password')),
            'phone'             => $request->get('phone'),
            'gender'            => $request->get('gender'),
            'birth'             => $request->get('birth'),
        ]);

        return $vendors;
    }
}
