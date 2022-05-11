<?php

namespace App\Http\Controllers\Api\site\users;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class comments extends Controller
{
    use response;
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'comment'    => 'required|string'
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        return $this->insert_comment($user->id, $request->product_id, $request->comment);
    }

    public function delete(Request $request){
        $validator = validator::make($request->all(), [
            'comment_id' => 'required|exists:comments,id',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        $comment = Comment::where('user_id', $user->id)->find($request->get('comment_id'));

        if(!$comment)
            return $this->faild(trans('user.this comment nout found'), 404, 'E04');

        $comment->delete();
        return $this->success(trans('user.delete comment success'), 200);
    }

    public function update(Request $request){
         $validator = validator::make($request->all(), [
            'comment_id' => 'required|exists:comments,id',
            'comment'     => 'required|string'
        ]);

        if($validator->fails())
            return $this->faild($validator->errors(), 403, 'E03');

        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        $comment = Comment::where('user_id', $user->id)->find($request->get('comment_id'));

        if(!$comment){
            return $this->faild(trans('user.this comment nout found'), 404, 'E04');
        }
        
        $comment->content = $request->get('comment');
        $comment->save();
        return $this->success(trans('user.edit comment success'), 200);
    }

    public function insert_comment($user_id, $product_id, $comment){
        Comment::create([
            'product_id' => $product_id, 
            'user_id'    => $user_id,
            'content'    => $comment,
        ]);

        return $this->success(trans('user.add comment success'), 200);
    }
}
