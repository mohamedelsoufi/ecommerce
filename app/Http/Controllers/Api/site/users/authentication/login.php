<?php

namespace App\Http\Controllers\Api\site\users\authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\userResource;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth as FacadesAuth;


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
        if (! $user = auth('user')->user()) 
            return $this->faild(trans('auth.user not found'), 404, 'E04');

        return $this->user_response($user, $check_data->token);
    }

    public function logout(){
        FacadesAuth::guard('user')->logout();

        return response::success('logout success', 200);
    }

    public function check_data_and_return_Token($request){
        $credentials = ['email' => $request->email, 'password' => $request->password];

        try {
            if (! $token = auth('user')->attempt($credentials)) {
                return $this::faild(trans('auth.passwored or email is wrong'), 400, 'E04');
            }
        } catch (JWTException $e) {
            return $this->faild(trans('auth.some thing is wrong'), 400);
        }

        return $this->success(trans('auth.success'), 200, 'token', $token);
    }

    public function user_response($user, $token){
        //check if user blocked
        if($user['status'] == 0){
            return $this->faild(trans('auth.you are blocked'), 403, 'E02');
        }

        //check if user not active
        if($user['verified'] == null){
            (new verification)->sendCode($user);
            return response()->json([
                'successful'=> false,
                'message'   => trans('auth.You must verify your email'),
                'user'      => new userResource($user),
                'token'     => $token,
            ], 403);
        }
        
        return response()->json([
            'successful'=> true,
            'message'   => trans('auth.success'),
            'user'      => new userResource($user),
            'token'     => $token,
        ], 200);
    }
}
