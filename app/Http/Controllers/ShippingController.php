<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShippingCharge;

class ShippingController extends Controller
{
    public function viewShipping(){
        $shipping_charges = ShippingCharge::get();
        // $shipping_charges = \json_decode($shipping_charges);
        // echo "<pre>"; print_r($shipping_charges); die;
        return \view('admin.shipping.view_shipping')->with(\compact('shipping_charges'));
    }

    public function editShipping($id,Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            ShippingCharge::where('id',$id)->update(['shipping_charges0_1000g'=>$data['shipping_charges0_1000g'],
                                                    'shipping_charges1001_3000g'=>$data['shipping_charges1001_3000g'],
                                                    'shipping_charges3001_5000g'=>$data['shipping_charges3001_5000g'],
                                                    'shipping_charges5001_10000g'=>$data['shipping_charges5001_10000g']]);
            return \redirect()->back()->with('flash_message_success','Shipping Charges updated Successfully!');
        }
        $shippingDetails = ShippingCharge::where('id',$id)->first();
        return \view('admin.shipping.edit_shipping')->with(\compact('shippingDetails'));
    }
}
