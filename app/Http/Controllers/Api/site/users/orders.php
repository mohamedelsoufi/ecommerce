<?php

namespace App\Http\Controllers\Api\site\users;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Service\myfatoorah;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\site\order as SiteOrder;
use App\Http\Resources\orderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Orderdetail;
use Illuminate\Support\Facades\DB;

class orders extends Controller
{
    use response;
    public function __construct(myfatoorah $myfatoorah){
        $this->myfatoorah = $myfatoorah;
    }

    public function index(Request $request){
        $validator = validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        $orders = Order::where('user_id', $user->id)
                            ->paginate(5);
        
        return $this->success('success',
                            200,
                            'orders',
                            OrderResource::collection($orders)->response()->getData(true),
                        );
    }

    public function delete(Request $request){
        $validator = validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        $order = Order::where('user_id', $user->id)
                        ->where('status', 0)
                        ->find($request->get('order_id'));

        if($order == null)
            return $this::faild(trans('user.this order don\'t found'), 404, 'E04');

        //update order
        if($order->update(['status'=> -1]))
            return $this::success(trans('user.order cancel success'), 200);

        return $this::faild(trans('user.order cancel faild'), 400);
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'promo_code' => 'nullable|exists:promo_codes,code',
            'address_id' => 'required|exists:addresses,id'
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        $carts = $user->Carts;

        return $this->ordering_process($user->id, $carts, $request->promo_code, $request->address_id);
    }

    public function ordering_process($user_id, $carts, $promo_code, $address_id){
        if($carts->count() == 0)
            return response::faild(trans('user.cart is empty'), 400);

        $promoCodeDetails = (new promo_codes)->get_promocode_discount($promo_code);

        $this->make_order($address_id, $carts, $user_id, $promoCodeDetails['discount'], $promoCodeDetails['id']);

        (new carts)->cart_empty($user_id);

        return $this->success(trans('user.make order success'), 200);
    }

    public function make_order($address_id, $carts, $user_id, $promoCode_discount, $promoCode_id){
        $total = $this->order_total($carts, $promoCode_discount);
        
        $order = $this->insert_main_order(
                                    $user_id,
                                    $address_id,
                                    $total['total'],
                                    $total['final_total'],
                                    $promoCode_id
                                );

        $this->make_order_details($carts, $order->id);

        return $this->success(trans('auth.success'), 200);
    }

    public function make_order_details($carts, $order_id){
        foreach($carts as $cart){
            if($this->check_product_quantity_in_cart($cart->Product, $cart->quantity)){
                $this->insert_order_details($cart, $order_id);
                $this->update_product_info($cart);
            }
        }
    }

    public function order_total($carts, $promoCode_discount){
        $total = 0;
        foreach($carts as $cart){
            if($this->check_product_quantity_in_cart($cart->Product, $cart->quantity)){
                $total_cart_item  = $cart->Product->price * $cart->quantity;
                $total += $total_cart_item - $this->percentage($cart->Product->discound, $total_cart_item);
            }
        }

        $final_total =  $total - $this->percentage($promoCode_discount, $total);

        return[
            'total'       => $total,
            'final_total' => $final_total, //with promocode
        ];
    }

    public function insert_main_order($user_id, $address_id, $total, $final_total, $promoCode_id){
        $order = Order::create([
            'user_id'           => $user_id,
            'address_id'        => $address_id,
            'total'             => $total,
            'final_total'       => $final_total,
            'shipping_cost'     => 10,
            'promoCode_id'      => $promoCode_id,
            'payment_status'    => 0,
        ]);
        return $order;
    }

    public function insert_order_details($cart, $order_id){
        Orderdetail::create([
            'product_id'            => $cart->Product->id,
            'order_id'              => $order_id,
            'quantity'              => $cart->quantity,
            'product_price'         => $cart->Product->price,
            'product_discound'      => $cart->Product->discound,
            'product_total_price'   => $cart->Product->price -  $this->percentage($cart->Product->discound, $cart->Product->price),    
            'color'                 => $cart->color,
            'size'                  => $cart->size,
        ]);
    }

    public function update_product_info($cart){
        $product = $cart->Product;

        $product->quantity -= $cart->quantity;
        $product->number_of_sell += $cart->quantity;
        $product->save();
    }

    public function check_product_quantity_in_cart($product, $oreder_quantity){
        if($product->quantity >= $oreder_quantity)
            return true;

        return false;
    }

}
