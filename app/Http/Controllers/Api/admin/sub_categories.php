<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\sub_category\add;
use App\Http\Requests\sub_category\edit;
use App\Models\Image;
use App\Models\Main_category;
use App\Models\Sub_category;
use App\Models\Sub_categoryTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class sub_categories extends Controller
{
    public function sub_categoryShow(){
        $sub_categories = Sub_category::where('status', '!=', -1)->paginate();
        return view('admin.sub_categories.sub_categoriesShow')->with('sub_categories',$sub_categories);
    }

    public function sub_category_delete($id){
        try{
            DB::beginTransaction();
            $sub_category = Sub_category::find($id);

            if($sub_category == null)
                return redirect()->back()->with('error', 'delete sub category faild');

            $sub_category->update(['status'=> -1]);

            DB::commit();
            return redirect()->back()->with('success', 'delete sub category success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'delete sub category faild');
        }        
    }

    public function active($id){
        try{
            DB::beginTransaction();
            $sub_category = Sub_category::find($id);

            if($sub_category == null)
                return redirect()->back()->with('error', 'delete main category faild');

            if($sub_category->status == 0){
                $status = 1;
            } else {
                $status = 0;
            }

            $sub_category->update(['status'=> $status]);

            DB::commit();
            return redirect()->back()->with('success', 'success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'faild');
        }  
    }

    public function add_view(){
        $main_categories = Main_category::where('status', '!=', -1)->get();
        return view('admin.sub_categories.sub_categoriesAdd')->with('main_categories', $main_categories);
    }

    public function add(add $request){
        try{
            DB::beginTransaction();
            
            $image_name = $this->upload_image($request->file('image'),'uploads/sub_categories', 300, 300);

            $sub_category = Sub_category::create([
                'main_cate_id'  => $request->main_category_id,
                'status'        => 1,                
            ]);

            foreach($request->sub_cate as $key=>$cat){
                Sub_categoryTranslation::create([
                    'sub_category_id'=> $sub_category->id,
                    'name'          => $cat['name'],
                    'locale'        => $key,
                ]);
            }

            Image::create([
                'imageable_id'   =>  $sub_category->id,
                'imageable_type' => 'App\Models\Sub_category',
                'src'          => $image_name,
            ]);

            DB::commit();
            return redirect('admin/sub_categories')->with('success', 'add sub category success');
        } catch(\Exception $ex){
            return $ex;
            return redirect()->back()->with('error', 'add sub category faild');
        }   
    }

    public function edit_View($id){
        $main_categories = Main_category::where('status', '!=', -1)->get();

        $sub_category = Sub_category::find($id);

        if($sub_category == null)
            return redirect()->back()->with('error', 'delete sub category faild');

        return view('admin.sub_categories.sub_categoryEdit')->with([
            'sub_category_id'           => $id,
            'sub_category'              => $sub_category,
            'main_categories'           => $main_categories,
        ]);
    }

    public function edit(edit $request,$id){
        $sub_category = Sub_category::find($id);

        if($sub_category == null)
            return redirect()->back()->with('error', 'sub main category faild');

        try{
            DB::beginTransaction();
            $sub_category_parent = Sub_category::find($id);

            if($request->hasFile('image')){
                $oldImage = $sub_category_parent->Image->src;
                
                if(file_exists(base_path('public/uploads/sub_categories/') . $oldImage)){
                    unlink(base_path('public/uploads/sub_categories/') . $oldImage);
                }

                $image_name = $this->upload_image($request->file('image'),'uploads/sub_categories', 300, 300);
                $sub_category_parent->Image->src = $image_name;
                $sub_category_parent->Image->save();
            }

            $sub_categoriesTranslation = Sub_categoryTranslation::where('sub_category_id', $id)->get();

            foreach($sub_categoriesTranslation as $sub_categoryTranslation){
                $sub_category->sub_cate_id  =  $request->main_category_id;
                $sub_categoryTranslation->name = $request->sub_cate[$sub_categoryTranslation->locale]['name'];
                $sub_categoryTranslation->save();
            }

            DB::commit();
            return redirect('admin/sub_categories')->with('success', 'update sub category success');
        } catch(\Exception $ex){
            return $ex;
            return redirect()->back()->with('error', 'update sub category faild');
        }   
    }
}
