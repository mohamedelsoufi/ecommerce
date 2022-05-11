<?php

namespace App\Http\Controllers\Api\site\users;

use App\Http\Controllers\Controller;
use App\Http\Resources\cartResource;
use App\Models\Cart;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class carts extends Controller
{
    use response;

    public function index(){
        if(!$user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        return $this::success(
                        trans('auth.success'),
                        200,
                        'carts',
                        cartResource::collection($user->Carts)
                    );
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer',
            'color'      => 'nullable|string',
            'size'       => 'nullable|string',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if(!$user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        $cart = Cart::where('user_id', $user->id)
                        ->where('product_id', $request->get('product_id'))
                        ->first();

        if($cart != null)
            return $this::faild(trans('user.this product already add to cart'), 400);

        return $this->insert_cart($request, $user->id);
    }

    public function update(Request $request){
        $validator = validator::make($request->all(), [
            'cart_id'       => 'required|exists:carts,id',
            'quantity'      => 'required|integer',
            'color'         => 'nullable|string',
            'size'          => 'nullable|string',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if(!$user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        $cart = $user->Carts->find($request->get('cart_id'));

        if($cart == null)
            return $this::faild(trans('user.this cart item don\'t found'), 404,'E04');

        return $this->update_cart($cart, $request);
    }

    public function delete(Request $request){
        $validator = validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if(!$user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        $cart = $user->Carts->find($request->get('cart_id'));

        if(!$cart)
            return $this::faild(trans('user.this cart item don\'t found'), 404,'E04');

        return $this->delete_cart($cart);
    }

    public function empty(){
        if(!$user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        if($this->cart_empty($user->id))
            return $this::success(trans('user.cart empty success'), 200);

        return $this::faild(trans('user.some thing is wrong'),400);
    }

    public function insert_cart($request, $user_id){
        Cart::create([
            'product_id' => $request->get('product_id'),
            'user_id'    => $user_id,
            'quantity'   => $request->get('quantity'),
            'color'      => $request->color,
            'size'       => $request->size,
        ]);

        return $this::success(trans('user.add to cart success'), 200);
    }

    public function update_cart($cart, $request){
        $cart->quantity = $request->get('quantity');
        $cart->color    = $request->get('color');
        $cart->size     = $request->get('size');

        if($cart->save())
            return $this::success(trans('user.update success'), 200);

        return $this::faild(trans('user.some thing is wrong'),400);
    }

    public function delete_cart($cart){
        if($cart->delete())
            return $this::success(trans('user.delete success'), 200);

        return $this::faild(trans('user.some thing is wrong'),400);
    }

    public function cart_empty($user_id){
        Cart::where('user_id', $user_id)->delete();

        return true;
    }
}
