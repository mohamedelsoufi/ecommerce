<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\productResource;
use App\Models\Product;
use Illuminate\Http\Request;

class products extends Controller
{
    public function index(){
        $products = Product::where('status', '!=', -1)->paginate();
        return view('admin.products.productsShow')->with('products',$products);
    }

    public function destroy($id){
        $product = Product::find($id);

        if($product == null)
            return redirect()->back()->with('error', 'delete product faild');
    
        if($product->update(['status'=> -1]))
            return redirect()->back()->with('success', 'delete product success');
    
        return redirect()->back()->with('error', 'delete product faild');
    }

    public function active($id){
        $product = Product::find($id);

        if($product == null)
            return redirect()->back()->with('error', 'faild');

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
