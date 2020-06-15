<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\CmsPage;
use App\Category;

class CmsController extends Controller
{
    public function addCmsPage(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

            if(empty($data['meta_title'])){
                $data['meta_title'] = "";
            }
            if(empty($data['meta_description'])){
                $data['meta_description'] = "";
            }
            if(empty($data['meta_keywords'])){
                $data['meta_keywords'] = "";
            }

            $cmspage = new CmsPage;
            $cmspage->title = $data['title'];
            $cmspage->url = $data['url'];
            $cmspage->description = $data['description'];
            $cmspage->meta_title = $data['meta_title'];
            $cmspage->meta_description = $data['meta_description'];
            $cmspage->meta_keywords = $data['meta_keywords'];

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

            if(empty($data['meta_title'])){
                $data['meta_title'] = "";
            }
            if(empty($data['meta_description'])){
                $data['meta_description'] = "";
            }
            if(empty($data['meta_keywords'])){
                $data['meta_keywords'] = "";
            }

            CmsPage::where('id',$id)->update([
                                            'title'=>$data['title'],
                                            'url'=>$data['url'],
                                            'description'=>$data['description'],
                                            'meta_title'=>$data['meta_title'],
                                            'meta_description'=>$data['meta_description'],
                                            'meta_keywords'=>$data['meta_keywords'],
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
            $meta_title = $cmsPageDetails->meta_title;
            $meta_description = $cmsPageDetails->meta_description;
            $meta_keywords = $cmsPageDetails->meta_keywords;
        }else{
            abort(404);
        }        

        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        return \view('pages.cms_page')->with(\compact('cmsPageDetails','categories',
                'meta_title','meta_description','meta_keywords'));
    }

    public function contact(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

            // Validation Contact Us Page
            $validator = Validator::make($request->all(), [
                'name' => 'required||regex:/^[\pL\s\-]+$/u|max:255',
                'email' => 'required|email',
                'subject' => 'required|max:255',
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            //Send Contact Email
            $email = "mile.javakv@gmail.com";
            $messageData = [
                'name'=>$data['name'],
                'email'=>$data['email'],
                'subject'=>$data['subject'],
                'comment'=>$data['message']
            ];
            Mail::send('emails.enquiry',$messageData,function($message)use($email){
                $message->to($email)->subject('Enquiry from ECommerce');
                $message->from('mile.javakv@gmail.com','ECommerce Contact');                
            });
            return \redirect()->back()->with('flash_message_success','Thanks for your enquiry.
                    We will get back to you soon.');
        }
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        // Meta tags
        $meta_title = "Contact Us - ECommerce Website";
        $meta_description = "Contact us for any queries related to our products";
        $meta_keywords = "contact us, queries";

        return \view('pages.contact')->with(\compact('categories',
                'meta_title','meta_description','meta_keywords'));
    }
}
