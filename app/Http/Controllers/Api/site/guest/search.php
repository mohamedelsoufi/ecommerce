<?php

namespace App\Http\Controllers\Api\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\productResource;
use App\Models\Product;
use App\Traits\response;
use Illuminate\Http\Request;

class search extends Controller
{
    use response;
    public function search(Request $request){
        $products = Product::active()
                            ->where('name', 'like', '%'. $request->text .'%')
                            ->paginate(5);

        return response::success(
                                'success',
                                200,
                                'products',
                                productResource::collection($products)->response()->getData(true),
                            );
    }
}
