<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Http\Resources\main_catResource;
use App\Http\Resources\productResource;
use App\Models\Comment;
use App\Models\Love;
use App\Models\Main_category;
use App\Models\Product;
use App\Models\Rating;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class user extends Controller
{
    use response;

    public function home(){
        $main_cate      = Main_category::where('locale', '=', Config::get('app.locale'))->where('status', 1)->limit(6)->get();

        $best_seller    = Product::orderBy('number_of_sell', 'desc')
                                            ->where('quantity', '>', 0)
                                            ->where('status', 1)
                                            ->whereHas('Sub_category', function($q){
                                                    $q->where('status', 1)->whereHas('Main_categories', function($query){
                                                        $query->where('status', 1);
                                                    });
                                            })->limit(6)->get();

        $data = [
            'main_categories' => main_catResource::collection($main_cate),
            'best_seller'     => productResource::collection($best_seller),
        ];

        return $this::success(trans('auth.success'), 200, 'data', $data);
    }

    public function love(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $love = Love::where('product_id', $request->get('product_id'))->where('user_id', $user->id)->first();

        if($love == null){
            Love::create([
                'product_id' => $request->get('product_id'), 
                'user_id'   => $user->id,
            ]);

            return $this->success(trans('user.add love success'), 200);
        } else {
            $love->delete();
            return $this->success(trans('user.remove love success'), 200);
        }

    }

    public function add_comment(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'comment'    => 'required|string'
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        Comment::create([
            'product_id' => $request->get('product_id'), 
            'user_id'    => $user->id,
            'content'    => $request->get('comment'),
        ]);

        return $this->success(trans('user.add comment success'), 200);
    }

    public function delete_comment(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'comment_id' => 'required|exists:comments,id',
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $comment = Comment::where('user_id', $user->id)->where('id', $request->get('comment_id'))->first();

        if($comment != null){
            $comment->delete();
            return $this->success(trans('user.delete comment success'), 200);
        } else {
            return $this->falid(trans('user.this comment nout found'), 404, 'E04');
        }
    }

    public function edit_comment(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'comment_id' => 'required|exists:comments,id',
            'comment'     => 'required|string'
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $comment = Comment::where('user_id', $user->id)->where('id', $request->get('comment_id'))->first();

        if($comment != null){
            $comment->content = $request->get('comment');
            $comment->save();
            return $this->success(trans('user.edit comment success'), 200);
        } else {
            return $this->falid(trans('user.this comment nout found'), 404, 'E04');
        }
    }

    public function rating(Request $request){
        //validation
        $validator = validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating'     => ['required', Rule::in(1,2,3,4,5)],
        ]);

        if($validator->fails()){
            return $this->falid($validator->errors(), 403, 'E03');
        }

        //get user
        if (! $user = auth('user')->user()) {
            return response::falid(trans('user.user not found'), 404, 'E04');
        }

        $rating = Rating::where('user_id', $user->id)->where('product_id', $request->get('product_id'))->first();

        if($rating == null){
            //if user don't rating this product
            Rating::create([
                'product_id' => $request->get('product_id'), 
                'user_id'    => $user->id,
                'rating'     => $request->get('rating'),
            ]);
            return $this->success(trans('user.rating success'), 200);

        } else {
            //if user aready ratind this product
            $rating->rating = $request->get('rating');
            $rating->save();
            return $this->success(trans('user.re-rating success'), 200);
        }
    }

    public function cart_get(){

    }

    public function cart_add(){

    }

    public function cart_edit(){

    }

    public function cart_remove(){

    }

    public function cart_empty(){

    }

}
