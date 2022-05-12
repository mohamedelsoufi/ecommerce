<?php

namespace App\Http\Controllers\Api\site\users;

use App\Http\Controllers\Controller;
use App\Models\Promo_code;
use App\Traits\response;
use Illuminate\Http\Request;

class promo_codes extends Controller
{
    use response;
    public function get_promocode_discount($promo_code){
        $promoCode_discount = 0;
        $promoCode_id       = null;

        if($promo_code){
            $promoCode = $this->check_promoCode($promo_code);

            if($promoCode){
                $promoCode_id       = $promoCode['id'];
                $promoCode_discount = $promoCode['discount'];
            }
        }

        return [
            'id'          => $promoCode_id,
            'discount'    => $promoCode_discount,
        ];
    }

    public function check_promoCode($promo_code){
        $promoCode = Promo_code::where('code', $promo_code)->first();

        if(date('Y-m-d H:i:s') > $promoCode->expire_date)
            return false;

        return [
            'id'        => $promoCode->id,
            'discount'  => $promoCode->discound,
        ];
    }
}
