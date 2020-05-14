<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;

class CouponsController extends Controller
{
    public function addCoupon(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $coupon = new Coupon;
            $coupon->coupon_code = $data['coupon_code'];
            $coupon->amount = $data['amount'];
            $coupon->amount_type = $data['amount_type'];
            $coupon->expiry_date = $data['expiry_date'];
            $coupon->status = $data['status'];
            $coupon->save();
            return \redirect()->action('CouponsController@viewCoupons')->with('flash_message_success','Coupon has been added!');
        }
        return \view('admin.coupons.add_coupon');
    }

    public function viewCoupons(){
        $coupons = Coupon::get();
        return \view('admin.coupons.view_coupons')->with(\compact('coupons'));
    }


}
