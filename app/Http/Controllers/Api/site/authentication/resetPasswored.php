<?php

namespace App\Http\Controllers\Api\site\authentication;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Traits\response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
// use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class resetPasswored extends Controller
{
    use response;
    ////////sent email /////////////

    public function sendEmail(Request $request){  // this is most important function to send mail and inside of that there are another function        
        $guard = $request->route()->getName();

        if (!$this->validateEmail($request->email, $guard)) {  // this is validate to fail send mail or true
            return $this::falid(trans('auth.Email does\'t found on our database'), 404, 'E04');
        }
        
        // code is important in send mail 
        $code = $this->createCode($request->email, $guard);

        Mail::to($request->email)->send(new ResetPasswordMail($code, $request->email));

        return $this::success(trans('auth.Reset Email is send successfully, please check your inbox.'), 200);
    }

    public function createCode($email, $guard){  // this is a function to get your request email that there are or not to send mail
        $table = $guard . '_password_resets';

        $oldCode = DB::table($table)->where('email', $email)->first();

        if ($oldCode) {
            return $oldCode->code;
        }

        $code = rand(1000000, 9999999);
        $this->saveCode($code, $email, $guard);
        return $code;
    }

    public function saveCode($code, $email, $guard){  // this function save new password
        $table = $guard . '_password_resets';

        DB::table($table)->insert([
            'email' => $email,
            'code' => $code,
            'created_at' => Carbon::now()
        ]);
    }

    public function validateEmail($email, $guard){  //this is a function to get your email from database
        return !!DB::table($guard . 's')->where('email', $email)->first();
    }
    ///////////////check if code is valid ////////////

    public function checkCode(Request $request){
        $guard = $request->route()->getName();
        $request->guard = $guard;

        if($this->updatePasswordRow($request)->count() > 0){
            return $this::success('success', 200);
        } else {
            return $this::falid(trans('auth.Either your email or code is wrong.'), 403, 'E04');
        }
    }

    //////////////////////// change password ////////////

    public function passwordResetProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'email'             => 'required',
            'code'              => 'required',
            'password'          => 'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
        ]);

        if($validator->fails()){
            return $this::falid($validator->errors(), 401, 'E03');
        }

        $guard = $request->route()->getName();
        $request->guard = $guard;

        return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this::falid(trans('auth.Either your email or code is wrong.'), 403, 'E04');
    }
  
    // Verify if code is valid
    private function updatePasswordRow($request){
        $table = $request->guard . '_password_resets';

        return DB::table($table)->where([
            'email' => $request->email,
            'code' => $request->code
        ]);
    }

    // Reset password
    private function resetPassword($request) {
        $table = $request->guard . 's';
        // update password
        DB::table($table)
        ->where('email', $request->email)
        ->update(['password' => bcrypt($request->password)]);

        // remove verification data from db
        $this->updatePasswordRow($request)->delete();

        // reset password response
        return response::success(trans('auth.Password has been updated.'), 200);
    } 
}
