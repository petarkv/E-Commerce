<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Arr;
use Auth;
use Session;
use Image;
use DB;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use App\Coupon;
use App\Banner;
use App\User;
use App\Country;
use App\DeliveryAddress;
use App\Order;
use App\OrdersProduct;


class ProductController extends Controller
{
    public function getAddProduct(){
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option selected disabled>Select</option>";
        foreach ($categories as $cat) {
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                $categories_dropdown .= "<option value='".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
        
        $sleeveArray = array('Long Sleeve','Short Sleeve');

        $patternArray = array('Checked','Plain','Printed','Self','Solid');

        return \view('admin.products.add_product')->with(compact('categories_dropdown','sleeveArray','patternArray'));
    }

    public function postAddProduct(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $product = new Product;
            if (empty($data['category_id'])) {
                return \redirect()->back()->with('flash_message_error','Sub Category is missing');
            }
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];

            if (!empty($data['description'])) {
                $product->description = $data['description'];
            }else{
                $product->description = '';
            }

            if (!empty($data['sleeve'])) {
                $product->sleeve = $data['sleeve'];
            }else{
                $product->sleeve = '';
            }

            if (!empty($data['pattern'])) {
                $product->pattern = $data['pattern'];
            }else{
                $product->pattern = '';
            }

            if (!empty($data['care'])) {
                $product->care = $data['care'];
            }else{
                $product->care = '';
            }
            
            $product->price = $data['price'];

            if (!empty($data['product_weight'])) {
                $product->weight = $data['product_weight'];
            }else{
                $product->weight = 0;
            }

            //Upload Image
            //$product->image = '';
            if($request->hasFile('image')) {
                $image_tmp = $request->image;
                if ($image_tmp->isValid()) {
                    
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/template_images/products/large/'.$filename;
                    $medium_image_path = 'images/template_images/products/medium/'.$filename;
                    $small_image_path = 'images/template_images/products/small/'.$filename;

                    //Resize Image
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);

                    //Store image name in products table
                    $product->image = $filename;
                }
            }

