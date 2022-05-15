<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class users extends Controller
{
    public function index(){
        $users = User::paginate();
        return view('admin.users.usersShow')->with('users', $users);
    }

    public function destroy($id){
        $user = User::find($id);

        if($user == null)
            return redirect()->back()->with('error', 'this user not found');

        if($user->status == 1){
            $user->status = 0;
        } else {
            $user->status = 1;
        }

        if($user->save()){
            return redirect()->back()->with('success', 'success');
        }
        return redirect()->back()->with('error', 'faild');
    }
}
