<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Mail\contact_us;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class all extends Controller
{
    use response;
    public function contact_us(Request $request){
        //get guard
        $guard = $request->route()->getName();

        //validation
        $validator = Validator::make($request->all(), [
            'email'         => 'required|string',
            'phone'         => 'required|string',
            'title'         => 'required|string',
            'body'          => 'required|string',
        ]);

        if($validator->fails()){
            return $this::falid($validator->errors(), 403, 'E03');
        }

        $data = [
            'type'  => $guard,
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'title' => $request->get('title'),
            'body'  => $request->get('body'),
        ];

        Mail::to('ahmedmaher1792001@gmail.com')->send(new contact_us($data));

        return $this::success(trans('all.send email success'), 200);
    }
}
