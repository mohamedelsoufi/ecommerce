<?php

namespace App\Http\Controllers\Api\site\vendors;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class products extends Controller
{   
    use response;
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string',
            'describe'          => 'required|string',
            'price'             => 'required|integer',
            'sub_categoriesId'  => 'required|exists:sub_categories,id',
            'gender'            =>['required', Rule::in(0,1,2)],
            'quantity'          => 'required|integer',
            'discound'          => 'required|integer',
            'images'            => 'required|array',
            'images.*'          => 'required|mimes:jpeg,jpg,png,gif',
            'sizes'             => 'nullable|array',
            'sizes.*'           => 'required|string',
            'colors'            => 'nullable|array',
            'colors.*'          => 'required|string',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $vendor = auth('vendor')->user())
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');

        return $this->insert_product($request, $vendor);
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id'              => 'required|exists:products,id',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $vendor = auth('vendor')->user())
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');

        $product = Product::where('vendor_id', $vendor->id)->find($request->get('product_id'));

        if($product == null)
            return $this->faild(trans('vendor.this product not found'), 404, 'E04');

        return $this->delete_product($product);
    }

    public function update(Request $request){
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

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $vendor = auth('vendor')->user())
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');

        $product = Product::where('vendor_id', $vendor->id)->find($request->get('product_id'));

        if($product == null)
            return $this->faild(trans('vendor.this product not found'), 404, 'E04');

        return $this->update_product_row($request, $product);
    }

    public function insert_product($request, $vendor){
        $product = Product::create([
            'name'              => $request->get('name'),
            'describe'          => $request->get('describe'),
            'price'             => $request->get('price'),
            'sub_categoriesId'  => $request->get('sub_categoriesId'),
            'gender'            => $request->get('gender'),
            'quantity'          => $request->get('quantity'),
            'discound'          => $request->get('discound'),
            'vendor_id'         => $vendor->id,
            'sizes'             => $request->get('sizes'),
            'colors'            => $request->get('colors'),
        ]);

        $this->insert_images_to_product($request, $product);

        return $this->success(trans('vendor.add product success'), 200);
    }

    public function delete_product($product){
        $product->status = -1;

        if($product->save())
            return $this->success(trans('vendor.delete product success'), 200);

        return $this->faild(trans('vendor.delete product faild'), 400);
    }

    public function update_product_row($request, $product){
        $input = $request->only(
            'name', 'describe', 'price','gender', 'discound', 'sizes', 'colors'
        );
        $product->update($input);

        if($request->file('images') != null){
            $this->delete_images_from_product($product);

            $this->insert_images_to_product($request, $product);
        }

        return $this->success(
            trans('vendor.edit product success'),
            200,
            'product',
            $product,
        );
    }

    public function delete_images_from_product($product){
        foreach($product->Images as $oldImage){
            if(file_exists(base_path('public/uploads/products/') . $oldImage->src)){
                unlink(base_path('public/uploads/products/') . $oldImage->src);
            }
            $oldImage->delete();
        }
    }

    public function insert_images_to_product($request, $product){
        $images = [];
        foreach($request->file('images') as $image){
            $image_name = $this->upload_image($image,'uploads/products', 300, 300);
            //get array of images to uploude
            $images[] = [
                'imageable_id'      => $product->id,
                'imageable_type'    => 'App\Models\Product',
                'src'               => $image_name,
            ];
        }
        Image::insert($images);
    }
}
