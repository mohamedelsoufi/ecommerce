<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Http\Resources\orderDetailsResource;
use App\Http\Resources\productsDetailsResource;
use App\Models\Image;
use App\Models\Orderdetail;
use App\Models\Product;
use App\Models\Sub_category;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class vender extends Controller
{
    use response;

    public function home(){
        //get user
        if (! $vendor = auth('vendor')->user()) {
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');
        }

        //products count
        $products_count = Product::notDelete()->where('vendor_id', $vendor->id)->count();

        //totalMony (that finished or returned)
        $orderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2)->orWhere('status', 3);
        })->whereHas('Product', function($q) use($vendor){
            $q->notDelete()->where('vendor_id', $vendor->id);
        })->get();

        $totalMony = $orderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //finishedMony for vendor that finished
        $finishedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2);
        })->whereHas('Product', function($q) use($vendor){
            $q->notDelete()->where('vendor_id', $vendor->id);
        })->get();

        $finishedMony = $finishedOrderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //returnedOrderdetails for vendor that returned
        $returnedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 3);
        })->whereHas('Product', function($q) use($vendor){
            $q->notDelete()->where('vendor_id', $vendor->id);
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
        if (! $vendor = auth('vendor')->user()) {
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');
        }

        //products
        $products = Product::notDelete()->where('vendor_id', $vendor->id)->get();

        //number_of_sell (that finished or returned)
        $orderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2)->orWhere('status', 3);
        })->whereHas('Product', function($q) use($vendor){
            $q->notDelete()->where('vendor_id', $vendor->id);
        })->get();

        $number_of_sell = $orderdetails->sum(function ($product) {
            return $product['quantity'];
        });

        //number_of_sell for vendor that returned
        $returnedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 3);
        })->whereHas('Product', function($q) use($vendor){
            $q->notDelete()->where('vendor_id', $vendor->id);
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
        if (! $vendor = auth('vendor')->user()) {
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');
        }

        //products count
        $products_count = Product::notDelete()->where('vendor_id', $vendor->id)->count();

        //products
        $products       = Product::notDelete()->where('vendor_id', $vendor->id)->get();

        //totalMony (that finished or returned)
        $orderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2)->orWhere('status', 3);
        })->whereHas('Product', function($q) use($vendor){
            $q->notDelete()->where('vendor_id',  $vendor->id);
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
            return $this->faild($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $vendor = auth('vendor')->user()) {
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');
        }

        //get products orders
        $Orderdetail = Orderdetail::whereHas('Product', function($q) use($vendor, $request){
            $q->notDelete()->where('id', $request->product_id)->where('vendor_id', $vendor->id);
        })->get();

        return $this->success('success', 200, 'orders', orderDetailsResource::collection($Orderdetail));
    }

    public function money(){
        //get user
        if (! $vendor = auth('vendor')->user()) {
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');
        }

        //totalMony (that finished or returned)
        $orderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2)->orWhere('status', 3);
        })->whereHas('Product', function($q) use($vendor){
            $q->notDelete()->where('vendor_id', $vendor->id);
        })->get();

        $totalMony = $orderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //returnedOrderdetails for vendor that returned
        $returnedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 3);
        })->whereHas('Product', function($q) use($vendor){
            $q->notDelete()->where('vendor_id', $vendor->id);
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
