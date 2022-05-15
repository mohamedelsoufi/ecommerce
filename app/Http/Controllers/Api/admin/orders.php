<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class orders extends Controller
{
    public function index(){
        $orders = Order::paginate();
        return view('admin.orders.ordersShow')->with('orders',$orders);
    }

    public function show($id){
        //sellect product
        $order = Order::find($id);

        if($order == null){
            return redirect()->back()->with('error', 'faild');
        }

        return view('admin.orders.ordersDetails')->with([
            'order'         => $order,
            'orders_details'  => $order->Orderdetail,
        ]);
    }

    public function cancel($id){
        //sellect product
        $order = Order::find($id);

        if($order == null){
            return redirect()->back()->with('error', 'cancel order faild');
        }

        if($order->update(['status'=>-1])){
            return redirect()->back()->with('success', 'cancel order success');
        }
        return redirect()->back()->with('error', 'cancel order faild');
    }

    public function active($id){
        //sellect product
        $order = Order::find($id);

        if($order == null){
            return redirect()->back()->with('error', 'active order faild');
        }

        if($order->update(['status'=> 1])){
            return redirect()->back()->with('success', 'active order success');
        }
        return redirect()->back()->with('error', 'active order faild');
    }

    public function finish($id){
        //sellect product
        $order = Order::find($id);

        if($order == null){
            return redirect()->back()->with('error', 'finish order faild');
        }

        if($order->update(['status'=> 2])){
            return redirect()->back()->with('success', 'finish order success');
        }
        return redirect()->back()->with('error', 'finish order faild');
    }
}
