<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class comments extends Controller
{
    public function index(){
        $comments = Comment::paginate();
        return view('admin.comments.commentsShow')->with('comments',$comments);
    }

    public function destroy($id){
        $comment = comment::find($id);

        if($comment == null)
            return redirect()->back()->with('error', 'this comment not found');

        if($comment->delete())
            return redirect()->back()->with('success', 'success');

        return redirect()->back()->with('error', 'faild');
    }
}
