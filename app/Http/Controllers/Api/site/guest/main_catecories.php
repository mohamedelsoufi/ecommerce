<?php

namespace App\Http\Controllers\Api\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\main_catResource;
use App\Models\Main_category;
use App\Traits\response;
use Illuminate\Http\Request;

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
}
