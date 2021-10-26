<?php

namespace App\Http\Controllers\Api\site\authentication;

use App\Http\Controllers\Api\site\address as SiteAddress;
use App\Http\Controllers\Controller;
use App\Http\Resources\addressResource;
use App\Http\Resources\userResource;
use App\Http\Resources\venderResource;
use App\Models\Address;
use App\Models\Image;
use App\Models\User;
use App\Models\Vender;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class profile extends Controller
{
    use response;

    public function __construct(SiteAddress $address){
        $this->address    = $address;
    }

    public function getProfile(Request $request){
        //get guard
        $guard = $request->route()->getName();

        //get user
        if (! $data = auth($guard)->user()) {
            return $this::falid(trans('user.user not found'), 404, 'E04');
        } 

        if($guard == 'user'){
            return $this::success(trans('auth.success'), 200, 'data', new userResource($data));
        } else if($guard == 'vender'){
            return $this::success(trans('auth.success'), 200, 'data', new venderResource($data));
        }
    }

    public function changePassword(Request $request){
        //get guard
        $guard = $request->route()->getName();

        // validate registeration request
        $validator = Validator::make($request->all(), [
            'oldPassword'       => 'required|string',
            'password'          => 'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
        ]);

        if($validator->fails()){
            return $this::falid($validator->errors(), 403);
        }

        //get user or vender
        if (! $data = auth($guard)->user()) {
            return $this::falid('user_not_found', 404);
        }        
        
        //update data
        if(Hash::check($request->oldPassword, $data->password)){
            $data->password  = Hash::make($request->get('password'));
        } else {
            return $this::falid(trans('auth.old password is wrong'), 400);
        }

        if($data->save()){
            return $this::success(trans('auth.change password success'), 200);
        } else {
            return $this::falid(trans('auth.update password falid'), 400);
        }
    }

    public function editProdile(Request $request){
        $guard = $request->route()->getName();
        $table = $guard . 's';

        //get user
        if (! $data = auth($guard)->user()) {
            return $this::falid(trans('user.user not found'), 404, 'E04');
        }

        // validate registeration request
        $validator = Validator::make($request->all(), [
            'fullName'          => 'nullable|string',
            'phone'             => 'nullable|string',
            'email'             => 'nullable|string|unique:' . $table . ',email,' . $data->id,
            'gender'            => 'nullable|string',
            'birth'             => 'nullable|string',
        ]);

        if($validator->fails()){
            return $this::falid($validator->errors(), 403);
        }

        //request data to update
        if($guard == 'user'){
            $input = $request->only(
                'fullName', 'phone', 'email', 'gender', 'birth' 
            );
        } else {
            $input = $request->only(
                'fullName', 'phone', 'email', 'gender', 'birth' 
            );
        }


        if($data->update($input)){
            return $this::success(trans('auth.update data success'), 200);
        }

        return $this::falid(trans('auth.update profile faild'), 400);
    }

    public function edit_image(Request $request){
        try{
            DB::beginTransaction();
            $guard = $request->route()->getName();

            // validate registeration request
            $validator = Validator::make($request->all(), [
                'image'       => 'required|mimes:jpeg,jpg,png,gif',
            ]);

            if($validator->fails()){
                return $this::falid($validator->errors(), 403);
            }

            //get model
            if($guard == 'user'){
                $model = 'App\Models\User';
            } else if($guard == 'vender'){
                $model = 'App\Models\Vender';
            }

            //get user
            if (! $data = auth($guard)->user()) {
                return $this::falid(trans('user.user not found'), 404, 'E04');
            }

            //update image
            $image_name = $this->upload_image($request->file('image'),'uploads/' . $guard . 's', 300, 300);

            if($data->image == null){
                //if user don't have image 
                Image::create([
                    'imageable_id'   => $data->id,
                    'imageable_type' => $model,
                    'image'          => $image_name,
                ]);
            } else {
                //if user have image
                $oldImage = $data->image->image;

                if(file_exists(base_path('public/uploads/'  . $guard . 's/') . $oldImage)){
                    unlink(base_path('public/uploads/' . $guard . 's/') . $oldImage);
                }

                $data->image->image = $image_name;
                $data->image->save();
            }

            DB::commit();
            return $this::success(trans('auth.update image success'), 200);
        } catch(\Exception $ex){
            return $this::falid(trans('auth.update image faild'), 200);
        }   
    }

    public function add_address(Request $request){
        $guard = $request->route()->getName();

        // validate registeration request
        $validator = Validator::make($request->all(), [
            'country'           => 'required|string',
            'city'              => 'required|string',
            'Neighborhood'      => 'required|string',
            'region'            => 'required|string',
            'street_name'       => 'required|string',
            'building_number'   => 'required|string',
            'notes'             => 'required|string',
        ]);

        if($validator->fails()){
            return $this::falid($validator->errors(), 403, 'E03');
        }
        //get user
        if (! $data = auth($guard)->user()) {
            return $this::falid(trans('user.user not found'), 404, 'E04');
        }

        //get model
        if($guard == 'user'){
            $model = 'App\Models\User';
        } else {
            $model = 'App\Models\Vender';
        }
        //check if user already add address
        $address = Address::where('addressable_id', $data->id)->where('addressable_type', $model)->first();

        if($address !=  null){
            return $this->falid(trans('auth.you already add address'), 400);
        }

        //create address
        $new_address = Address::create([
            'addressable_id'        => $data->id,
            'addressable_type'      => $model,
            'country'               => $request->get('country'),
            'city'                  => $request->get('city'),
            'Neighborhood'          => $request->get('Neighborhood'),
            'region'                => $request->get('region'),
            'street_name'           => $request->get('street_name'),
            'building_number'       => $request->get('building_number'),
            'notes'                 => $request->get('notes'),
        ]);

        return $this::success(trans('all.add address success'), 200, 'address', new addressResource($new_address));
    }

    public function edit_address(Request $request){
        $guard = $request->route()->getName();

        //get user
        if (! $data = auth($guard)->user()) {
            return $this::falid(trans('user.user not found'), 404, 'E04');
        }

        if($data->Address == null){
            return $this->falid(trans('auth.you should add address first'), 400);
        }

        //set address id with request
        $request->request->add(['address_id' => $data->Address->id]);

        $address = $this->address->editAddress($request);

        return $address;
    }
}
