<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function getAddCategory(){
        $levels = Category::where(['parent_id'=>0])->get();
        return \view('admin.categories.add_category')->with(compact('levels'));
    }

    public function postAddCategory(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; \print_r($data); die;

            if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }

            $category = new Category;
            $category->name = $data['category_name'];
            $category->parent_id = $data['parent_id'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->status = $status;
            $category->save();
            return \redirect('/admin/view-categories')->with('flash_message_success','Category added Successfully.');
        }
        
        #return \view('admin.categories.add_category');
    }

    public function getViewCategories(){
        $categories = Category::get();
        return \view('admin.categories.view_categories')->with(\compact('categories'));
    }

    public function getEditCategory($id=null){         
        $categoryDetails = Category::where(['id'=>$id])->first();
        $levels = Category::where(['parent_id'=>0])->get();
        return \view('admin.categories.edit_category')->with(compact('categoryDetails', 'levels'));
    }

    public function postEditCategory(Request $request, $id=null){ 
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

            if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }

            Category::where(['id'=>$id])->update(['name'=>$data['category_name'],
                                                  'description'=>$data['description'],
                                                  'url'=>$data['url'],
                                                  'status'=>$status]);
            return \redirect('admin/view-categories')->with('flash_message_success','Category updated Successfully');
        }       
        //return \view('admin.categories.edit_category');
    }

    public function deleteCategory($id = null){
        //echo $id; die;
        if (!empty($id)) {
            Category::where(['id'=>$id])->delete();
            return \redirect()->back()->with('flash_message_success','Category deleted Successfully');
        }
    }
}
