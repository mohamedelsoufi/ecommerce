<?php

namespace App\Http\Controllers\Api\site\users\authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\userResource;
use App\Models\Image;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class profile extends Controller
{
    use response;
    public function index(){
        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');

        return response::success(trans('auth.success'), 200, 'user', new userResource($user));
    }

    public function update(Request $request){
        if (! $user = auth('user')->user())
            return response::faild(trans('user.user not found'), 404, 'E04');
        
        $validator = Validator::make($request->all(), [
            'fullName'          => 'nullable|string',
            'phone'             => 'nullable|string',
            'email'             => 'nullable|string|unique:users,email,' . $user->id,
            'gender'            => 'nullable|string',
            'birth'             => 'nullable|string',
            'image'             => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors(), 403);
        }

        if($request->has('image'))
            $this->update_image($request->file('image'), $user);

        $input = $request->only(
            'fullName', 'phone', 'email', 'gender', 'birth' 
        );

        if($user->update($input)){
            return response()->json([
                'successful'=> true,
                'message'   => trans('auth.success'),
                'user'      => new userResource($user),
            ], 200);
        }

        return response::faild(trans('auth.update profile faild'), 400);
    }

    public function update_image($image, $user){
        $image_name = $this->upload_image($image,'uploads/users', 300, 300);

        if($user->Image == null){
            Image::create([
                'imageable_id'   => $user->id,
                'imageable_type' => 'App\Models\User',
                'src'            => $image_name,
            ]);
        } else {
            $oldImage = $user->Image->src;

            if(file_exists(base_path('public/uploads/users/') . $oldImage)){
                unlink(base_path('public/uploads/users/') . $oldImage);
            }

            $user->Image->src = $image_name;
            $user->Image->save();
        }
    }

    public function changePasswordProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'oldPassword'       => 'required|string',
            'password'          => 'required|string|min:6',
            'confirm_password'  => 'required|string|same:password',
        ]);

        if($validator->fails())
            return response::faild($validator->errors(), 403);

        if (! $user = auth('user')->user())
            return response::faild('user_not_found', 404);

        return $this->updatePassword($request->get('oldPassword'),$request->get('password'),$user);
    }

    public function updatePassword($oldPassword, $newPassword, $user){
        if(!Hash::check($oldPassword, $user->password))
            return response::faild(trans('auth.old password is wrong'), 400);

        $user->password  = Hash::make($newPassword);
        if($user->save())
            return response::success(trans('auth.change password success'), 200);

        return response::faild(trans('auth.update password faild'), 400);
    }

}
