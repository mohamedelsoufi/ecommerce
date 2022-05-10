<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\sub_category\add;
use App\Http\Requests\sub_category\edit;
use App\Models\Image;
use App\Models\Main_category;
use App\Models\Sub_category;
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

            //sellect sub category
            $sub_category = Sub_category::where('id', $id)->where('locale', '=','0')->first();

            if($sub_category == null){
                return redirect()->back()->with('error', 'delete sub category faild');
            }
            //delete sub category parent
            $sub_category->update(['status'=> -1]);


            //delete sub categories all languages
            $sub_categories_all_languages = Sub_category::where('parent', $id)->get();

            foreach($sub_categories_all_languages as $cate){
                $cate->update(['status'=> -1]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'delete sub category success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'delete sub category faild');
        }        
    }

    public function active($id){
        try{
            DB::beginTransaction();

            //sellect sub category
            $sub_category = Sub_category::where('id', $id)->where('locale', '=','0')->first();

            if($sub_category == null){
                return redirect()->back()->with('error', 'delete main category faild');
            }

            if($sub_category->status == 0){
                //active
                $n = 1;
            } else {
                //un active
                $n = 0;
            }

            //active (un active) sub category parent
            $sub_category->update(['status'=> $n]);


            //active (un active) sub categories all languages
            $sub_categories_all_languages = Sub_category::where('parent', $id)->get();

            foreach($sub_categories_all_languages as $cate){
                $cate->update(['status'=> $n]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'faild');
        }  
    }

    public function add_view(){
        $main_categories = Main_category::where('locale', '=','0')->where('status', '!=', -1)->get();
        return view('admin.sub_categories.sub_categoriesAdd')->with('main_categories', $main_categories);
    }

    public function add(add $request){
        try{
            DB::beginTransaction();
            
            $image_name = $this->upload_image($request->file('image'),'uploads/sub_categories', 300, 300);

            $sub_category_parent = Sub_category::create([
                'name'          => $request->sub_cate['en']['name'],
                'main_cate_id'  => $request->main_category_id,
                'status'        => 1,
                'locale'        => 0,
                'parent'        => 0,
                
            ]);

            //add category in all lang
            foreach($request->sub_cate as $key=>$cat){
                Sub_category::create([
                    'name'          => $cat['name'],
                    'main_cate_id'  => $request->main_category_id,
                    'status'        => 1,
                    'locale'        => $key,
                    'parent'        => $sub_category_parent->id,
                ]);
            }

            //add image for this sub cate (put parent id)
            Image::create([
                'imageable_id'   =>  $sub_category_parent->id,
                'imageable_type' => 'App\Models\Sub_category',
                'image'          => $image_name,

            ]);

            DB::commit();
            return redirect('admin/sub_categories')->with('success', 'add sub category success');
        } catch(\Exception $ex){
            return $ex;
            return redirect()->back()->with('error', 'add sub category faild');
        }   
    }

    public function edit_View($id){
        $main_categories = Main_category::where('locale', '=','0')->where('status', '!=', -1)->get();

        //sellect sub category
        $sub_category = Sub_category::where('id', $id)->where('locale', '=','0')->first();
        if($sub_category == null){
            return redirect()->back()->with('error', 'delete sub category faild');
        }

        return view('admin.sub_categories.sub_categoryEdit')->with([
            'sub_category_parent_id'    => $id,
            'sub_category'              => $sub_category,
            'main_categories'           => $main_categories,
        ]);
    }

    public function edit(edit $request,$id){
        //sellect sub category
        $sub_category = Sub_category::where('id', $id)->where('locale', '=','0')->first();

        if($sub_category == null){
            return redirect()->back()->with('error', 'delete main category faild');
        }

        try{
            DB::beginTransaction();
            //sellect parent sub category
            $sub_category_parent = Sub_category::find($id);

            //update image
            if($request->hasFile('image')){
                //delete old image
                $oldImage = $sub_category_parent->Image->src;
                
                if(file_exists(base_path('public/uploads/sub_categories/') . $oldImage)){
                    unlink(base_path('public/uploads/sub_categories/') . $oldImage);
                }

                //upload new image
                $image_name = $this->upload_image($request->file('image'),'uploads/sub_categories', 300, 300);
                $sub_category_parent->Image->src = $image_name;
                $sub_category_parent->Image->save();
            }

            //update (parent) sub category
            $sub_category_parent                = Sub_category::find($id);
            $sub_category_parent->name          =  $request->sub_cate['en']['name'];
            $sub_category_parent->main_cate_id  =  $request->main_category_id;
            $sub_category_parent->save();

            //update sub category in all lang
            foreach($request->sub_cate as $key=>$cat){
                $sub_category = Sub_category::where('parent', $id)->where('locale', $key)->first();
                $sub_category->name =  $cat['name'];
                $sub_category->main_cate_id  =  $request->main_category_id;
                $sub_category->save();
            }

            DB::commit();
            return redirect('admin/sub_categories')->with('success', 'update sub category success');
        } catch(\Exception $ex){
            return $ex;
            return redirect()->back()->with('error', 'update sub category faild');
        }   
    }
}
