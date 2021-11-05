<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Http\Resources\orderDetailsResource;
use App\Http\Resources\productResource;
use App\Http\Resources\productsDetailsResource;
use App\Models\Orderdetail;
use App\Models\Product;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class vender extends Controller
{
    use response;

    public function home(){
        //get user
        if (! $vender = auth('vender')->user()) {
            return response::falid(trans('vender.vendor not found'), 404, 'E04');
        }

        //products count
        $products_count = Product::notDelete()->where('vender_id', $vender->id)->count();

        //totalMony (that finished or returned)
        $orderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2)->orWhere('status', 3);
        })->whereHas('Product', function($q) use($vender){
            $q->notDelete()->where('vender_id', $vender->id);
        })->get();

        $totalMony = $orderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //finishedMony for vender that finished
        $finishedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2);
        })->whereHas('Product', function($q) use($vender){
            $q->notDelete()->where('vender_id', $vender->id);
        })->get();

        $finishedMony = $finishedOrderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //returnedOrderdetails for vender that returned
        $returnedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 3);
        })->whereHas('Product', function($q) use($vender){
            $q->notDelete()->where('vender_id', $vender->id);
        })->get();
        
        $returnedMony = $returnedOrderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //data
        $data = [
            'products_count'            => $products_count,
            'total_mony'                => $totalMony,
            'returned_mony_percentage'  => $this->getPercentage($returnedMony, ($finishedMony + $returnedMony)) . '%',
            'earned_mony_percentage'    => $this->getPercentage($finishedMony, ($finishedMony + $returnedMony)) . '%',
        ];

        return $this->success('success', 200, 'data', $data);
    }

    public function products(){
        //get user
        if (! $vender = auth('vender')->user()) {
            return response::falid(trans('vender.vendor not found'), 404, 'E04');
        }

        //products
        $products = Product::notDelete()->where('vender_id', $vender->id)->get();

        //number_of_sell (that finished or returned)
        $orderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2)->orWhere('status', 3);
        })->whereHas('Product', function($q) use($vender){
            $q->notDelete()->where('vender_id', $vender->id);
        })->get();

        $number_of_sell = $orderdetails->sum(function ($product) {
            return $product['quantity'];
        });

        //number_of_sell for vender that returned
        $returnedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 3);
        })->whereHas('Product', function($q) use($vender){
            $q->notDelete()->where('vender_id', $vender->id);
        })->get();

        $number_of_returned_sell= $returnedOrderdetails->sum(function ($product) {
            return $product['quantity'];
        });

        //data
        $data = [
            'products_count'            => $products->count(),
            'remaining_quantity'        => $products->sum('quantity'),
            'number_of_sell'            => $number_of_sell,
            'number_of_returned_sell'   => $number_of_returned_sell,
        ];

        return $this->success('success', 200, 'data', $data);
    }

    public function products_informations(){
        //get user
        if (! $vender = auth('vender')->user()) {
            return response::falid(trans('vender.vendor not found'), 404, 'E04');
        }

        //products count
        $products_count = Product::notDelete()->where('vender_id', $vender->id)->count();

        //products
        $products       = Product::notDelete()->where('vender_id', $vender->id)->get();

        //totalMony (that finished or returned)
        $orderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2)->orWhere('status', 3);
        })->whereHas('Product', function($q) use($vender){
            $q->notDelete()->where('vender_id',  $vender->id);
        })->get();

        $totalMony = $orderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //data
        $data = [
            'products_count'            => $products_count,
            'totalMony'                 => $totalMony,
            'products'                  => productsDetailsResource::collection($products),
        ];

        return $this->success('success', 200, 'data', $data);
    }

    public function product_order(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $vender = auth('vender')->user()) {
            return response::falid(trans('vender.vendor not found'), 404, 'E04');
        }

        //get products orders
        $Orderdetail = Orderdetail::whereHas('Product', function($q) use($vender, $request){
            $q->notDelete()->where('id', $request->product_id)->where('vender_id', $vender->id);
        })->get();

        return $this->success('success', 200, 'orders', orderDetailsResource::collection($Orderdetail));
    }

    public function money(){
        //get user
        if (! $vender = auth('vender')->user()) {
            return response::falid(trans('vender.vendor not found'), 404, 'E04');
        }

        //totalMony (that finished or returned)
        $orderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2)->orWhere('status', 3);
        })->whereHas('Product', function($q) use($vender){
            $q->notDelete()->where('vender_id', $vender->id);
        })->get();

        $totalMony = $orderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //returnedOrderdetails for vender that returned
        $returnedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 3);
        })->whereHas('Product', function($q) use($vender){
            $q->notDelete()->where('vender_id', $vender->id);
        })->get();
        
        $returnedMony = $returnedOrderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //data
        $data = [
            'total_mony'                => $totalMony,
            'net_prodit'                => $totalMony - $returnedMony,
            'returned_mony_percentage'  => $returnedMony,
            'withdrawal'                => 0,
        ];

        return $this->success('success', 200, 'data', $data);
    }
}
