<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Http\Resources\main_catResource;
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
    public function getCategory(){
        return main_catResource::collection(Main_category::where('locale', '=', Config::get('app.locale'))->get());
    }

    public function main_cate_details(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'mainCategory_id' => 'required|exists:main_categories,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        $main_category = new main_catResource(Main_category::find($request->get('mainCategory_id')));

        return $this->success('success', 200, 'main_category', $main_category);
    }

    public function sub_cate_details(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'subCategory_id' => 'required|exists:sub_categories,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        $sub_category = new sub_catResource(Sub_category::find($request->get('subCategory_id')));

        return $this->success('success', 200, 'sub_category', $sub_category);
    }
}
