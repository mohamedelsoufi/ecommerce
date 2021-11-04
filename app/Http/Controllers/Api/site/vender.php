<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Models\Orderdetail;
use App\Models\Product;
use App\Traits\response;
use Illuminate\Http\Request;

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
            $q->where('vender_id', $vender->id);
        })->get();

        $totalMony = $orderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //finishedMony for vender that finished
        $finishedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2);
        })->whereHas('Product', function($q) use($vender){
            $q->where('vender_id', $vender->id);
        })->get();

        $finishedMony = $finishedOrderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        //returnedOrderdetails for vender that returned
        $returnedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 3);
        })->whereHas('Product', function($q) use($vender){
            $q->where('vender_id', $vender->id);
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
            $q->where('vender_id', $vender->id);
        })->get();

        $number_of_sell = $orderdetails->sum(function ($product) {
            return $product['quantity'];
        });

        //number_of_sell for vender that returned
        $returnedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 3);
        })->whereHas('Product', function($q) use($vender){
            $q->where('vender_id', $vender->id);
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
}
