<?php

namespace App\Http\Controllers\Api\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\productResource;
use App\Models\Product;
use App\Models\Sub_category;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class products extends Controller
{
    use response;
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        $product = Product::active()->find($request->get('product_id'));

        if($product == null)
            return $this->faild(trans('guest.this product found'), 404, 'E04');

        return response::success(
            trans('auth.success'),
            200,
            'product',
            new productResource($product),
        );
    }

    public function product_by_sub_category(Request $request){
        $validator = Validator::make($request->all(), [
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        $sub_category = Sub_category::active()
                                        ->find($request->get('sub_category_id'));
        
        if($sub_category == null)
            return $this->faild(trans('guest.this category not found'), 404, 'E04');

        return response::success(
            trans('auth.success'),
            200,
            'products',
            productResource::collection($sub_category->Products()->paginate())->response()->getData(true),
        );
    }
}
