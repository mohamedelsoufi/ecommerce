<?php

namespace App\Http\Resources;

use App\Models\Orderdetail;
use App\Models\Sub_category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class productsDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //get product images
        if($this->Images->first() != null){
            $images = $this->Image->transform(function ($item, $key) {
                return url('public/uploads/products/' . $item->Image);
            });
        } else {
            $images = array(url('public/uploads/products/default.jpg'));
        }

        //totalMony (that finished or returned)
        $orderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 2)->orWhere('status', 3);
        })->whereHas('Product', function($q){
            $q->notDelete()->where('id', $this->id)->where('vendor_id',  $this->Vendor->id);
        })->get();

        $totalMony = $orderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });


        //get returned products count and mony
        $returnedOrderdetails  = Orderdetail::whereHas('Order' , function($q){
            $q->where('status', 3);
        })->whereHas('Product', function($q){
            $q->notDelete()->where('id', $this->id)->where('vendor_id', $this->Vendor->id);
        })->get();
        
        $returned_count = $returnedOrderdetails->sum(function ($product) {
            return $product['quantity'];
        });
        
        $returned_money = $returnedOrderdetails->sum(function ($product) {
            return $product['product_total_price'] * $product['quantity'];
        });

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'date'              => date("Y-m-d H:i:s", strtotime($this->created_at)),
            'quantity'          => [
                                    'quantity'           => $this->quantity + $this->number_of_sell,
                                    'number_of_sell'     => $this->number_of_sell, 
                                    'returned_count'     => $returned_count,
                                    'remaining_quantity' => $this->quantity,
                                ],
            'money'             => [
                                        'total_money'    => $totalMony,
                                        'returned_money' => $returned_money,
                                        'net_profit'     => $totalMony - $returned_money,
                                    ],
            'images'            => $images,
            'colors'            => $this->colors,
            'sizes'             => $this->sizes,
        ];
    }
}
