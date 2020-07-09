<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<div class="container">
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<h2>ECommerce Invoice</h2><h3 class="pull-right">Order # {{ $orderDetails->id }}</h3>
    		</div>
    		<hr>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    				<strong>Billed To:</strong><br>
                    {{ $userDetails->name }} {{ $userDetails->surname }}<br>
                    {{ $userDetails->address }}<br>
                    {{ $userDetails->city }}<br>
                    {{ $userDetails->state }}<br>
                    {{ $userDetails->country }}<br>
                    {{ $userDetails->pincode }}<br>
                    {{ $userDetails->mobile }}<br>
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
        			<strong>Shipped To:</strong><br>
                    {{ $orderDetails->name }} {{ $orderDetails->surname }}<br>
                    {{ $orderDetails->address }}<br>
                    {{ $orderDetails->city }}<br>
                    {{ $orderDetails->state }}<br>
                    {{ $orderDetails->country }}<br>
                    {{ $orderDetails->pincode }}<br>
                    {{ $orderDetails->mobile }}<br>
    				</address>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    					<strong>Payment Method:</strong><br>
    					{{ $orderDetails->payment_method }}
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
    					<strong>Order Date:</strong><br>
    					{{ $orderDetails->created_at }}<br><br>
    				</address>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Order summary</strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<td><strong>Product Code</strong></td>
        							<td class="text-center"><strong>Product Name</strong></td>
                                    <td class="text-center"><strong>Product Size</strong></td>
                                    <td class="text-center"><strong>Product Color</strong></td>
                                    <td class="text-center"><strong>Product Price</strong></td>
                                    <td class="text-center"><strong>Product Quantity</strong></td>
        							<td class="text-right"><strong>Totals</strong></td>
                                </tr>
    						</thead>
    						<tbody>
                                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                <?php $subtotal = 0; ?>
                                @foreach($orderDetails->orders as $product)
    							<tr>
    								<td>{{ $product->product_code }}</td>
    								<td class="text-center">{{ $product->product_name }}</td>
                                    <td class="text-center">{{ $product->product_size }}</td>
                                    <td class="text-center">{{ $product->product_color }}</td>
                                    <td class="text-center">{{ $product->product_price }}</td>
                                    <td class="text-center">{{ $product->product_quantity }}</td>
    								<td class="text-right">EUR {{ $product->product_price * $product->product_quantity }}</td>
                                </tr>
                                <?php $subtotal = $subtotal + ($product->product_price * $product->product_quantity); ?>
                                @endforeach
    							<tr>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line text-center"><strong>Subtotal</strong></td>
    								<td class="thick-line text-right">EUR {{ $subtotal }}</td>
    							</tr>
    							<tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Shipping Charges (+)</strong></td>
    								<td class="no-line text-right">EUR {{ $orderDetails->shipping_charges }}</td>
                                </tr>
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Coupon Discount (-)</strong></td>
    								<td class="no-line text-right">EUR {{ $orderDetails->coupon_amount }}</td>
    							</tr>
    							<tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Total</strong></td>
    								<td class="no-line text-right">EUR {{ $orderDetails->grand_total }}</td>
    							</tr>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>

<style>
    .invoice-title h2, .invoice-title h3 {
    display: inline-block;
}

.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 2px solid;
}
</style>