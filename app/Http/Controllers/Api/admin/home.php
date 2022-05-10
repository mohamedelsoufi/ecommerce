<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Main_category;
use Illuminate\Http\Request;

class home extends Controller
{
    public function home(){
        return view('admin.home.home');
    }
}
