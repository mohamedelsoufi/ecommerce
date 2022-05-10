<?php

namespace App\Http\Controllers\Api\site\vendors\authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\vendorResource;
use App\Models\Image;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class profile extends Controller
{
    use response;
    public function index(Request $request){
        if (! $vendor = auth('vendor')->user())
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');

        return response::success(trans('auth.success'), 200, 'vendor', new vendorResource($vendor));
    }

    public function update(Request $request){
        if (! $vendor = auth('vendor')->user())
            return response::faild(trans('vendor.vendor not found'), 404, 'E04');
        
        $validator = Validator::make($request->all(), [
            'fullName'          => 'nullable|string',
            'phone'             => 'nullable|string',
            'email'             => 'nullable|string|unique:vendors,email,' . $vendor->id,
            'gender'            => 'nullable|string',
            'birth'             => 'nullable|string',
            'image'             => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors(), 403);
        }

        if($request->has('image'))
            $this->update_image($request->file('image'), $vendor);

        $input = $request->only(
            'fullName', 'phone', 'email', 'gender', 'birth' 
        );

        if($vendor->update($input)){
            return response()->json([
                'successful'=> true,
                'message'   => trans('auth.success'),
                'vendor'      => new vendorResource($vendor),
            ], 200);
        }

        return response::faild(trans('auth.update profile faild'), 400);
    }

    public function update_image($image, $vendor){
        $image_name = $this->upload_image($image,'uploads/vendors', 300, 300);

        if($vendor->image == null){
            Image::create([
                'imageable_id'   => $vendor->id,
                'imageable_type' => 'App\Models\Vendor',
                'src'            => $image_name,
            ]);
        } else {
            $oldImage = $vendor->image->src;

            if(file_exists(base_path('public/uploads/vendors/') . $oldImage)){
                unlink(base_path('public/uploads/vendors/') . $oldImage);
            }

            $vendor->image->src = $image_name;
            $vendor->image->save();
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

        if (! $vendor = auth('vendor')->user())
            return response::faild('vendor_not_found', 404);

        return $this->updatePassword($request->get('oldPassword'),$request->get('password'),$vendor);
    }

    public function updatePassword($oldPassword, $newPassword, $vendor){
        if(!Hash::check($oldPassword, $vendor->password))
            return response::faild(trans('auth.old password is wrong'), 400);

        $vendor->password  = Hash::make($newPassword);
        if($vendor->save())
            return response::success(trans('auth.change password success'), 200);

        return response::faild(trans('auth.update password faild'), 400);
    }
}
