<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Api\site\order as SiteOrder;
use App\Http\Controllers\Controller;
use App\Http\Resources\cartResource;
use App\Http\Resources\main_catResource;
use App\Http\Resources\orderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\productResource;
use App\Http\Resources\userResource;
use App\Models\Cart;
use App\Models\Main_category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promo_code;
use App\Service\myfatoorah;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class user extends Controller
{
    use response;

    public function __construct(myfatoorah $myfatoorah,SiteOrder $order,address $address){
        $this->myfatoorah = $myfatoorah;
        $this->order      = $order;
        $this->address    = $address;
    }

    public function home(){
        $main_cate      = Main_category::where('locale', '=', Config::get('app.locale'))->active()->limit(6)->get();

        $best_seller    = Product::active()->orderBy('number_of_sell', 'desc')->limit(6)->get();

        $data = [
            'main_categories' => main_catResource::collection($main_cate),
            'best_seller'     => productResource::collection($best_seller),
        ];

        return $this::success(trans('auth.success'), 200, 'data', $data);
    }

    public function check_promoCode(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'promo_code' => 'required|exists:promo_codes,code',
        ]);

        if($validator->fails()){
            return $this->faild($validator->errors(), 403, 'E03');
        }

        //select code
        $promoCode = Promo_code::where('code', $request->get('promo_code'))->first();

        //check if promocode is expired
        if(date('Y-m-d H:i:s') > $promoCode->expire_date){
            return $this::faild(trans('user.this promo code expired'), 400, 'E06');
        }

        return $this::success(trans('auth.success'), 200, 'promoCode', $promoCode);
    }

    public function order_address(Request $request){
        $address = $this->address->addAddress($request);

        return $address;
    }

    public function make_order(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'promo_code' => 'nullable|exists:promo_codes,code',
            'address_id' => 'required|exists:addresses,id'
        ]);

        if($validator->fails()){
            return $this->faild($validator->errors(), 403, 'E03');
        }

        //get user details//
        if (! $user = auth('user')->user()) {
            return response::faild(trans('user.user not found'), 404, 'E04');
        }

        try{
            //sellect all carts items//
            $carts = Cart::where('user_id', $user->id)->get();

            if($carts->count() == 0){
                return $this::faild(trans('user.cart is empty'), 400);
            }

            //get promo code //
            $promoCode_discound = 0;
            $promoCode_id       = null;

            if($request->get('promo_code') != null){
                $promoCode = $this->check_promoCode($request);

                if($promoCode->successful == true){
                    $promoCode_discound = $promoCode->promoCode->discound;
                    $promoCode_id       = $promoCode->promoCode->id;
                }
            }

            DB::beginTransaction();

            //create order
            $create_order = $this->order->create_order($request, $carts, $user, $promoCode_discound, $promoCode_id);
            if($create_order->successful  == false){
                return $this->faild($create_order->message, 400);
            }

            //cart empty
            $this->cart_empty($request);

            DB::commit();
        } catch(\Exception $ex){
            return $this::faild(trans('user.make order faild'), 400);
        }

        return $this->success(trans('user.make order success'), 200);
    }

    public function cancel_order(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);

        if($validator->fails()){
            return $this->faild($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::faild(trans('user.user not found'), 404, 'E04');
        }

        //sellect order
        $order = Order::where('user_id', $user->id)->where('status', 0)->where('id', $request->get('order_id'))->first();

        //check if it is exist
        if($order == null){
            return $this::faild(trans('user.this order don\'t found'), 404, 'E04');
        }

        //update order
        if($order->update(['status'=> -1])){
            return $this::success(trans('user.order cancel success'), 200);
        }

        return $this::faild(trans('user.order cancel faild'), 400);
    }

    public function order_tracking(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'status' => ['required', Rule::in(0,1,2,3)], //0->not active, 1->Preparation and delivery, 2->finshed, 3->all
        ]);

        if($validator->fails()){
            return $this->faild($validator->errors(), 403, 'E03');
        }

        //get user details//
        if (! $user = auth('user')->user()) {
            return response::faild(trans('user.user not found'), 404, 'E04');
        }

        if($request->get('status') == 3){
            $orders = Order::where('user_id', $user->id)->where('status', '!=', -1)->get();
        } else {
            $orders = Order::where('user_id', $user->id)->where('status', $request->get('status'))->get();
        }

        return $this->success(trans('auth.success'), 200, 'orders', OrderResource::collection($orders));
    }

    public function order_details(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);

        if($validator->fails()){
            return $this->faild($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::faild(trans('user.user not found'), 404, 'E04');
        }

        $orders = Order::where('user_id', $user->id)->where('id', $request->get('order_id'))->first()->Orderdetail;
        return orderDetailsResource::collection($orders);
    }

    public function profil_details(){
        //get user
        if (! $user = auth('user')->user()) {
            return response::faild(trans('user.user not found'), 404, 'E04');
        }

        //products that user add love in it
        $products = Product::active()->whereHas('Loves', function($q) use($user){
            $q->where('user_id', $user->id);
        })->get();

        //orders that finished
        $orders = Order::where('user_id', $user->id)->where('status', 2)->get();

        $data = [
            'user'              => new userResource($user),
            'loves_products'    => productResource::collection($products),
            'orders'            => OrderResource::collection($orders),
        ];

        return $this->success('success', 200, 'data', $data);
    }
}