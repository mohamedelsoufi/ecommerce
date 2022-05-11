<?php

namespace App\Http\Controllers\Api\site\users;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ratings extends Controller
{
    use response;

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating'     => ['required', Rule::in(1,2,3,4,5)],
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        return $this->add_rating_row($user->id, $request->get('product_id'), $request->get('rating'));
    }

    public function add_rating_row($user_id, $product_id, $rating){
        $rating_row = Rating::where('user_id', $user_id)
                            ->where('product_id', $product_id)
                            ->first();

        if(!$rating_row){
            $this->insert_rating($user_id, $product_id, $rating);
        } else {
            $this->update_rating($rating_row, $rating);
        }

        return $this->success(trans('user.rating success'), 200);

    }

    public function insert_rating($user_id, $product_id, $rating){
        Rating::create([
            'product_id' => $product_id, 
            'user_id'    => $user_id,
            'rating'     => $rating,
        ]);
    }

    public function update_rating($rating_row, $rating){
        $rating_row->rating = $rating;
        $rating_row->save();
    }
}
