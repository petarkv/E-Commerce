<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Banner;

class IndexController extends Controller
{
    public function index(){
        //$productsAll = Product::orderBy('id','DESC')->take(3)->get();
        $productsAll = Product::orderBy('id','DESC')->where('status',1)->where('feature_item',1)->paginate(6);

        //\dump($productsAll);
        // Get All Categories and Sub Categories
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();
        //$categories = \json_decode(\json_encode($categories));
        //echo print_r($categories); die;

        /*$categories_menu = "";
        foreach ($categories as $cat) {
            echo $cat->name; echo"<br>";

            $categories_menu .= "<div class='panel-heading'>
                                    <h4 class='panel-title'>
                                        <a data-toggle='collapse' data-parent='#accordian' href='#".$cat->id."'>
                                            <span class='badge pull-right'><i class='fa fa-plus'></i></span>
                                            ".$cat->name."
                                        </a>
                                    </h4>
                                </div>
                                <div id='".$cat->id."' class='panel-collapse collapse'>
                                <div class='panel-body'>
                                    <ul>";
                                    $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
                                    foreach ($sub_categories as $subcat) {                                    
                                    $categories_menu .= "<li><a href='#'>".$subcat->name."</a></li>";
                                    }
                                        
                $categories_menu .= "</ul>
                                </div>
                            </div>";            
        }*/

        $banners = Banner::where('status','1')->get();
        // Meta tags
        $meta_title = "MyShop Website";
        $meta_description = "Online Shopping Site for Men, Women and Kids Clothing";
        $meta_keywords = "eshop website, online shopping, men clothing, myshop website";
        return view('index')->with(\compact('productsAll','categories','banners',
            'meta_title','meta_description','meta_keywords'));       
    }
}
