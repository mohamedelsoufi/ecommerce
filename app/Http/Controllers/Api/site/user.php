<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Api\site\order as SiteOrder;
use App\Http\Controllers\Controller;
use App\Http\Resources\cartResource;
use App\Http\Resources\main_catResource;
use App\Http\Resources\orderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\productResource;
use App\Models\Cart;
use App\Models\Comment;
use App\Models\Love;
use App\Models\Main_category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promo_code;
use App\Models\Rating;
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
        $main_cate      = Main_category::where('locale', '=', Config::get('app.locale'))->where('status', 1)->limit(6)->get();

        $best_seller    = Product::orderBy('number_of_sell', 'desc')
                                            ->where('quantity', '>', 0)
                                            ->where('status', 1)
                                            ->whereHas('Sub_category', function($q){
                                                    $q->where('status', 1)->whereHas('Main_categories', function($query){
                                                        $query->where('status', 1);
                                                    });
                                            })->limit(6)->get();

        $data = [
            'main_categories' => main_catResource::collection($main_cate),
            'best_seller'     => productResource::collection($best_seller),
        ];

        return $this::success(trans('auth.success'), 200, 'data', $data);
    }

    public function love(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $love = Love::where('product_id', $request->get('product_id'))->where('user_id', $user->id)->first();

        if($love == null){
            Love::create([
                'product_id' => $request->get('product_id'), 
                'user_id'   => $user->id,
            ]);

            return $this->success(trans('user.add love success'), 200);
        } else {
            $love->delete();
            return $this->success(trans('user.remove love success'), 200);
        }

    }

    public function add_comment(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'comment'    => 'required|string'
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        Comment::create([
            'product_id' => $request->get('product_id'), 
            'user_id'    => $user->id,
            'content'    => $request->get('comment'),
        ]);

        return $this->success(trans('user.add comment success'), 200);
    }

    public function delete_comment(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'comment_id' => 'required|exists:comments,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $comment = Comment::where('user_id', $user->id)->where('id', $request->get('comment_id'))->first();

        if($comment != null){
            $comment->delete();
            return $this->success(trans('user.delete comment success'), 200);
        } else {
            return $this->falid(trans('user.this comment nout found'), 404, 'E04');
        }
    }

    public function edit_comment(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'comment_id' => 'required|exists:comments,id',
            'comment'     => 'required|string'
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $comment = Comment::where('user_id', $user->id)->where('id', $request->get('comment_id'))->first();

        if($comment != null){
            $comment->content = $request->get('comment');
            $comment->save();
            return $this->success(trans('user.edit comment success'), 200);
        } else {
            return $this->falid(trans('user.this comment nout found'), 404, 'E04');
        }
    }

    public function rating(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating'     => ['required', Rule::in(1,2,3,4,5)],
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $rating = Rating::where('user_id', $user->id)->where('product_id', $request->get('product_id'))->first();

        if($rating == null){
            //if user don't rating this product
            Rating::create([
                'product_id' => $request->get('product_id'), 
                'user_id'    => $user->id,
                'rating'     => $request->get('rating'),
            ]);
            return $this->success(trans('user.rating success'), 200);

        } else {
            //if user aready ratind this product
            $rating->rating = $request->get('rating');
            $rating->save();
            return $this->success(trans('user.re-rating success'), 200);
        }
    }

    public function cart_get(){
        //get user
        if(!$user = auth('user')->user()){
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $carts = Cart::where('user_id', $user->id)->get();

        return $this::success(trans('auth.success'), 200, 'carts',cartResource::collection($carts));
    }

    public function cart_add(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer'
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if(!$user = auth('user')->user()){
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $cart = Cart::where('user_id', $user->id)->where('product_id', $request->get('product_id'))->first();

        if($cart != null){
            return $this::falid(trans('user.this product already add to cart'), 400);
        }

        Cart::create([
            'product_id' => $request->get('product_id'),
            'user_id'    => $user->id,
            'quantity'   => $request->get('quantity'),
        ]);

        return $this::success(trans('user.add to cart success'), 200);
    }

    public function cart_edit(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'cart_id'       => 'required|exists:carts,id',
            'quantity'      => 'required|integer'
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if(!$user = auth('user')->user()){
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $cart = Cart::where('user_id', $user->id)->where('id', $request->get('cart_id'))->first();

        if($cart == null){
            return $this::falid(trans('user.this cart item don\'t found'), 404,'E04');
        }

        $cart->quantity = $request->get('quantity');

        if($cart->save()){
            return $this::success(trans('user.edit success'), 200);
        }

        return $this::falid(trans('user.some thing is wrong'),400);

    }

    public function cart_remove(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if(!$user = auth('user')->user()){
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $cart = Cart::where('user_id', $user->id)->where('id', $request->get('cart_id'))->first();

        if($cart == null){
            return $this::falid(trans('user.this cart item don\'t found'), 404,'E04');
        }

        if($cart->delete()){
            return $this::success(trans('user.delete success'), 200);
        }

        return $this::falid(trans('user.some thing is wrong'),400);

    }

    public function cart_empty(){
        //get user
        if(!$user = auth('user')->user()){
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        Cart::where('user_id', $user->id)->delete();

        return $this::success(trans('user.cart empty success'), 200);
    }

    public function check_promoCode(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'promo_code' => 'required|exists:promo_codes,code',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //select code
        $promoCode = Promo_code::where('code', $request->get('promo_code'))->first();

        //check if promocode is expired
        if(date('Y-m-d H:i:s') > $promoCode->expire_date){
            return $this::falid(trans('user.this promo code expired'), 400, 'E06');
        }

        return $this::success(trans('auth.success'), 200, 'promoCode', $promoCode);
    }

    public function make_order(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'promo_code' => 'nullable|exists:promo_codes,code',
            'address_id' => 'required|exists:addresses,id'
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user details//
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        try{
            //sellect all carts items//
            $carts = Cart::where('user_id', $user->id)->get();

            if($carts->count() == 0){
                return $this::falid(trans('user.cart is empty'), 400);
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
                return $this->falid($create_order->message, 400);
            }

            //cart empty
            $this->cart_empty($request);

            DB::commit();
        } catch(\Exception $ex){
            return $ex;
            return $this::falid(trans('user.make order faild'), 400);
        }

        return $this->success(trans('user.make order success'), 200);
    }

    public function order_tracking(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'status' => ['required', Rule::in(0,1,2,3)], //0->not active, 1->Preparation and delivery, 2->finshed, 3->all
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user details//
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        if($request->get('status') == 3){
            $orders = Order::where('user_id', $user->id)->get();
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
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $orders = Order::where('user_id', $user->id)->where('id', $request->get('order_id'))->first()->Orderdetail;
        return orderDetailsResource::collection($orders);
    }
}
