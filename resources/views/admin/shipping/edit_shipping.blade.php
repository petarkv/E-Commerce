@extends('layouts.adminLayout.admin_design')
@section('content')
    
  <div id="content">
    <div id="content-header">
      <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom">
          <i class="icon-home"></i> Home</a> <a href="{{ url('/admin/view-shipping') }}">Shipping</a> 
          <a href="" class="current">Edit Shipping</a> </div>
      <h1>Shipping Charges</h1>
    </div>

    @if (Session::has('flash_message_success'))                
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>                
        <strong>{!! session('flash_message_success') !!}</strong>                
      </div>
    @endif

    <div class="container-fluid"><hr>
      <div class="row-fluid">
        <div class="span12">
          <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
              <h5>Edit Shipping Charges</h5>
            </div>
            <div class="widget-content nopadding">
              <form class="form-horizontal" method="post" action="{{ url('/admin/edit-shipping/'.$shippingDetails->id) }}" 
                    name="edit_shipping" id="edit_shipping" novalidate="novalidate">{{ csrf_field() }} 
                    
                <input type="hidden" name="id" value="{{ $shippingDetails->id }}">

                <div class="control-group">
                    <label class="control-label" style="width: 220px; margin-right: 20px;">Country</label>
                    <div class="controls">
                        <input readonly type="text" value="{{ $shippingDetails->country_name }}">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" style="width: 220px; margin-right: 20px;">Country Code</label>
                    <div class="controls">
                        <input readonly type="text" value="{{ $shippingDetails->country_code }}">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" style="width: 220px; margin-right: 20px;">Shipping Charges (0-1000g)</label>
                    <div class="controls">
                        <input type="text" name="shipping_charges0_1000g" id="shipping_charges0_1000g" 
                        value="{{ $shippingDetails->shipping_charges0_1000g }}"> EUR
                    </div>
                </div>

                <div class="control-group">
                  <label class="control-label" style="width: 220px; margin-right: 20px;">Shipping Charges (1001-3000g)</label>
                  <div class="controls">
                      <input type="text" name="shipping_charges1001_3000g" id="shipping_charges1001_3000g" 
                      value="{{ $shippingDetails->shipping_charges1001_3000g }}"> EUR
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" style="width: 220px; margin-right: 20px;">Shipping Charges (3001-5000g)</label>
                  <div class="controls">
                      <input type="text" name="shipping_charges3001_5000g" id="shipping_charges3001_5000g" 
                      value="{{ $shippingDetails->shipping_charges3001_5000g }}"> EUR
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" style="width: 220px; margin-right: 20px;">Shipping Charges (5001-10000g)</label>
                  <div class="controls">
                      <input type="text" name="shipping_charges5001_10000g" id="shipping_charges5001_10000g" 
                      value="{{ $shippingDetails->shipping_charges5001_10000g }}"> EUR
                  </div>
                </div>
                
                <div class="form-actions">
                  <input type="submit" value="Edit Shipping" class="btn btn-success">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>      
    </div>
  </div>

@endsection