<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Http\Resources\commentResource;
use App\Http\Resources\main_catResource;
use App\Http\Resources\productResource;
use App\Http\Resources\sub_catResource;
use App\Models\Main_category;
use App\Models\Product;
use App\Models\Sub_category;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class guest extends Controller
{
    use response;

    public function getCategories(){

        $main_cate = Main_category::where('locale', '=', Config::get('app.locale'))->active()->get();
        return $this->success(trans('auth.success'), 200, 'main_categories', main_catResource::collection($main_cate));
    }

    public function main_cate_details(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'mainCategory_id' => 'required|exists:main_categories,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //check if main category is active
        $main_category = Main_category::active()->where('parent', '!=', 0)->where('id',$request->get('mainCategory_id'))->first();
        if($main_category == null){
            return $this->falid(trans('guest.this category not found'), 404, 'E04');
        }

        //get all product from this category (order number of sell)
        $products = Product::active()->orderBy('number_of_sell', 'desc')
                            ->whereHas('Sub_category', function($q) use($main_category){
                                $q->whereHas('Main_categories', function($query) use($main_category){
                                    $query->where('id', $main_category->parent);
                                });
                            })->get();
        
        //get some products this category (order by discount)
        $most_discount  = Product::active()->orderBy('discound', 'desc')
                                ->whereHas('Sub_category', function($q) use($main_category){
                                    $q->whereHas('Main_categories', function($query) use($main_category){
                                        $query->where('id', $main_category->parent);
                                    });
                                })->limit(6)->get();
        
        $data = [
            'products'      => productResource::collection($products),
            'most_discount' => productResource::collection($most_discount),
        ];

        return $this->success(trans('auth.success'), 200, 'data', $data);
        
    }

    public function sub_cate_details(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'subCategory_id' => 'required|exists:sub_categories,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        $sub_category = Sub_category::where('status', 1)->whereHas('Main_categories', function($q){
            $q->where('status', 1);
        })->where('id', $request->get('subCategory_id'))->first();

        if($sub_category != null){
            return $this->success(trans('auth.success'), 200, 'sub_category', new sub_catResource($sub_category));
        } else {
            return $this->falid(trans('guest.this category not found'), 404, 'E04');
        }
    }

    public function product_details(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'product_id'   => 'required:exists:products,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        $product = Product::active()->where('id',$request->get('product_id'))->first();

        $recommended = Product::active()->where('sub_categoriesId', $product->sub_categoriesId)->limit(6)->get();

        $data = [
            'product_details' => new productResource($product),
            'recommended'     => $recommended,
            'comments'        => commentResource::collection($product->Comments),
        ];

        if($product != null){
            return $this->success(trans('auth.success'), 200, 'data', $data);
        } else {
            return $this->falid(trans('guest.this product not found'), 404, 'E04');
        }
    }
}
