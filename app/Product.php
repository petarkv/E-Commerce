<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Currency;
use Auth;
use Session;
use DB;

class Product extends Model
{
    public function attributes(){
        return $this->hasMany('App\ProductsAttribute','product_id');
    }

    public static function cartCount(){
        if (Auth::check()) {
            // User is logged in. We will use Auth.
            $user_email = Auth::user()->email;
            $cartCount = DB::table('cart')->where('user_email',$user_email)->sum('quantity');
        }else{
            // User is not logged in. We will use Session.
            $session_id = Session::get('session_id');
            $cartCount = DB::table('cart')->where('session_id',$session_id)->sum('quantity');
        }
        return $cartCount;
    }

    public static function productCount($cat_id){
        $categoryCount = Product::where(['category_id'=>$cat_id,'status'=>1])->count();
        return $categoryCount;
    }

    public static function getCurrencyRates($price){
        $getCurrencies = Currency::where('status',1)->get();
        foreach ($getCurrencies as $currency) {
            if($currency->currency_code == "USD"){
                $USD_Rate = round($price/$currency->exchange_rate,2);
            }else if($currency->currency_code == "RSD"){
                $RSD_Rate = round($price/$currency->exchange_rate,2);
            }else if($currency->currency_code == "CHF"){
                $CHF_Rate = round($price/$currency->exchange_rate,2);
            }            
        }
        $currenciesArr = array('USD_Rate'=>$USD_Rate,'RSD_Rate'=>$RSD_Rate,'CHF_Rate'=>$CHF_Rate);
        return $currenciesArr;
    }

    /*public static function getProductPrice($product_id,$product_size){
        $getProductPrice = ProductsAttribute::select('price')->where(['product_id'=>$product_id,'size'=>$product_size])->first();
        return $getProductPrice->price;
    }*/
}
