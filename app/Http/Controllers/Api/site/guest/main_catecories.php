<?php

namespace App\Http\Controllers\Api\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\main_catResource;
use App\Models\Main_category;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class main_catecories extends Controller
{
    use response;
    public function index(){
        $main_categories = Main_category::active()
                                    ->get();

        return response::success(
            trans('auth.success'),
            200,
            'main_categories',
            main_catResource::collection($main_categories)
        );
    }

    public function details(Request $request){
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
                        'main_category',
                        new main_catResource($main_category),
                    );
    }
}
