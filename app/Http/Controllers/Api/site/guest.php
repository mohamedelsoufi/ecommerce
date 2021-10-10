<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Http\Resources\main_catResource;
use App\Models\Main_category;
use App\Models\Sub_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class guest extends Controller
{
    public function getCategory(){
        return main_catResource::collection(Main_category::where('locale', '=', Config::get('app.locale'))->get());
    }
}
