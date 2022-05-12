<?php

namespace App\Http\Controllers\Api\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\sub_catResource;
use App\Models\Main_category;
use App\Models\Sub_category;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class sub_catecories extends Controller
{
    use response;
    public function index(){
        $sub_categories = Sub_category::active()->get();

        return response::success(
            trans('auth.success'),
            200,
            'sub_categories',
            sub_catResource::collection($sub_categories)
        );
    }

    public function details(Request $request){
        $validator = Validator::make($request->all(), [
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        $sub_category = Sub_category::active()
                                        ->find($request->get('sub_category_id'));
        
        if($sub_category == null)
            return $this->faild(trans('guest.this category not found'), 404, 'E04');
        
        return $this->success(
                        trans('auth.success'),
                        200,
                        'sub_category',
                        new sub_catResource($sub_category),
                    );
    }

    public function sub_categories_by_main_category(Request $request){
        $validator = Validator::make($request->all(), [
            'main_category_id' => 'required|exists:main_categories,id',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        $main_category = Main_category::active()
                                        ->find($request->get('main_category_id'));
        
        if($main_category == null)
            return $this->faild(trans('guest.this category not found'), 404, 'E04');
        
        return $this->success(
                        trans('auth.success'),
                        200,
                        'sub_categories',
                        Sub_catResource::collection($main_category->Sub_category->where('status', 1)),
                    );
    }
}
