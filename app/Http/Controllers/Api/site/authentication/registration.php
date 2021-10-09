<?php

namespace App\Http\Controllers\Api\site\authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vender;
use App\Traits\response;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class registration extends Controller
{
    use response;
    public function userRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'fullName'         => 'required|string',
            'email'            => 'required|string|email|max:255|unique:users',
            'password'         => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
            'phone'            => 'required|min:6',
            'gender'           => ['required',Rule::in(0,1,2)],    //0->male  1->female 2->other
            'birth'            => 'required',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 403, 'E03');
        }

        $users = User::create([
            'fullName'          => $request->get('fullName'),
            'email'             => $request->get('email'),
            'password'          => Hash::make($request->get('password')),
            'phone'             => $request->get('phone'),
            'gender'            => $request->get('gender'),
            'birth'             => $request->get('birth'),
        ]);

        $token = JWTAuth::claims(['type' => 'user'])->fromUser($users);

        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.register success'),
            'users'     => $users,
            'token'     => $token,
        ], 200);
    }

    public function venderRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'fullName'         => 'required|string',
            'email'            => 'required|string|email|max:255|unique:venders',
            'password'         => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
            'phone'            => 'required|min:6',
            'gender'           => ['required',Rule::in(0,1,2)],    //0->male  1->female 2->other
            'birth'            => 'required',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 403, 'E03');
        }

        $vender = Vender::create([
            'fullName'          => $request->get('fullName'),
            'email'             => $request->get('email'),
            'password'          => Hash::make($request->get('password')),
            'phone'             => $request->get('phone'),
            'gender'            => $request->get('gender'),
            'birth'             => $request->get('birth'),
        ]);

        $token = JWTAuth::claims(['type' => 'vender'])->fromUser($vender);

        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.register success'),
            'venders'   => $vender,
            'token'     => $token,
        ], 200);
    }
}
