<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Vendor;
use Illuminate\Http\Request;

class vendors extends Controller
{
    public function vendorShow(){
        $vensers = Vendor::paginate();
        return view('admin.vendors.vendorsShow')->with('vensers', $vensers);
    }

    public function block($id){
        $vendor = Vendor::find($id);

        if($vendor == null)
            return redirect()->back()->with('error', 'this vendor not found');

        if($vendor->status == 1){
            $vendor->status = 0;
        } else {
            $vendor->status = 1;
        }

        if($vendor->save())
            return redirect()->back()->with('success', 'success');
        
        return redirect()->back()->with('error', 'faild');
    }
}
