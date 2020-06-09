<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CmsPage;
use App\Category;

class CmsController extends Controller
{
    public function addCmsPage(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $cmspage = new CmsPage;
            $cmspage->title = $data['title'];
            $cmspage->url = $data['url'];
            $cmspage->description = $data['description'];

            if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }
            $cmspage->status = $status;
            $cmspage->save();
            return \redirect()->back()->with('flash_message_success','CMS Page has been added successfully!');
        }
        return \view('admin.pages.add_cms_page');
    }

    public function viewCmsPages(){
        $cmsPages = CmsPage::get();
        //$cmsPages = \json_decode(\json_encode($cmsPages));
        //echo "<pre>"; print_r($cmsPages); die;
        return \view('admin.pages.view_cms_pages')->with(\compact('cmsPages'));
    }

    public function editCmsPage(Request $request,$id){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

            if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }

            CmsPage::where('id',$id)->update([
                                            'title'=>$data['title'],
                                            'url'=>$data['url'],
                                            'description'=>$data['description'],
                                            'status'=>$status
                                            ]);
            return \redirect()->back()->with('flash_message_success','CMS Page has been updated successfully!');
        }
        $cmsPage = CmsPage::where('id',$id)->first();
        //$cmsPage = \json_decode(\json_encode($cmsPage));
        //echo "<pre>"; print_r($cmsPage); die;

        return \view('admin.pages.edit_cms_page')->with(\compact('cmsPage'));
    }

    public function deleteCmsPages($id){
        CmsPage::where('id',$id)->delete();
        return \redirect('admin/view-cms-page')->with('flash_message_success','CMS Page has been deleted successfully!');
    }

    public function cmsPage($url){

        // Check if CMS Page is disabled
        $cmsPageCount = CmsPage::where(['url'=>$url,'status'=>1])->count();
        if($cmsPageCount > 0){
            $cmsPageDetails = CmsPage::where('url',$url)->first();
        }else{
            abort(404);
        }        

        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        return \view('pages.cms_page')->with(\compact('cmsPageDetails','categories'));
    }
}
