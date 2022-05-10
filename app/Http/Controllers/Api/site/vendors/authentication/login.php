<?php

namespace App\Http\Controllers\Api\site\vendors\authentication;

use App\Http\Controllers\Controller;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class login extends Controller
{
    use response;
    public function login(Request $request){        
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required',
        ]);

        if($validator->fails()){
            return $this->faild($validator->errors(), 403, 'E03');
        }   
        
        $check_data = $this->check_data_and_return_Token($request);
        if(!$check_data->successful)
            return $check_data;

        //get user date
        if (! $vendor = auth('vender')->user()) 
            return $this->faild(trans('auth.vendor not found'), 404, 'E04');

        return $this->vendor_response($vendor, $check_data->token);
    }

    public function logout(){
        Auth::guard('vender')->logout();

        return response::success('logout success', 200);
    }

    public function check_data_and_return_Token($request){
        $credentials = ['email' => $request->email, 'password' => $request->password];

        try {
            if (! $token = auth('vender')->attempt($credentials)) {
                return $this::faild(trans('auth.passwored or email is wrong'), 400, 'E04');
            }
        } catch (JWTException $e) {
            return $this->faild(trans('auth.some thing is wrong'), 400);
        }

        return $this->success(trans('auth.success'), 200, 'token', $token);
    }

    public function vendor_response($vendor, $token){
        //check if vendor blocked
        if($vendor['status'] == 0){
            return $this->faild(trans('auth.you are blocked'), 403, 'E02');
        }

        //check if vendor not active
        if($vendor['verified'] == null){
            (new verification)->sendCode($vendor);
            return response()->json([
                'successful'=> false,
                'message'   => trans('auth.You must verify your email'),
                'vendor'    => $vendor,
                'token'     => $token,
            ], 403);
        }
        
        return response()->json([
            'successful'=> true,
            'message'   => trans('auth.success'),
            'vendor'    => $vendor,
            'token'     => $token,
        ], 200);
    }
}
