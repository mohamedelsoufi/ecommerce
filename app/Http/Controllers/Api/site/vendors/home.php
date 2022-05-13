<?php

namespace App\Http\Controllers\Api\site\vendors;

use App\Http\Controllers\Controller;
use App\Models\Orderdetail;
use App\Models\Product;
use App\Traits\response;
use Illuminate\Http\Request;

class home extends Controller
{
    use response;
    public function index(){
        if (! $vendor = auth('vendor')->user())
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');

        $products_count = Product::notDelete()
                                    ->where('vendor_id', $vendor->id)
                                    ->count();

        $finishedOrder  = Orderdetail::finished($vendor->id)->get();
        $finishedMony = $finishedOrder->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        $returnedOrder  = Orderdetail::returned($vendor->id)->get();
        $returnedMony = $returnedOrder->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'products_count'    => $products_count,
            'finished_mony'      => $finishedMony,
            'returned_mony'     => $returnedMony,
        ], 200)->getData();
    }
}
