<?php

namespace App\Http\Controllers\Api\site\vendors;

use App\Http\Controllers\Controller;
use App\Http\Resources\orderDetailsResource;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Traits\response;
use Illuminate\Http\Request;

class orders extends Controller
{
    use response;
    public function index(){
        if (! $vendor = auth('vendor')->user())
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');

        $Orderdetail = Orderdetail::whereHas('Product', function($query) use($vendor){
                                        $query->where('vendor_id', $vendor->id);
                                    })
                                    ->paginate(5);

        return $this->success(
                            trans('vendor.success'),
                            200,
                            'orders',
                            orderDetailsResource::collection($Orderdetail)->response()->getData(true)
                        );
    }
}
