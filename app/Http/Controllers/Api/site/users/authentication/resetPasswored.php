<?php

namespace App\Http\Controllers\Api\site\users\authentication;

use App\Http\Controllers\Controller;
use App\Traits\response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class resetPasswored extends Controller
{
    use response;

    public function sendCode(Request $request){         
        $validator = Validator::make($request->all(), [
            'email'          => 'required',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403);
        }
        
        if (!$this->validateEmail($request->email)) {
            return response::faild(trans('auth.email not found'), 404, 'E04');
        }
        
        // code is important in send mail 
        $code = $this->createCode($request->email);
        // Mail::to($request->email)->send(new MailVerification($code, $request->email));

        return response::success(trans('auth.send reset password code success, please check your email.'), 200);
    }

    public function createCode($email){  
        $oldCode = DB::table('user_password_resets')->where('email', $email)->first();

        //if user already has code
        if ($oldCode)
            return $oldCode->code;

        // $code = rand(1000,9999);
        $code = "1234";
        $this->saveCode($code, $email);
        return $code;
    }

    public function saveCode($code, $email){  
        DB::table('user_password_resets')->insert([
            'email'      => $email,
            'code'          => $code,
            'created_at'    => Carbon::now()
        ]);
    }

    public function validateEmail($email){
        return !!DB::table('users')->where('email', $email)->first();
    }
    
    ///////////////check if code is valid ////////////
    public function checkCode(Request $request){
        $validator = Validator::make($request->all(), [
            'email'             => 'required',
            'code'              => 'required',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        if($this->updatePasswordRow($request->code, $request->email)->count() > 0){
            $user = User::where('email', $request->email)->first();

            if (! $token = JWTAuth::fromUser($user)) { //login
                return response::faild(trans('auth.passwored or email is wrong'), 404, 'E04');
            }

            return response::success(trans('auth.success'), '200', 'token', $token);
        }

        return response::faild(trans('auth.Either your email or code is wrong.'), 404, 'E04');
    }
    //////////////////////// resetpasswored ////////////

    public function passwordResetProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'code'              => 'required',
            'password'          => 'required|string|min:6',
            'confirm_password'  => 'required|string|same:password',
        ]);

        if($validator->fails())
            return response::faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('auth.user not found'), 404, 'E04');

        if($this->updatePasswordRow($request->code, $user->email)->count() > 0){
            return $this->resetPassword($request, $user);
        } else{
            return response::faild(trans('auth.your code is wrong.'), 400, 'E00');
        } 
    }

    private function updatePasswordRow($code, $email){
        return DB::table('user_password_resets')->where([
            'email'  => $email,
            'code'   => $code
        ]);
    }

    private function resetPassword($request, $user) {
        // update password
        DB::table('users')
        ->where('email', $user->email)
        ->update(['password' => bcrypt($request->password)]);

        $this->updatePasswordRow($request->code, $user->email)->delete();

        if (! $token = JWTAuth::fromUser($user)) {
            return response::faild(trans('auth.passwored or email is wrong'), 404, 'E04');
        }

        return (new login)->user_response($user, $token);
    } 
}