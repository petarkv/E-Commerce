@extends('layouts.frontLayout.front_design')
@section('content')
    
<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
              <li><a href="{{ url('/') }}">Home</a></li>
              <li><a href="{{ url('/orders') }}">Orders</a></li>
              <li class="active">{{ $orderDetails->id }}</li>
            </ol>
        </div>               
    </div>
</section>

<section id="do_action">
    <div class="container">
        <div class="heading" align="center">
            
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Product Size</th>
                        <th>Product Color</th>
                        <th>Product Price</th>
                        <th>Product Quantity</th>                              
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderDetails->orders as $product)
                    <tr>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->product_size }}</td>
                        <td>{{ $product->product_color }}</td>
                        <td>{{ $product->product_price }}</td>
                        <td>{{ $product->product_quantity }}</td>                    
                    </tr>
                    @endforeach                  
                </tbody>                
            </table>
        </div>
    </div>    
</section>

@endsection
