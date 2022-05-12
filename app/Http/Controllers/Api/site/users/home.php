<?php

namespace App\Http\Controllers\Api\site\users;

use App\Http\Controllers\Controller;
use App\Http\Resources\main_catResource;
use App\Http\Resources\productResource;
use App\Models\Main_category;
use App\Models\Product;
use App\Traits\response;
use Illuminate\Http\Request;

class home extends Controller
{
    use response;
    public function index(){
        $main_cate      = Main_category::active()->limit(6)->get();

        $best_seller    = Product::active()
                                    ->orderBy('number_of_sell', 'desc')
                                    ->limit(6)
                                    ->get();

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'main_categories'   => main_catResource::collection($main_cate),
            'best_seller'       => productResource::collection($best_seller),
        ], 200)->getData();
    }
}
