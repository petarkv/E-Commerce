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
            <h3>YOUR <span style="color: red;">PAYPAL</span> ORDER HAS BEEN PLACED</h3>
            <p>Thanks for the payment. We will process Your order very soon.</p>
            <p>Your order number is #<span style="color: red; font-style: oblique">{{ Session::get('order_id') }}</span> and total amount paid is
                 EUR <span style="color: red; font-style: oblique">{{ Session::get('grand_total') }}</span></p>
        </div>
    </div>
</section>

@endsection

<?php
Session::forget('grand_total');
Session::forget('order_id');
Session::forget('payment_method');
?>