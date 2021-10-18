<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Models\Order as ModelsOrder;
use App\Models\Orderdetail;
use App\Models\Product;
use App\Traits\response;
use Illuminate\Http\Request;

class order extends Controller
{
    use response;
    public function create_order($request, $carts, $user, $promoCode_discound, $promoCode_id){
        $total = 0;
        foreach($carts as $cart){
            $total_cart_item  = $cart->Product->price * $cart->quantity;
            //total price with discound
            $total       += $total_cart_item - $this->percentage($cart->Product->discound, $total_cart_item);
        }
        $final_total =  $total - $this->percentage($promoCode_discound, $total);

        //creat main order//
        $order = ModelsOrder::create([
            'user_id'           => $user->id,
            'address_id'        => $request->get('address_id'),
            'total'             => $total,
            'final_total'       => $final_total,
            'promoCode_id'      => $promoCode_id,
            'payment_status'    => 0,
        ]);

        //creat order details//
        foreach($carts as $cart){
            $check_order_quantity = $this->check_order_quantity($cart->product_id, $cart->quantity);
            if($check_order_quantity->successful  == false){
                return $check_order_quantity;
            }
            //get product by relation ship
            $Product = $cart->Product;

            //subtract from quantity
            $Product->quantity -= $cart->quantity;

            //add to number_of_sell
            $Product->number_of_sell += $cart->quantity;

            $Product->save();

            // $cart->Product = ;
            Orderdetail::create([
                'product_id'            => $cart->Product->id,
                'order_id'              => $order->id,
                'quantity'              => $cart->quantity,
                'product_price'         => $cart->Product->price,
                'product_total_price'   => $cart->Product->price -  $this->percentage($cart->Product->discound, $cart->Product->price),    
            ]);
        }
        return $this->success(trans('auth.success'), 200);
    }

    public function check_order_quantity($product_id, $oreder_quantity){
        //seletct product
        $product = Product::find($product_id);

        //check quantity
        if($product->quantity > $oreder_quantity){
            return response()->json([
                'successful' => true,
                'message'    => trans('auth.success'),
            ], 400)->getData();
        } else {
            return response()->json([
                'successful' => false,
                'message'    => trans('user.You can\'t order that quantity of ') . $product->name,
            ], 400)->getData();
        }
    }
}
