<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Vender;
use Illuminate\Http\Request;

class venders extends Controller
{
    public function venderShow(){
        $vensers = Vender::paginate();
        return view('admin.venders.vendersShow')->with('vensers', $vensers);
    }

    public function block($id){
        //sellect vender
        $vender = Vender::find($id);

        //if there are no vender with this id
        if($vender == null){
            return 'this vender not found';
        }

        if($vender->status == 1){
            //block vender
            $vender->status = 0;
        } else {
            //un block vender
            $vender->status = 1;
        }

        if($vender->save()){
            return redirect()->back()->with('success', 'success');
        }
        return redirect()->back()->with('error', 'faild');
    }
}
