@extends('layouts.frontLayout.front_design')
@section('content')
    
<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
              <li><a href="{{ url('/') }}">Home</a></li>
              <li class="active">Thanks</li>
            </ol>
        </div>               
    </div>
</section>

<section id="do_action">
    <div class="container">
        <div class="heading" align="center">
            <h3>YOUR <span style="color: red;">PAYPAL</span> ORDER HAS BEEN CANCELED</h3>
            <p>Please contact us if there is any enquiry.</p>            
        </div>
    </div>
</section>

@endsection

<?php
Session::forget('grand_total');
Session::forget('order_id');
Session::forget('payment_method');
?>