<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Http\Resources\orderDetailsResource;
use App\Http\Resources\productResource;
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

    public function product_add(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string',
            'describe'          => 'required|string',
            'price'             => 'required|integer',
            'sub_categoriesId'  => 'required|exists:sub_categories,id',
            'gender'            =>['required', Rule::in(0,1,2)], //0->male, 1-> famale, 2->all
            'quantity'          => 'required|integer',
            'discound'          => 'required|integer',
            'images'            => 'required|array',
            'images.*'          => 'required|mimes:jpeg,jpg,png,gif',
            'sizes'             => 'nullable|array',
            'sizes.*'           => 'required|string',
            'colors'            => 'nullable|array',
            'colors.*'          => 'required|string',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get vender
        if (! $vender = auth('vender')->user()) {
            return response::falid(trans('vender.vendor not found'), 404, 'E04');
        }

        //check if subcategory is parent subcategory parent = 0
        $sub_category = Sub_category::where('parent', $request->get('sub_categoriesId'))->first();

        if($sub_category == null){
            return $this->falid(trans('vender.this sub category not valide'), 400);
        }

        $product = Product::create([
                'name'              => $request->get('name'),
                'describe'          => $request->get('describe'),
                'price'             => $request->get('price'),
                'sub_categoriesId'  => $request->get('sub_categoriesId'),
                'gender'            => $request->get('gender'),
                'quantity'          => $request->get('quantity'),
                'discound'          => $request->get('discound'),
                'vender_id'         => $vender->id,
                'sizes'             => $request->get('sizes'),
                'colors'            => $request->get('colors'),
        ]);

        //uploade images
        $images = [];
        foreach($request->file('images') as $image){
            $image_name = $this->upload_image($image,'uploads/products', 300, 300);
            //get array of images to uploude
            $images[] = [
                'imageable_id'      => $product->id,
                'imageable_type'    => 'App\Models\Product',
                'image'             => $image_name,
            ];
        }
        Image::insert($images);

        return $this->success(trans('vender.add product success'), 200);
    }

    public function product_delete(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'product_id'              => 'required|exists:products,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get vender
        if (! $vender = auth('vender')->user()) {
            return response::falid(trans('vender.vendor not found'), 404, 'E04');
        }

        //select product 
        $product = Product::where('vender_id', $vender->id)->where('id', $request->get('product_id'))->first();

        if($product == null){
            return $this->falid(trans('vender.this product not found'), 404, 'E04');
        }

        //delete product
        $product->status = -1;

        if($product->save()){
            return $this->success(trans('vender.delete product success'), 200);
        }

        return $this->falid(trans('vender.delete product faild'), 400);
    }

    public function product_edit(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'product_id'        => 'required|exists:products,id',
            'name'              => 'nullable|string',
            'describe'          => 'nullable|string',
            'price'             => 'nullable|integer',
            'gender'            =>['nullable', Rule::in(0,1,2)], //0->male, 1-> famale, 2->all
            'discound'          => 'nullable|integer',
            'images'            => 'nullable|array',
            'images.*'          => 'required|mimes:jpeg,jpg,png,gif',
            'sizes'             => 'nullable|array',
            'sizes.*'           => 'required|string',
            'colors'            => 'nullable|array',
            'colors.*'          => 'required|string',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get vender
        if (! $vender = auth('vender')->user()) {
            return response::falid(trans('vender.vendor not found'), 404, 'E04');
        }

        //sellect product 
        $product = Product::where('vender_id', $vender->id)->where('id', $request->get('product_id'))->first();

        if($product == null){
            return $this->falid(trans('vender.this product not found'), 404, 'E04');
        }

        //check if subcategory is parent subcategory parent = 0
        if($request->get('sub_categoriesId') != null){
            $sub_category = Sub_category::where('parent', $request->get('sub_categoriesId'))->first();

            if($sub_category == null){
                return $this->falid(trans('vender.this sub category not valide'), 400);
            }
        }

        //update
        $input = $request->only(
            'name', 'describe', 'price','gender', 'discound', 'sizes', 'colors'
        );
        
        $product->update($input);

        //if vender want to change images

        // update images
        if($request->file('images') != null){
            //delete old images
            foreach($product->image as $oldImage){
                if(file_exists(base_path('public/uploads/products/') . $oldImage->image)){
                    unlink(base_path('public/uploads/products/') . $oldImage->image);
                }
                // delete images from images table
                $oldImage->delete();
            }

            //uploade images
            $images = [];
            foreach($request->file('images') as $image){
                $image_name = $this->upload_image($image,'uploads/products', 300, 300);
                
                //get array of images to uploude
                $images[] = [
                    'imageable_id'      => $product->id,
                    'imageable_type'    => 'App\Models\Product',
                    'image'             => $image_name,
                ];
            }
            Image::insert($images);
        }

        return $this->success(trans('vender.edit product success'), 200);
    }
}
