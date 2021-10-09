<?php

namespace App\Http\Controllers\Api\site\authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\login as RequestsLogin;
use App\Models\User;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class auth extends Controller
{
    use response;

    public function login(Request $request){
        $guard = $request->route()->getName();
        
        // //validation
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            // 'token_firebase'    => 'required',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //check password and email and login
        $credentials = ['email' => $request->email, 'password' => $request->password];

        try {
            if (! $token = auth($guard)->claims(['type' => $guard])->attempt($credentials)) {
                return $this::falid(trans('auth.passwored or email is wrong'), 400, 'E04');
            }
        } catch (JWTException $e) {
            return $this->falid(trans('auth.some thing is wrong'), 400);
        }

        //get user data
        if (! $data = auth($guard)->user()) {
            return $this->falid(trans('auth.user not found'), 404, 'E04');
        }

        //check if user blocked
        if($data['status'] == 0){
            return $this->falid(trans('auth.you are blocked'), 403, 'E02');
        }

        //check if user not active
        if($data['email_verified_at'] == null){
            return $this->falid(trans('auth.You must verify your email'), 403, 'E05');
        }
        
        return response()->json([
            'status'  => true,
            'message' => trans('auth.succeess'),
            'data'    => $data,
            'token'   => $token,
        ], 200);
    }

    public function logout(Request $request){
        //get guard
        $guard = $request->route()->getName();

        FacadesAuth::guard($guard)->logout();

        return response::success('logout success', 200);
    }
}
