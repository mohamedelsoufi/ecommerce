<?php

namespace App\Http\Controllers\Api\site\vendors\authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\vendorResource;
use App\Traits\response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class verification extends Controller
{
    use response;
    public function send_code_to_vendor_from_token(){
        if (! $vendor = auth('vendor')->user()) {
            return response::faild(trans('auth.vendor not found'), 404, 'E04');
        }

        return $this->sendCode($vendor);
    }

    public function sendCode($vendor){
        if (!$this->validateEmail($vendor->email)) {
            return response::faild(trans('auth.email not found'), 404, 'E04');
        }
        
        $code = $this->createCode($vendor->email);
        // Mail::to($request->email)->send(new MailVerification($code, $request->email));

        return response::success(trans('auth.send verify code success, please check your email.'), 200);
    }

    public function createCode($email){
        $oldCode = DB::table('vendors_verification')->where('email', $email)->first();

        //if vendor already has code
        if ($oldCode)
            return $oldCode->code;

        // $code = rand(1000,9999);
        $code = "1234";

        $this->saveCode($code, $email);
        return $code;
    }

    public function saveCode($code, $email){
        DB::table('vendors_verification')->insert([
            'email'         => $email,
            'code'          => $code,
            'created_at'    => Carbon::now()
        ]);
    }

    public function validateEmail($email){
        return !!DB::table('vendors')->where('email', $email)->first();
    }
    //////////////////////// verification ////////////

    public function verificationProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'code'              => 'required',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        if($this->verificationRow($request)->count() > 0){
            return $this->verification($request);
        } else {
            return response::faild(trans('auth.your code is wrong.'), 404, 'E04');
        }
    }
  
    private function verificationRow($request){
        if (! $vendor = auth('vendor')->user()) {
            return response::faild(trans('auth.vendor not found'), 404, 'E04');
        }
        return DB::table('vendors_verification')->where([
            'email'  => $vendor->email,
            'code'   => $request->code
        ]);
    }

    private function verification($request) {
        if (! $vendor = auth('vendor')->user()) {
            return response::faild(trans('auth.vendor not found'), 404, 'E04');
        }
        // update vendors
        DB::table('vendors')
        ->where('email', $vendor->email)
        ->update(['verified' => 1]);

        $this->verificationRow($request)->delete();

        //get token
        try {
            if (! $token = JWTAuth::fromUser($vendor)) { //login
                return response::faild(trans('auth.passwored or email is wrong'), 404, 'E04');
            }
        } catch (JWTException $e) {
            return response::faild(trans('auth.login faild'), 400, 'E00');
        }

        return response()->json([
            'successful'=> true,
            'message'   => 'success',
            'vendor'    => new vendorResource($vendor),
            'token'     => $token,
        ], 200);
    } 
}