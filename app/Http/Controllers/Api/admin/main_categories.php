<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\main_category\add;
use App\Http\Requests\main_category\edit;
use App\Models\Image;
use App\Models\Main_category;
use App\Models\Main_categoryTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class main_categories extends Controller
{
    public function index(){
        $main_categories = Main_category::where('status', '!=', -1)->paginate();

        return view('admin.main_categories.main_categoriesShow')->with('main_categories',$main_categories);
    }

    public function destroy($id){
        try{
            DB::beginTransaction();
            $main_category = Main_category::find($id);

            if($main_category == null)
                return redirect()->back()->with('error', 'delete main category faild');
            
            $main_category->update(['status'=> -1]);

            DB::commit();
            return redirect()->back()->with('success', 'delete main category success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'delete main category faild');
        }        
    }

    public function active($id){
        try{
            DB::beginTransaction();

            $main_category = Main_category::find($id);

            if($main_category == null)
                return redirect()->back()->with('error', 'delete main category faild');

            if($main_category->status == 0){
                $status = 1;
            } else {
                $status = 0;
            }

            $main_category->update(['status'=> $status]);

            DB::commit();
            return redirect()->back()->with('success', 'success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'faild');
        }  
    }

    public function create(){
        return view('admin.main_categories.main_categoriesAdd');
    }

    public function store(add $request){
        try{
            DB::beginTransaction();      
            $image_name = $this->upload_image($request->file('image'),'uploads/main_categories', 300, 300);

            $main_category = Main_category::create([
                'status'    => 1,
            ]);

            foreach($request->main_cate as $key=>$cat){
                Main_categoryTranslation::create([
                    'main_category_id' => $main_category->id,
                    'name'      => $cat['name'],
                    'locale'    => $key,
                ]);
            }

            Image::create([
                'imageable_id'   =>  $main_category->id,
                'imageable_type' => 'App\Models\Main_category',
                'src'            => $image_name,
            ]);

            DB::commit();
            return redirect('admin/main_categories')->with('success', 'add main category success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'add main category faild');
        }   

    }

    public function show($id){
        $main_category = Main_category::find($id);

        if($main_category == null)
            return redirect()->back()->with('error', 'delete main category faild');

        return view('admin.main_categories.main_categoryEdit')->with('main_category_id', $id);
    }

    public function edit(edit $request,$id){
        $main_category = Main_category::find($id);

        if($main_category == null)
            return redirect()->back()->with('error', 'delete main category faild');

        try{
            DB::beginTransaction();
            $main_category_parent = Main_category::find($id);

            if($request->hasFile('image')){
                $oldImage = $main_category_parent->Image->src;
                
                if(file_exists(base_path('public/uploads/main_categories/') . $oldImage)){
                    unlink(base_path('public/uploads/main_categories/') . $oldImage);
                }

                //upload new image
                $image_name = $this->upload_image($request->file('image'),'uploads/main_categories', 300, 300);
                $main_category_parent->Image->src = $image_name;
                $main_category_parent->Image->save();
            }

            $main_categoriesTranslation = Main_categoryTranslation::where('main_category_id', $id)->get();

            foreach($main_categoriesTranslation as $main_categoryTranslation){
                $main_categoryTranslation->name = $request->main_cate[$main_categoryTranslation->locale]['name'];
                $main_categoryTranslation->save();
            }

            DB::commit();
            return redirect('admin/main_categories')->with('success', 'update main category success');
        } catch(\Exception $ex){
            return redirect()->back()->with('error', 'update main category faild');
        }   
    }
}
