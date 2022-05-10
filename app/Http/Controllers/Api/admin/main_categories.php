<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\main_category\add;
use App\Http\Requests\main_category\edit;
use App\Models\Image;
use App\Models\Main_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class main_categories extends Controller
{
    public function main_categoryShow(){
        $main_categories = Main_category::where('status', '!=', -1)->paginate();
        return view('admin.main_categories.main_categoriesShow')->with('main_categories',$main_categories);
    }

    public function main_category_delete($id){
        try{
            DB::beginTransaction();

            //sellect main category
            $main_category = Main_category::where('id', $id)->where('locale', '=','0')->first();

            if($main_category == null){
                return redirect()->back()->with('error', 'delete main category faild');
            }
            //delete main category parent
            $main_category->update(['status'=> -1]);


            //delete main categories all languages
            $main_categories_all_languages = Main_category::where('parent', $id)->get();

            foreach($main_categories_all_languages as $cate){
                $cate->update(['status'=> -1]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'delete main category success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'delete main category faild');
        }        
    }

    public function active($id){
        try{
            DB::beginTransaction();

            //sellect main category
            $main_category = Main_category::where('id', $id)->where('locale', '=','0')->first();

            if($main_category == null){
                return redirect()->back()->with('error', 'delete main category faild');
            }

            if($main_category->status == 0){
                //active
                $n = 1;
            } else {
                //un active
                $n = 0;
            }

            //active (un active) main category parent
            $main_category->update(['status'=> $n]);


            //active (un active) main categories all languages
            $main_categories_all_languages = Main_category::where('parent', $id)->get();

            foreach($main_categories_all_languages as $cate){
                $cate->update(['status'=> $n]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'faild');
        }  
    }

    public function add_view(){
        return view('admin.main_categories.main_categoriesAdd');
    }

    public function add(add $request){
        try{
            DB::beginTransaction();
            
            $image_name = $this->upload_image($request->file('image'),'uploads/main_categories', 300, 300);

            $main_category_parent = Main_category::create([
                'name'      => $request->main_cate['en']['name'],
                'status'    => 1,
                'locale'    => 0,
                'parent'    => 0,
            ]);

            //add category in all lang
            foreach($request->main_cate as $key=>$cat){
                Main_category::create([
                    'name'      => $cat['name'],
                    'status'    => 1,
                    'locale'    => $key,
                    'parent'    => $main_category_parent->id,
                ]);
            }

            //add image for this main cate (put parent id)
            Image::create([
                'imageable_id'   =>  $main_category_parent->id,
                'imageable_type' => 'App\Models\Main_category',
                'image'          => $image_name,

            ]);

            DB::commit();
            return redirect('admin/main_categories')->with('success', 'add main category success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'add main category faild');
        }   

    }

    public function edit_View($id){
        //sellect main category
        $main_category = Main_category::where('id', $id)->where('locale', '=','0')->first();
        if($main_category == null){
            return redirect()->back()->with('error', 'delete main category faild');
        }

        return view('admin.main_categories.main_categoryEdit')->with('main_category_parent_id', $id);
    }

    public function edit(edit $request,$id){
        //sellect main category
        $main_category = Main_category::where('id', $id)->where('locale', '=','0')->first();

        if($main_category == null){
            return redirect()->back()->with('error', 'delete main category faild');
        }

        try{
            DB::beginTransaction();
            //sellect parent main category
            $main_category_parent = Main_category::find($id);

            //update image
            if($request->hasFile('image')){
                //delete old image
                $oldImage = $main_category_parent->Image->src;
                
                if(file_exists(base_path('public/uploads/main_categories/') . $oldImage)){
                    unlink(base_path('public/uploads/main_categories/') . $oldImage);
                }

                //upload new image
                $image_name = $this->upload_image($request->file('image'),'uploads/main_categories', 300, 300);
                $main_category_parent->Image->src = $image_name;
                $main_category_parent->Image->save();
            }

            //update (parent) main category
            $main_category_parent = Main_category::find($id);
            $main_category_parent->name =  $request->main_cate['en']['name'];
            $main_category_parent->save();

            //update main category in all lang
            foreach($request->main_cate as $key=>$cat){
                $main_category = Main_category::where('parent', $id)->where('locale', $key)->first();
                $main_category->name =  $cat['name'];
                $main_category->save();
            }

            DB::commit();
            return redirect('admin/main_categories')->with('success', 'update main category success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'update main category faild');
        }   
    }
}
