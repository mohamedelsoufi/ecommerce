<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\productResource;
use App\Models\Product;
use Illuminate\Http\Request;

class products extends Controller
{
    public function productShow(){
        $products = Product::paginate();
        return view('admin.products.productsShow')->with('products',$products);
    }

    public function active($id){
        //sellect product
        $product = Product::find($id);

        if($product == null){
            return 'this product not found';
        }

        if($product->status == 1){
            //un active product
            $product->status = 0;
        } else {
            //un active product
            $product->status = 1;
        }

        if($product->save()){
            return redirect()->back()->with('success', 'success');
        }
        return redirect()->back()->with('error', 'faild');
    }
}