            // Upload Video
            if ($request->hasFile('video')) {
                $video_tmp = $request->video;
                //echo "<pre>"; print_r($video_tmp); die;
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path,$video_name);
                $product->video = $video_name;
            }

            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }
            $product->status = $status;

            if(empty($data['feature_item'])){
                $feature_item = 0;
            }else{
                $feature_item = 1;
            }
            $product->feature_item = $feature_item;

            $product->save();
            //return \redirect()->back()->with('flash_message_success','Product has been added successfuly');
            return \redirect('/admin/view-products')->with('flash_message_success','Product has been added successfuly');
        }
        //return \view('admin.products.add_product');        
    }

    public function getViewProducts(){
        $products = Product::orderBy('id','desc')->get();
        //$products = \json_decode(\json_encode($products));
        foreach ($products as $key => $prod) {
            $category_name = Category::where(['id'=>$prod->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }
        return \view('admin.products.view_products')->with(\compact('products'));
    }

    public function getEditProduct($id=null){
        if(Session::get('adminDetails')['products_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        $productDetails = Product::where(['id'=>$id])->first();
        
        //Categories Drop Down
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option selected disabled>Select</option>";
        foreach ($categories as $cat) {
            if ($cat->id==$productDetails->category_id) {
                $selected = "selected";
            }else{
                $selected = "";
            }
            $categories_dropdown .= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                if ($sub_cat->id==$productDetails->category_id) {
                    $selected = "selected";
                }else{
                    $selected = "";
                }
                $categories_dropdown .= "<option value='".$sub_cat->id."' ".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }

        $sleeveArray = array('Long Sleeve','Short Sleeve');

        $patternArray = array('Checked','Plain','Printed','Self','Solid');

        return \view('admin.products.edit_product')->with(\compact('productDetails','categories_dropdown',
        'sleeveArray','patternArray'));   
        //return view('admin.products.edit_product')->with(\compact('productDetails'));     
    }


    public function postEditProduct(Request $request, $id=null){
        if(Session::get('adminDetails')['products_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

             //Upload Image            
            if($request->hasFile('image')) {
                $image_tmp = $request->image;
                if ($image_tmp->isValid()) {
                    
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/template_images/products/large/'.$filename;
                    $medium_image_path = 'images/template_images/products/medium/'.$filename;
                    $small_image_path = 'images/template_images/products/small/'.$filename;

                    //Resize Image
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);                    
                }
            }else if(!empty($data['current_image'])){
                $filename = $data['current_image'];
            }else{
                $filename = '';
            }

            // Upload Video
            if ($request->hasFile('video')) {
                $video_tmp = $request->video;
                //echo "<pre>"; print_r($video_tmp); die;
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path,$video_name);
                $videoName = $video_name;
            }else if(!empty($data['current_video'])){
                $videoName = $data['current_video'];
            }else{
                $videoName = '';
            }

            if (empty($data['description'])) {
                $data['description'] = '';
            }

            if (empty($data['care'])) {
                $data['care'] = '';
            }

            if (!empty($data['sleeve'])) {
                $sleeve = $data['sleeve'];
            }else{
                $sleeve = '';
            }

            if (!empty($data['pattern'])) {
                $pattern = $data['pattern'];
            }else{
                $pattern = '';
            }

            if (empty($data['product_weight'])) {
                $data['product_weight'] = '';
            }

            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }

            if(empty($data['feature_item'])){
                $feature_item = 0;
            }else{
                $feature_item = 1;
            }
            
            Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],
                                                'product_name'=>$data['product_name'],
                                                'product_code'=>$data['product_code'],
                                                'product_color'=>$data['product_color'],
                                                'description'=>$data['description'],
                                                'care'=>$data['care'],
                                                'sleeve'=>$sleeve,
                                                'pattern'=>$pattern,
                                                'price'=>$data['price'],
                                                'weight'=>$data['product_weight'],
                                                'image'=>$filename,
                                                'video'=>$videoName,
                                                'status'=>$status,
                                                'feature_item'=>$feature_item]);

            return \redirect()->back()->with('flash_message_success','Product has been updated Successfully');
        }
    }

    public function deleteProduct($id=null){
        if(Session::get('adminDetails')['products_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        // Product::where(['id'=>$id])->delete();

        // Get Product Image Name
        $productImage = Product::where(['id'=>$id])->first();
        // echo $productImage->image; die;
        $large_image_path = 'images/template_images/products/large/';
        $medium_image_path = 'images/template_images/products/medium/';
        $small_image_path = 'images/template_images/products/small/';

        // Delete Large Image if exist in Folder
        if (\file_exists($large_image_path.$productImage->image)) {
            \unlink($large_image_path.$productImage->image);
        }

        // Delete Midium Image if exist in Folder
        if (\file_exists($medium_image_path.$productImage->image)) {
            \unlink($medium_image_path.$productImage->image);
        }

        // Delete Small Image if exist in Folder
        if (\file_exists($small_image_path.$productImage->image)) {
            \unlink($small_image_path.$productImage->image);
        }

        Product::where(['id'=>$id])->delete();

        return \redirect()->back()->with('flash_message_success', 'Product has been deleted Successfully');
    }

    public function deleteProductImage($id = null){
        if(Session::get('adminDetails')['products_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        // Get Product Image Name
        $productImage = Product::where(['id'=>$id])->first();
        //echo $productImage->image; die;
        $large_image_path = 'images/template_images/products/large/';
        $medium_image_path = 'images/template_images/products/medium/';
        $small_image_path = 'images/template_images/products/small/';

        // Delete Large Image if exist in Folder
        if (\file_exists($large_image_path.$productImage->image)) {
            \unlink($large_image_path.$productImage->image);
        }

        // Delete Midium Image if exist in Folder
        if (\file_exists($medium_image_path.$productImage->image)) {
            \unlink($medium_image_path.$productImage->image);
        }

        // Delete Small Image if exist in Folder
        if (\file_exists($small_image_path.$productImage->image)) {
            \unlink($small_image_path.$productImage->image);
        }

        // Delete Image from Products Table  
        Product::where(['id'=>$id])->update(['image'=>'']);
        
        return \redirect()->back()->with('flash_message_success', 'Product Image has been deleted Successfully');
    } 

    public function deleteAltImage($id = null){
        if(Session::get('adminDetails')['products_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        // Get Product Image Name
        $productImage = ProductsImage::where(['id'=>$id])->first();
        //echo $productImage->image; die;
        $large_image_path = 'images/template_images/products/large/';
        $medium_image_path = 'images/template_images/products/medium/';
        $small_image_path = 'images/template_images/products/small/';

        // Delete Large Image if exist in Folder
        if (\file_exists($large_image_path.$productImage->image)) {
            \unlink($large_image_path.$productImage->image);
        }

        // Delete Midium Image if exist in Folder
        if (\file_exists($medium_image_path.$productImage->image)) {
            \unlink($medium_image_path.$productImage->image);
        }

        // Delete Small Image if exist in Folder
        if (\file_exists($small_image_path.$productImage->image)) {
            \unlink($small_image_path.$productImage->image);
        }

        // Delete Image from Products Table  
        ProductsImage::where(['id'=>$id])->delete();
        
        return \redirect()->back()->with('flash_message_success', 'Product Alternate Image(s) has been deleted Successfully');
    } 

    public function deleteProductVideo($id){
        if(Session::get('adminDetails')['products_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        // Get Video Name
        $productVideo = Product::select('video')->where('id',$id)->first();
        // Get Video Path
        $video_path = 'videos/';

        // Delete Video of exists in videos folder
        if(\file_exists($video_path.$productVideo->video)){
            \unlink($video_path.$productVideo->video);
        }

        // Delete Video from Products table
        Product::where('id',$id)->update(['video'=>'']);

        return \redirect()->back()->with('flash_message_success', 'Product Video has been deleted Successfully');
    }
    
    public function addAttributes(Request $request, $id=null){
        if(Session::get('adminDetails')['products_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        //$productDetails = \json_decode(\json_encode($productDetails));
        //echo "<pre>"; print_r($productDetails); die;
        if ($request->isMethod('post')) {
            $data = $request->all();
 
            foreach ($data['sku'] as $key => $val) {
                if (!empty($val)) {
                    //SKU Check
                    $attrCountSKU = ProductsAttribute::where('sku',$val)->count();
                    if ($attrCountSKU>0) {
                        return \redirect('admin/add-attributes/'.$id)->with('flash_message_error',
                                'SKU already exists! Please add another SKU.');
                    }

                    //Prevent duplicate Size
                    $attrCountSize = ProductsAttribute::where(['product_id'=>$id,'size'=>$data['size'][$key]])->count();
                    if ($attrCountSize>0) {
                        return \redirect('admin/add-attributes/'.$id)->with('flash_message_error',
                                '"'.$data['size'][$key].'" Size already exists for this Product! Please add another Size.');
                    }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
            }
            return \redirect('admin/add-attributes/'.$id)->with('flash_message_success',
                                'Product Attributes have been added Successfully');
        }
        return \view('admin.products.add_attributes')->with(\compact('productDetails'));
    }

    public function editAttributes(Request $request, $id=null){
        if(Session::get('adminDetails')['products_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            foreach ($data['idAttr'] as $key => $attr) {
                ProductsAttribute::where(['id'=>$data['idAttr'][$key]])->update(['price'=>$data['price'][$key],
                                                                                 'stock'=>$data['stock'][$key]]);
            
            }
            return \redirect()->back()->with('flash_message_success','Product Atributes has been updated successfully');
        }
    }

    public function addImages(Request $request, $id=null){
        if(Session::get('adminDetails')['products_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        $productDetails = Product::where(['id'=>$id])->first();

        if ($request->isMethod('post')) {          
            $data = $request->all();
           
            if ($request->hasFile('image')) {
                $files = $request->image; 
                foreach ($files as $file) {

                $image = new ProductsImage;
                $extension = $file->getClientOriginalExtension();
                $filename = rand(111,99999).'.'.$extension;
                $large_image_path = 'images/template_images/products/large/'.$filename;
                $medium_image_path = 'images/template_images/products/medium/'.$filename;
                $small_image_path = 'images/template_images/products/small/'.$filename;
                Image::make($file)->save($large_image_path);
                Image::make($file)->resize(600,600)->save($medium_image_path);
                Image::make($file)->resize(300,300)->save($small_image_path);
                $image->image = $filename;
                //$image->product_id = $id;
                $image->product_id = $data['product_id'];
                $image->save();          
                } 
                
            }
            
            return \redirect('admin/add-images/'.$id)->with('flash_message_success','Product Images has been added');
        }

        $productsImages = ProductsImage::where(['product_id'=>$id])->get();
        $productsImages = \json_decode(\json_encode($productsImages));
        //echo "<pre>"; print_r($productsImages); die;

        return \view('admin.products.add_images')->with(\compact('productDetails','productsImages'));
    }

    public function deleteAttribute($id=null){
        if(Session::get('adminDetails')['products_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        ProductsAttribute::where(['id'=>$id])->delete();
        return \redirect()->back()->with('flash_message_success','Attribute has been deleted Successfully');
    }

    public function products($url = null){

        //Show 404 if category url is inccorect
        $countCategory = Category::where(['url' => $url,'status'=>1])->count();
        if ($countCategory == 0) {
            \abort(404);
        }

        // Get All Categories and Sub Categories
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        $categoryDetails = Category::where(['url'=>$url])->first();

        if ($categoryDetails->parent_id==0) {
            // If url is main category url
            $subCategories = Category::where(['parent_id'=>$categoryDetails->id])->get();
            
            foreach ($subCategories as $subcat) {
                $cat_ids[] = $subcat->id;
            }
            //print_r($cat_ids); die;
            $productsAll = Product::whereIn('products.category_id',$cat_ids)->where('status',1);
            //$productsAll = \json_decode(\json_encode($productsAll));
            //echo "<pre>"; print_r($productsAll); die;
            $breadcrumb = "<a href='/'>Home</a> / <a href='".$categoryDetails->url."'>".$categoryDetails->name."</a>";
        }else{
            $productsAll = Product::where(['products.category_id' => $categoryDetails->id])->where('status',1);
            $mainCategory = Category::where('id',$categoryDetails->parent_id)->first();

            $breadcrumb = "<a href='/'>Home</a> / <a href='".$mainCategory->url."'>".$mainCategory->name."</a>
            / <a href='".$categoryDetails->url."'>".$categoryDetails->name."</a>";            
        }
        
        $banners = Banner::where('status','1')->get();

        if (!empty($_GET['color'])) {
            $colorArray = explode('-',$_GET['color']);
            $productsAll = $productsAll->whereIn('products.product_color',$colorArray);
        }

        if (!empty($_GET['sleeve'])) {
            $sleeveArray = explode('-',$_GET['sleeve']);
            $productsAll = $productsAll->whereIn('products.sleeve',$sleeveArray);
        }

        if (!empty($_GET['pattern'])) {
            $patternArray = explode('-',$_GET['pattern']);
            $productsAll = $productsAll->whereIn('products.pattern',$patternArray);
        }

        if (!empty($_GET['size'])) {
            $sizeArray = explode('-',$_GET['size']);
            $productsAll = $productsAll->join('products_attributes','products_attributes.product_id','=','products.id')->
            select('products.*','products_attributes.product_id','products_attributes.size')->
            whereIn('products_attributes.size',$sizeArray);
        }

        $productsAll = $productsAll->paginate(3);

        //$colorArray = array('Black','Green','Grey','Red','Orange');
        $colorArray = Product::select('product_color')->groupBy('product_color')->get();
        $colorArray = Arr::flatten(\json_decode(\json_encode($colorArray),true));

        $sleeveArray = Product::select('sleeve')->where('sleeve','!=','')->groupBy('sleeve')->get();
        $sleeveArray = Arr::flatten(\json_decode(\json_encode($sleeveArray),true));

        $patternArray = Product::select('pattern')->where('pattern','!=','')->groupBy('pattern')->get();
        $patternArray = Arr::flatten(\json_decode(\json_encode($patternArray),true));
        
        $sizesArray = ProductsAttribute::select('size')->groupBy('size')->get();
        $sizesArray = Arr::flatten(\json_decode(\json_encode($sizesArray),true));

        // echo "<pre>"; print_r($sizesArray); die;

        $meta_title = $categoryDetails->meta_title;
        $meta_description = $categoryDetails->meta_description;
        $meta_keywords = $categoryDetails->meta_keywords;

        return view('products.listing')->with(\compact('categories','categoryDetails','productsAll','banners',
                    'meta_title','meta_description','meta_keywords','url','colorArray','sleeveArray','patternArray',
                    'sizesArray','breadcrumb'));
    }

    public function filter(Request $request){
        $data = $request->all();
        //echo "<pre>"; print_r($data); die;

        $colorUrl = "";
        if (!empty($data['colorFilter'])) {
            foreach ($data['colorFilter'] as $color) {
                if (empty($colorUrl)) {
                    $colorUrl = "&color=".$color;
                }else{
                    $colorUrl .= "-".$color;
                }
            }
        }

        $sleeveUrl = "";
        if (!empty($data['sleeveFilter'])) {
            foreach ($data['sleeveFilter'] as $sleeve) {
                if (empty($sleeveUrl)) {
                    $sleeveUrl = "&sleeve=".$sleeve;
                }else{
                    $sleeveUrl .= "-".$sleeve;
                }
            }
        }

        $patternUrl = "";
        if (!empty($data['patternFilter'])) {
            foreach ($data['patternFilter'] as $pattern) {
                if (empty($patternUrl)) {
                    $patternUrl = "&pattern=".$pattern;
                }else{
                    $patternUrl .= "-".$pattern;
                }
            }
        }

        $sizeUrl = "";
        if (!empty($data['sizeFilter'])) {
            foreach ($data['sizeFilter'] as $size) {
                if (empty($sizeUrl)) {
                    $sizeUrl = "&size=".$size;
                }else{
                    $sizeUrl .= "-".$size;
                }
            }
        }

        $finalUrl = "products/".$data['url']."?".$colorUrl.$sleeveUrl.$patternUrl.$sizeUrl;
        return redirect::to($finalUrl);
    }


    public function getProductDetails($id = null){

        // Show 404 page if product is disabled
        $productsCount = Product::where(['id'=>$id,'status'=>1])->count();
        if ($productsCount==0) {
            abort(404);
        }

        // Get Product Details
        $productDetails = Product::with('attributes')->where('id',$id)->first();
        //$productDetails = \json_decode(\json_encode($productDetails));
        //echo "<pre>"; print_r($productDetails); die;

        $relatedProducts = Product::where('id','!=',$id)->where(['category_id'=>$productDetails->category_id])->get();
        //$relatedProducts = \json_decode(\json_encode($relatedProducts));

        /*foreach ($relatedProducts->chunk(3) as $chunk) {
            foreach ($chunk as $item) {
                echo $item; echo "<br>";
            }
            echo "<br><br><br>";
        }
        die;*/
        //echo "<pre>"; print_r($relatedProducts); die;

        // Get All Categories and Sub Categories
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        $categoryDetails = Category::where('id',$productDetails->category_id)->first();

        if ($categoryDetails->parent_id==0) {            
            $breadcrumb = "<a href='/'>Home</a> / <a href='".$categoryDetails->url."'>".$categoryDetails->name."</a>
            /".$productDetails->product_name;
        }else{           
            $mainCategory = Category::where('id',$categoryDetails->parent_id)->first();

            $breadcrumb = "<a href='/'>Home</a> / <a href='/products/".$mainCategory->url."'>".$mainCategory->name."</a>
            / <a href='/products/".$categoryDetails->url."'>".$categoryDetails->name."</a> / ".$productDetails->product_name;            
        }

        //Get Product Alternate Images
        $productAltImages = ProductsImage::where('product_id',$id)->get();
        //$productAltImages = \json_decode(\json_encode($productAltImages));
        //echo "<pre>"; print_r($productAltImages); die;

        $total_stock = ProductsAttribute::where('product_id',$id)->sum('stock');

        $meta_title = $productDetails->product_name;
        $meta_description = $productDetails->description;
        $meta_keywords = $productDetails->product_name;

        return \view('products.detail')->with(\compact('productDetails','categories','productAltImages','total_stock',
                    'relatedProducts','meta_title','meta_description','meta_keywords','breadcrumb'));
    }

    public function getProductPrice(Request $request){    
        $data = $request->all();   

        //echo "<pre>"; print_r($data); die;
        $proArr = explode("-",$data['idSize']);
        //echo $proArr[0]; echo $proArr[1]; die;
        $proAttr = ProductsAttribute::where(['product_id' => $proArr[0], 'size' => $proArr[1]])->first();
        
        $getCurrencyRates = Product::getCurrencyRates($proAttr->price);
        echo $proAttr->price."-".$getCurrencyRates['USD_Rate']."-".$getCurrencyRates['RSD_Rate']."-".$getCurrencyRates['CHF_Rate'];
        //echo $proAttr->price;
        echo "#";
        echo $proAttr->stock;
    } 
    
    public function addtocart(Request $request){

        Session::forget('couponAmount');
        Session::forget('couponCode');

        $data = $request->all();
        //echo "<pre>"; print_r($data); die;

        

        if (empty(Auth::user()->email)) {
            $data['user_email'] = '';
        }else{
            $data['user_email'] = Auth::user()->email;
        }

        /*if (empty($data['session_id'])) {
            $data['session_id'] = '';
        }*/

        $session_id = Session::get('session_id');
        if(empty($session_id)){
            $session_id = Str::random(40);
        Session::put('session_id',$session_id);
        }

        $sizeArr = explode("-",$data['size']);
        $priceArr = explode("-",$data['price']);
        if(empty($sizeArr[1])){
            return \redirect()->back()->with('flash_message_error','You need to select Product size first!');
        }else{
            // Check Product Stock is available or not
            $product_size = explode("-",$data['size']);
            //echo $product_size[1]; die;
            $getProductStock = ProductsAttribute::where(['product_id'=>$data['product_id'],
                            'size'=>$product_size[1]])->first();
            //echo $getProductStock->stock; die;

            if ($getProductStock->stock < $data['quantity']) {
                return \redirect()->back()->with('flash_message_error','Required Quantity is not available!');
            }

            $countProducts = DB::table('cart')->where(['product_id'=>$data['product_id'],        
                                                    'product_color'=>$data['product_color'],        
                                                    'size'=>$sizeArr[1],      
                                                    'session_id'=>$session_id])->count();

        //echo $countProducts; die;
        if ($countProducts>0) {
            return \redirect()->back()->with('flash_message_error','Product already exists in Cart!');
        }else{

            $getSKU = ProductsAttribute::select('sku')->where(['product_id'=>$data['product_id'],
            'size'=>$sizeArr[1]])->first();
        }       



        DB::table('cart')->insert(['product_id'=>$data['product_id'],
                                    'product_name'=>$data['product_name'],
                                    'product_code'=>$getSKU->sku,
                                    'product_color'=>$data['product_color'],
                                    'price'=>$priceArr[0],
                                    'size'=>$sizeArr[1],
                                    'quantity'=>$data['quantity'],
                                    'user_email'=>$data['user_email'],
                                    'session_id'=>$session_id]);
        }
        return \redirect('cart')->with('flash_message_success','Product has been added in Cart!'); 
    }

    public function cart(){
        
        if(Auth::check()){
            $user_email = Auth::user()->email;
            $userCart = DB::table('cart')->where(['user_email'=>$user_email])->get();
        }else{
            $session_id = Session::get('session_id'); 
            $userCart = DB::table('cart')->where(['session_id'=>$session_id])->get();
        }
        
        foreach ($userCart as $key => $product) {
            $productDetails = Product::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }
        //echo "<pre>"; print_r($userCart); die;
        $meta_title = "Shopping Cart - MyShop";
        $meta_description = "View Shopping Cart of MyShop Website";
        $meta_keywords = "shopping cart, ecommerce website, myshop website";
        return \view('products.cart')->with(\compact('userCart','meta_title','meta_description','meta_keywords'));
    }

    public function deleteCartProduct($id=null){

        Session::forget('couponAmount');
        Session::forget('couponCode');

        //echo $id; die;
        DB::table('cart')->where('id',$id)->delete();
        return \redirect('cart')->with('flash_message_success','Product has been deleted from Cart!');
    }

    public function updateCartQuantity($id=null,$quantity=null){

        Session::forget('couponAmount');
        Session::forget('couponCode');

        $getCartDetails = DB::table('cart')->where('id',$id)->first();
        $getAttributeStock = ProductsAttribute::where('sku',$getCartDetails->product_code)->first();        
        $updated_quantity = $getCartDetails->quantity+$quantity;
        if($getAttributeStock->stock >= $updated_quantity){
            DB::table('cart')->where('id',$id)->increment('quantity',$quantity);
            return \redirect('cart')->with('flash_message_success','Product Quantity has been updated Successfully!');
        }else{
            return \redirect('cart')->with('flash_message_error','Required Product Quantity is not available!');
        }

        
    }

    public function applyCoupon(Request $request){

        Session::forget('couponAmount');
        Session::forget('couponCode');

        $data = $request->all();
        //echo "<pre>"; print_r($data); die;
        $couponCount = Coupon::where('coupon_code',$data['coupon_code'])->count();
        if ($couponCount==0) {
            return \redirect()->back()->with('flash_message_error','This Coupon does not exists');
        }else{
            $couponDetails = Coupon::where('coupon_code',$data['coupon_code'])->first();

            // If coupon is inactive
            if ($couponDetails->status==0) {
                return \redirect()->back()->with('flash_message_error','This Coupon is not active!');
            }

            // If coupon is expired
            $expiry_date = $couponDetails->expiry_date;
            $current_date = date('Y-m-d');
            if ($expiry_date < $current_date) {
                return \redirect()->back()->with('flash_message_error','This Coupon is expired!');
            }

            // Get Cart Total Amount
            $session_id = Session::get('session_id');
            //$userCart = DB::table('cart')->where(['session_id'=>$session_id])->get();

            if(Auth::check()){
                $user_email = Auth::user()->email;
                $userCart = DB::table('cart')->where(['user_email'=>$user_email])->get();
            }else{
                $session_id = Session::get('session_id'); 
                $userCart = DB::table('cart')->where(['session_id'=>$session_id])->get();
            }
            //echo "<pre>"; print_r($userCart); die;
            $total_amount = 0;
            foreach ($userCart as $item) {
                $total_amount = $total_amount + ($item->price * $item->quantity);
            }
            
            // Check if amount type is Fixed or Percentage
            if ($couponDetails->amount_type=="Fixed") {
                $couponAmount = $couponDetails->amount;
            }else{
                $couponAmount = $total_amount * ($couponDetails->amount/100);
            }
            //echo $couponAmount; die;

            // Add Coupon Code & Amount in Session
            Session::put('couponAmount',$couponAmount);
            Session::put('couponCode',$data['coupon_code']);

            return \redirect()->back()->with('flash_message_success','Coupon Code Successfully applied. You are availing discount!');
        }
    }

    public function checkout(Request $request){
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::find($user_id);
        $countries = Country::get();

        // Check if Shipping Address exists
        $shippingCount = DeliveryAddress::where('user_id',$user_id)->count();
        $shippingDetails = array();
        if ($shippingCount>0) {
            $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();
        }

        // Update cart table with user email
        $session_id = Session::get('session_id');
        DB::table('cart')->where(['session_id'=>$session_id])->update(['user_email'=>$user_email]);

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

            //Return to Checkout Page if any of the field is empty
            if (empty($data['billing_name']) ||
                empty($data['billing_surname']) ||
                empty($data['billing_address']) ||
                empty($data['billing_city']) ||
                empty($data['billing_state']) ||
                empty($data['billing_country']) ||
                empty($data['billing_pincode']) ||
                empty($data['billing_mobile']) ||
                empty($data['shipping_name']) ||
                empty($data['shipping_surname']) ||
                empty($data['shipping_address']) ||
                empty($data['shipping_city']) ||
                empty($data['shipping_state']) ||
                empty($data['shipping_country']) ||
                empty($data['shipping_pincode']) ||
                empty($data['shipping_mobile'])) {
                return \redirect()->back()->with('flash_message_error','Please fill all fields to Checkout.');
            }

            // Update User Details
            User::where('id',$user_id)->update(['name'=>$data['billing_name'],
                                                'surname'=>$data['billing_surname'],
                                                'address'=>$data['billing_address'],
                                                'city'=>$data['billing_city'],
                                                'state'=>$data['billing_state'],
                                                'country'=>$data['billing_country'],
                                                'pincode'=>$data['billing_pincode'],
                                                'mobile'=>$data['billing_mobile']]);  
            if ($shippingCount>0){
                // Update Shipping Address
                DeliveryAddress::where('user_id',$user_id)->update(['name'=>$data['shipping_name'],
                                                                    'surname'=>$data['shipping_surname'],
                                                                    'address'=>$data['shipping_address'],
                                                                    'city'=>$data['shipping_city'],
                                                                    'state'=>$data['shipping_state'],
                                                                    'country'=>$data['shipping_country'],
                                                                    'pincode'=>$data['shipping_pincode'],
                                                                    'mobile'=>$data['shipping_mobile']]);
            }else{
                // Add New Shipping Address
                $shipping = new DeliveryAddress;
                $shipping->user_id = $user_id;
                $shipping->user_email = $user_email;
                $shipping->name = $data['shipping_name'];
                $shipping->surname = $data['shipping_surname'];
                $shipping->address = $data['shipping_address'];
                $shipping->city = $data['shipping_city'];
                $shipping->state = $data['shipping_state'];
                $shipping->country = $data['shipping_country'];
                $shipping->pincode = $data['shipping_pincode'];
                $shipping->mobile = $data['shipping_mobile'];
                $shipping->save();
            } 
           
            $postalcodeCount = DB::table('postal_codes')->where('postal_code',$data['shipping_pincode'])->count();
            if ($postalcodeCount == 0) {
                return \redirect()->back()->with('flash_message_error','Your location is not available for delivery.
                            Please enter another location.');
            }

            return \redirect()->action('ProductController@orderReview');
        }

        $meta_title = "Checkout - ECommerce";
        return \view('products.checkout')->with(\compact('userDetails','countries','shippingDetails','meta_title'));
    }

    public function orderReview(){
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::where('id',$user_id)->first();
        $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();
        $shippingDetails = \json_decode(\json_encode($shippingDetails));
        //echo "<pre>"; print_r($shippingDetails); die;
        $userCart = DB::table('cart')->where(['user_email'=>$user_email])->get();
        $total_weight = 0;
        foreach ($userCart as $key => $product) {
            $productDetails = Product::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
            $total_weight = $total_weight + $productDetails->weight;
        }
        // echo $total_weight; die;
        //echo "<pre>"; print_r($userCart); die;
        $codpostalcodeCount = DB::table('cod_postal_codes')->where('postal_code',$shippingDetails->pincode)->count();
        $prepaidpostalcodeCount = DB::table('prepaid_postal_codes')->where('postal_code',$shippingDetails->pincode)->count();

        // Fetch Shipping Charges
        $shippingCharges = Product::getShippingCharges($total_weight,$shippingDetails->country);
        Session::put('ShippingCharges',$shippingCharges);

        $meta_title = "Order Review - ECommerce";
        return \view('products.order_review')->with(\compact('userDetails','shippingDetails','userCart','meta_title',
                'codpostalcodeCount','prepaidpostalcodeCount','shippingCharges'));
    }

    public function placeOrder(Request $request){     
        if($request->isMethod('post')){
            $data = $request->all();
            $user_id = Auth::user()->id;
            $user_email = Auth::user()->email;

            // Prevent Out of Stock Products from ordering
            $userCart = DB::table('cart')->where('user_email',$user_email)->get();
            // $userCart = \json_decode(\json_encode($userCart));
            // echo "<pre>"; print_r($userCart); die;
            foreach ($userCart as $cart) {

                $getAttributeCount = Product::getAttributeCount($cart->product_id,$cart->size);
                if ($getAttributeCount==0) {
                    Product::deleteCartProduct($cart->product_id,$user_email);                    
                    return \redirect('/cart')->with('flash_message_error','One of the product is
                                not available. Try again.');
                }

                $product_stock = Product::getProductStock($cart->product_id,$cart->size);
                if ($product_stock==0) {
                    Product::deleteCartProduct($cart->product_id,$user_email);                    
                    return \redirect('/cart')->with('flash_message_error','Sold out product
                                        removed from Cart. Please try placing order again.');
                }
                // echo 'Orignal Stock: '.$product_stock;
                // echo 'Demanded Stock: '.$cart->quantity; die;
                if ($cart->quantity>$product_stock) {
                    return \redirect('/cart')->with('flash_message_error','Reduce Product Stock and try again.');
                }

                $product_status = Product::getProductStatus($cart->product_id);
                if ($product_status==0) {
                    Product::deleteCartProduct($cart->product_id,$user_email);
                    return \redirect('/cart')->with('flash_message_error','Disabled product
                                        removed from Cart. Please try placing order again.');
                }

                $getCategoryId = Product::select('category_id')->where('id',$cart->product_id)->first();
                // echo $getCategoryId->category_id; die;
                $category_status = Product::getCategoryStatus($getCategoryId->category_id);
                if ($category_status==0) {
                    Product::deleteCartProduct($cart->product_id,$user_email);
                    return \redirect('/cart')->with('flash_message_error','One of the product category
                                    is disabled. Please try placing order again.');
                }
                
            }

            // Get Shipping Address of User
            $shippingDetails = DeliveryAddress::where(['user_email' => $user_email])->first();
            //$shipping = \json_decode(json_encode($shipping));
            //echo "<pre>"; print_r($shipping); die;

            $postalcodeCount = DB::table('postal_codes')->where('postal_code',$shippingDetails->pincode)->count();
            if ($postalcodeCount == 0) {
                return \redirect()->back()->with('flash_message_error','Your location is not available for delivery.
                            Please enter another location.');
            }

            //echo "<pre>"; print_r($data); die;
            if(empty(Session::get('couponCode'))){
                $coupon_code = '';
            }else{
                $coupon_code = Session::get('couponCode');                
            }

            if(empty(Session::get('couponAmount'))){
                $coupon_amount = '';
            }else{
                $coupon_amount = Session::get('couponAmount');
            }

            // Fetch Shipping Charges
            // $shippingCharges = Product::getShippingCharges($shippingDetails->country);
           
            $grand_total = Product::getGrandTotal();
            // Session::put('grand_total',$grand_total);

            
            $order = new Order;
            $order->user_id = $user_id;
            $order->user_email = $user_email;
            $order->name = $shippingDetails->name;
            $order->surname = $shippingDetails->surname;
            $order->address = $shippingDetails->address;
            $order->city = $shippingDetails->city;
            $order->state = $shippingDetails->state;
            $order->pincode = $shippingDetails->pincode;
            $order->country = $shippingDetails->country;
            $order->mobile = $shippingDetails->mobile;
            $order->coupon_code = $coupon_code;
            $order->coupon_amount = $coupon_amount;
            $order->order_status = "New";
            $order->payment_method = $data['payment_method'];
            $order->shipping_charges = Session::get('ShippingCharges');
            $order->grand_total = $data['grand_total'];
            $order->save();

            $order_id = DB::getPdo()->lastInsertId();

            $cartProducts = DB::table('cart')->where(['user_email'=>$user_email])->get();
            foreach ($cartProducts as $pro) {
                $cartPro = new OrdersProduct;
                $cartPro->order_id = $order_id;
                $cartPro->user_id = $user_id;
                $cartPro->product_id = $pro->product_id;
                $cartPro->product_code = $pro->product_code;
                $cartPro->product_name = $pro->product_name;
                $cartPro->product_color = $pro->product_color;
                $cartPro->product_size = $pro->size;
                $product_price = Product::getProductPrice($pro->product_id,$pro->size);
                $cartPro->product_price = $product_price;
                // $cartPro->product_price = $pro->price;
                $cartPro->product_quantity = $pro->quantity;
                $cartPro->save();

                // Reduce Stock Starts
                $getProductStock = ProductsAttribute::where('sku',$pro->product_code)->first();
                echo "Original Stock: ".$getProductStock->stock;
                echo "Stock to reduce: ".$pro->quantity; 
                $newStock = $getProductStock->stock - $pro->quantity;
                if ($newStock<0) {
                    $newStock = 0;
                }
                ProductsAttribute::where('sku',$pro->product_code)->update(['stock'=>$newStock]);
                // Reduce Stock Ends
            }
            Session::put('order_id',$order_id);
            // Session::put('grand_total',$data['grand_total']);
            Session::put('grand_total',$grand_total);
            Session::put('payment_method',$data['payment_method']);

            if($data['payment_method']=="COD"){

                $productDetails = Order::with('orders')->where('id',$order_id)->first();
                $productDetails = \json_decode(\json_encode($productDetails),true);
                //echo "<pre>"; print_r($productDetails); die;

                $userDetails = User::where('id',$user_id)->first();
                $userDetails = \json_decode(\json_encode($userDetails),true);
                //echo "<pre>"; print_r($userDetails); die;

                /* Code for Order Email Start */
                $email = $user_email;
                $messageData = [
                    'email'=>$email,
                    'name'=>$shippingDetails->name,
                    'surname'=>$shippingDetails->surname,
                    'order_id' => $order_id,
                    'productDetails' => $productDetails,
                    'userDetails' => $userDetails
                ];
                Mail::send('emails.order',$messageData,function($message) use($email){
                    $message->to($email)->subject('Order Placed - MyShop');
                    $message->from('mile.javakv@gmail.com','MyShop');
                });
                /* Code for Order Email End */





                return \redirect('/thanks');
            }else{
                return \redirect('/paypal');
            }
            
        }
    }

    public function thanks(Request $request){
        Session::forget('couponAmount');
        Session::forget('couponCode');

        $user_email = Auth::user()->email;
        DB::table('cart')->where('user_email',$user_email)->delete();

        $meta_title = "COD - MyShop";

        return \view('orders.thanks');
    }

    public function userOrders(){
        $user_id = Auth::user()->id;
        $orders = Order::with('orders')->where('user_id',$user_id)->orderBy('id','DESC')->get();
        //$orders = \json_decode(\json_encode($orders));
        //echo "<pre>"; print_r($orders); die;
        return \view('orders.user_orders')->with(\compact('orders'));
    }

    public function userOrderDetails($order_id){
        if(Session::get('adminDetails')['orders_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        $user_id = Auth::user()->id;
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = \json_decode(\json_encode($orderDetails));
        //echo "<pre>"; print_r($orderDetails); die;
        return \view('orders.user_order_details')->with(compact('orderDetails'));
    }

    public function viewOrders(){
        if(Session::get('adminDetails')['orders_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        $orders = Order::with('orders')->orderBy('id','Desc')->get();
        $orders = \json_decode(\json_encode($orders));
        //echo "<pre>"; print_r($orders); die;
        return view('admin.orders.view_orders')->with(\compact('orders'));
    }

    public function viewOrderDetails($order_id){
        if(Session::get('adminDetails')['orders_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = \json_decode(\json_encode($orderDetails));
        //echo "<pre>"; print_r($orderDetails); die;
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id',$user_id)->first();
        //$userDetails = \json_decode(\json_encode($userDetails));
        //echo "<pre>"; print_r($userDetails); die;
        return \view('admin.orders.order_details')->with(\compact('orderDetails','userDetails'));
    }

    public function viewOrderInvoice($order_id){
        if(Session::get('adminDetails')['orders_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        $orderDetails = \json_decode(\json_encode($orderDetails));
        //echo "<pre>"; print_r($orderDetails); die;
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id',$user_id)->first();
        //$userDetails = \json_decode(\json_encode($userDetails));
        //echo "<pre>"; print_r($userDetails); die;
        return \view('admin.orders.order_invoice')->with(\compact('orderDetails','userDetails'));
    }

    public function updateOrderStatus(Request $request){
        if(Session::get('adminDetails')['orders_access']==0){
            return \redirect('/admin/dashboard')->with('flash_message_error',
               'You have no access for this module!');
        }

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            Order::where('id',$data['order_id'])->update(['order_status'=>$data['order_status']]);
            return \redirect()->back()->with('flash_message_success','Order Status has been updated Successfully.');
        }
    }

    public function paypal(Request $request){        
        $user_email = Auth::user()->email;
        DB::table('cart')->where('user_email',$user_email)->delete();

        $meta_title = "PayPal - MyShop";

        return \view('orders.paypal');
    }

    public function thanksPaypal(Request $request){
        $meta_title = "PayPal - MyShop";

        return \view('orders.thanks_paypal');
    }

    public function cancelPaypal(){
        $meta_title = "PayPal - MyShop";

        return \view('orders.cancel_paypal');
    }

    public function searchProducts(Request $request){
        if($request->isMethod('post')){
            $data = $request->all(); 
            //echo "<pre>"; print_r($data); die;

            $categories = Category::with('categories')->where(['parent_id'=>0])->get();

            $search_product = $data['product'];

            /*$productsAll = Product::where('product_name','like','%'.$search_product.'%')->
                    orwhere('product_code',$search_product)->where('status',1)->get();*/

            $productsAll = Product::where(function($query) use($search_product){
                $query->where('product_name','like','%'.$search_product.'%')
                ->orwhere('product_code','like','%'.$search_product.'%')
                ->orwhere('description','like','%'.$search_product.'%')
                ->orwhere('product_color','like','%'.$search_product.'%');               
            })->where('status',1)->get();

            $breadcrumb = "<a href='/'>Home</a> / ".$search_product;
            
            $banners = Banner::where('status','1')->get();

            return view('products.listing')->with(\compact('categories','productsAll',
                                                    'search_product','banners','breadcrumb'));
        }
    }

    public function checkPostalCode(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $postalcodeCount = DB::table('postal_codes')->where('postal_code',$data['postal_code'])->count();
            if ($postalcodeCount > 0) {
                echo "This Postal Code is available for delivery";
            }else{
                echo "This Postal Code is not available for delivery";
            }
        }
    }
}
