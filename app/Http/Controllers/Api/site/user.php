<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Http\Resources\main_catResource;
use App\Http\Resources\productResource;
use App\Models\Main_category;
use App\Models\Product;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class user extends Controller
{
    use response;
    public function home(){
        $main_cate      = main_catResource::collection(Main_category::where('locale', '=', Config::get('app.locale'))->limit(6)->get());
        $best_seller    = productResource::collection(Product::orderBy('number_of_sell', 'desc')->limit(6)->get());

        $data = [
            'main_categories' => $main_cate,
            'best_seller'     => $best_seller
        ];

        return $this::success('success', 200, 'data', $data);
    }
}
