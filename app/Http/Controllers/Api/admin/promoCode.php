<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\promoCode\add;
use App\Models\Promo_code;
use Illuminate\Http\Request;

class promoCode extends Controller
{
    public function promoCodesShow(){
        $promo_codes = Promo_code::paginate();
        return view('admin.promoCodes.promoCodesShow')->with('promo_codes',$promo_codes);
    }

    public function addView(){
        return view('admin.promoCodes.promoCodesAdd');
    }

    public function add(add $request){
        Promo_code::create([
            'code'          => $request->code,
            'discound'      => $request->discound,
            'expire_date'   => $request->expire_date,
        ]);

        return redirect('admin/promoCodes')->with('success', 'add promo code success');
    }

    public function expiry($id){
        //sellect Promo_code
        $promo_code = Promo_code::find($id);

        if($promo_code == null){
            return redirect()->back()->with('error', 'faild');
        }

        if($promo_code->update(['expire_date'=> '2001-01-01 01:00:00'])){
            return redirect()->back()->with('success', 'success');
        }
        return redirect()->back()->with('error', 'faild');
    }
}
