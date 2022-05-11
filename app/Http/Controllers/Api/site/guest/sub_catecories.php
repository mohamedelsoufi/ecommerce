<?php

namespace App\Http\Controllers\Api\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\sub_catResource;
use App\Models\Sub_category;
use App\Traits\response;
use Illuminate\Http\Request;

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
}
