<?php

namespace App\Http\Controllers\Api\site\users;

use App\Http\Controllers\Controller;
use App\Http\Resources\productResource;
use App\Models\Love;
use App\Models\Product;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class loves extends Controller
{
    use response;
    public function index(){
        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        $products = Product::active()
                            ->whereHas('Loves', function($query) use($user){
                                $query->where('user_id', $user->id);
                            })
                            ->paginate(5);

        return $this->success('success',
                                200,
                                'products',
                                productResource::collection($products)->response()->getData(true)
                            );
    }

    public function change(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        $love = Love::where('product_id', $request->get('product_id'))
                        ->where('user_id', $user->id)
                        ->first();

        if(!$love)
            return $this->insert_love($user->id, $request->product_id);
        
        return $this->delete_love($love);
    }

    public function insert_love($user_id, $product_id){
        Love::create([
            'product_id' => $product_id, 
            'user_id'    => $user_id,
        ]);
        return $this->success(trans('user.add love success'), 200);
    }

    public function delete_love($love){
        $love->delete();
        return $this->success(trans('user.remove love success'), 200);
    }
}
