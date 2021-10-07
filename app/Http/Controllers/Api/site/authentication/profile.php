<?php

namespace App\Http\Controllers\Api\site\authentication;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\User;
use App\Models\Vender;
use App\Traits\response;
use Illuminate\Http\Request;

class profile extends Controller
{
    use response;

    public function getProfile(Request $request){
        return User::find(1)->address;
        //get guard
        $guard = $request->route()->getName();

        //get user
        if (! $user = auth($guard)->user()) {
            return response::falid('user_not_found', 404, 'E04');
        }

        return response::success('success', 200, 'data', $user);
    }
}
