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

    public static function getProductStock($product_id,$product_size){
        $getProductStock = ProductsAttribute::select('stock')->
        where(['product_id'=>$product_id,'size'=>$product_size])->first();
        return $getProductStock->stock;
    }

    public static function getProductPrice($product_id,$product_size){
        $getProductPrice = ProductsAttribute::select('price')->where(['product_id'=>$product_id,
        'size'=>$product_size])->first();
        return $getProductPrice->price;
    }

    public static function deleteCartProduct($product_id,$user_email){
        DB::table('cart')->where(['product_id'=>$product_id,'user_email'=>$user_email])->delete();
    }

    public static function getProductStatus($product_id){
        $getProductStatus = Product::select('status')->where('id',$product_id)->first();
        return $getProductStatus->status;
    }

    public static function getCategoryStatus($product_id){
        $getCategoryStatus = Category::select('status')->where('id',$product_id)->first();
        return $getCategoryStatus->status;
    }

    public static function getAttributeCount($product_id,$product_size){
        $getAttributeCount = ProductsAttribute::where(['product_id'=>$product_id,'size'=>$product_size])->count();
        return $getAttributeCount;
    }

    public static function getShippingCharges($total_weight,$country){
        $shippingDetails = ShippingCharge::where('country_name',$country)->first();
        // $shipping_charges = $shippingDetails->shipping_charges;
        if ($total_weight>0) {
            if ($total_weight>0 && $total_weight<=1000) {
                $shipping_charges = $shippingDetails->shipping_charges0_1000g;
            }else if ($total_weight>1000 && $total_weight<=3000) {
                $shipping_charges = $shippingDetails->shipping_charges1001_3000g 	;
            }else if ($total_weight>3000 && $total_weight<=5000) {
                $shipping_charges = $shippingDetails->shipping_charges3001_5000g;
            }else if ($total_weight>5000 && $total_weight<=10000) {
                $shipping_charges = $shippingDetails->shipping_charges5001_10000g;
            }else{
                $shipping_charges = 0;
            }
        }else{
            $shipping_charges = 0; 
        }
        return $shipping_charges;
    }

    public static function getGrandTotal(){
        $getGrandTotal = "";
        $username = Auth::user()->email;
        $userCart = DB::table('cart')->where('user_email',$username)->get();
        $userCart = \json_decode(\json_encode($userCart),true);
        // echo "<pre>"; print_r($userCart); die;
        foreach ($userCart as $product) {
            $productPrice = ProductsAttribute::where(['product_id'=>$product['product_id'],
                                                'size'=>$product['size']])->first();
            $priceArray[] = $productPrice->price;
        }
        // echo print_r($priceArray); die;
        $grandTotal = array_sum($priceArray) - Session::get('couponAmount') + Session::get('ShippingCharges');
        return $grandTotal;
    }
}
