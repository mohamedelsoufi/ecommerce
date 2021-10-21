<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authentication extends Controller
{
    public function loginView(){
        return view('admin.login');
    }

    public function login(Request $request){
        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect('admin');
        }

        return redirect()->back()->with('error', 'email or password is wrong');
    }

    public function logout(){
        Auth::guard('admin')->logout();

        return redirect('admin/login');
    }
}
