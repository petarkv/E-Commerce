@extends('layouts.frontLayout.front_design')
@section('content')
<?php use App\Product; ?>    
<section>
    <div class="container">
        <div class="row">

        @if (Session::has('flash_message_error'))                
            <div class="alert alert-error alert-block" style="background-color: #f2dfd0">
                <button type="button" class="close" data-dismiss="alert">×</button>                
                <strong>{!! session('flash_message_error') !!}</strong>                
            </div>
        @endif
        
            <div class="col-sm-3">
                @include('layouts.frontLayout.front_sidebar')
            </div>
            
            <div class="col-sm-9 padding-right">
                <div class="product-details"><!--product-details-->
                    <div class="col-sm-5">
                        <div class="view-product">
                            <div class="easyzoom easyzoom--overlay easyzoom--with-thumbnails">
                                <a href="{{ asset('images/template_images/products/large/'.$productDetails->image) }}">
                                    <img style="width: 350px;" class="mainImage" src="{{ asset('images/template_images/products/medium/'.$productDetails->image) }}"
                                                                     alt="" />
                                </a>
                            <!--h3>ZOOM</h3-->
                            </div>
                        </div>
                        <div id="similar-product" class="carousel slide" data-ride="carousel"  style="margin-top: 255px;">
                            
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                @if(count($productAltImages)>0)
                                <div class="item active thumbnails">                                        
                                <img style="width: 80px; cursor: pointer;" class="changeImage" src="{{ asset('images/template_images/products/medium/'
                                                                    .$productDetails->image) }}" alt="" />
                                @foreach ($productAltImages as $altimage)
                                <a href="{{ asset('images/template_images/products/large/'.$altimage->image) }}" 
                                    data-standard="{{ asset('images/template_images/products/medium/'.$altimage->image) }}">
                                    <img class="changeImage" style="width: 80px; cursor: pointer;" 
                                            src="{{ asset('images/template_images/products/small/'.$altimage->image) }}" 
                                            alt="">
                                        
                                </a>
                                @endforeach                                                                            
                                </div> 
                                @endif                                 
                            </div>                                                 
                        </div>

                    </div>
                    <div class="col-sm-7">
                        <form name="addtocartForm" id="addtocartForm" action="{{ url('add-cart') }}" method="POST">{{ csrf_field() }}
                        <div class="product-information" style="margin-top: -50px;"><!--/product-information-->
                            <div align="left"><?php echo $breadcrumb; ?></div><!-- BREADCRUMB -->
                            <div>&nbsp;</div><!-- BREADCRUMB -->
                            <input type="hidden" name="product_id" value="{{ $productDetails->id }}">
                            <input type="hidden" name="product_name" value="{{ $productDetails->product_name }}">
                            <input type="hidden" name="product_code" value="{{ $productDetails->product_code }}">
                            <input type="hidden" name="product_color" value="{{ $productDetails->product_color }}">
                            <input type="hidden" name="price" id="price" value="{{ $productDetails->price }}">
                           
                            <img src="images/product-details/new.jpg" class="newarrival" alt="" />
                            <h2>{{ $productDetails->product_name }}</h2>
                            <p>Code: {{ $productDetails->product_code }}</p>
                            <p>Color: {{ $productDetails->product_color }}</p>
                            @if(!empty($productDetails->sleeve))
                                <p>Sleeve: {{ $productDetails->sleeve }}</p>
                            @endif
                            @if(!empty($productDetails->pattern))
                                <p>Pattern: {{ $productDetails->pattern }}</p>
                            @endif

                            <p>
                                <select id="selSize" name="size" style="width: 150px">
                                    <option value="">Select Size</option>
                                    @foreach ($productDetails->attributes as $sizes)
                                        <option value="{{ $productDetails->id }}-{{ $sizes->size }}">{{ $sizes->size }}</option>                                        
                                    @endforeach
                                </select>
                            </p>
                            <img src="images/product-details/rating.png" alt="" />
                            <span>
                                <?php $getCurrencyRates = Product::getCurrencyRates($productDetails->price); ?>
                                <span id="getPrice">
                                    EUR {{ $productDetails->price }}<br>
                                    <h2>
                                        USD {{ $getCurrencyRates['USD_Rate'] }}<br>
                                        RSD {{ $getCurrencyRates['RSD_Rate'] }}<br>                                        
                                        CHF {{ $getCurrencyRates['CHF_Rate'] }}
                                    </h2>
                                    
                                </span>
                                <label>Quantity:</label>
                                <input type="text" name="quantity" value="1" />
                                @if($total_stock>0)
                                <button type="submit" class="btn btn-fefault cart" id="cartButton">
                                    <i class="fa fa-shopping-cart"></i>
                                    Add to cart
                                </button>
                                @endif
                            </span>
                            <p><b>Availability:</b><span id="Availability">@if($total_stock>0) In Stock @else Out of Stock @endif</p></span>
                            <p><b>Condition:</b> New</p>
                            <p><b>Delivery: </b>
                            <input type="text" name="postal_code" id="chkPostalCode" placeholder="Check Postal Code">
                            <button type="button" onclick="return checkPostalCode();">Go</button></p>
                            <span id="postalcodeResponse"></span>
                            
                            <!-- Share -->
                            <div class="sharethis-inline-share-buttons"></div>
                        </div><!--/product-information-->                        
                        </form>
                    </div>
                </div><!--/product-details-->
                
                <div class="category-tab shop-details-tab"><!--category-tab-->
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#description" data-toggle="tab">Description</a></li>
                            <li><a href="#care" data-toggle="tab">Material & Care</a></li>
                            <li><a href="#delivery" data-toggle="tab">Delivery Options</a></li>
                            @if(!empty($productDetails->video))
                            <li><a href="#video" data-toggle="tab">Product Video</a></li>
                            @endif
                            <li><a href="#reviews" data-toggle="tab">Reviews (5)</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="description" >
                            <div class="col-sm-12">
                                <p>{{ $productDetails->description }}</p>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="care" >
                            <div class="col-sm-12">
                                <p>{{ $productDetails->care }}</p>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="delivery" >
                            <div class="col-sm-12">
                                <p>100% Original Product <br>
                                    Cash on Delivery
                                </p>
                            </div>
                        </div>

                        @if(!empty($productDetails->video))
                        <div class="tab-pane fade" id="video" >
                            <div class="col-sm-12">
                                <video width="320" height="240" controls>
                                    <source src="{{ url('videos/'.$productDetails->video) }}" type="video/mp4">                                    
                                </video> 
                            </div>
                        </div>
                        @endif
                        
                        <div class="tab-pane fade" id="reviews" >
                            <div class="col-sm-12">
                                <ul>
                                    <li><a href=""><i class="fa fa-user"></i>EUGEN</a></li>
                                    <li><a href=""><i class="fa fa-clock-o"></i>12:41 PM</a></li>
                                    <li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li>
                                </ul>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                                <p><b>Write Your Review</b></p>
                                
                                <form action="#">
                                    <span>
                                        <input type="text" placeholder="Your Name"/>
                                        <input type="email" placeholder="Email Address"/>
                                    </span>
                                    <textarea name="" ></textarea>
                                    <b>Rating: </b> <img src="images/product-details/rating.png" alt="" />
                                    <button type="button" class="btn btn-default pull-right">
                                        Submit
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div><!--/category-tab-->
                
                <div class="recommended_items"><!--recommended_items-->
                    <h2 class="title text-center">recommended items</h2>
                    
                    <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php $count=1; ?>
                            @foreach($relatedProducts->chunk(3) as $chunk)
                        <div <?php if($count==1){ ?> class="item active" <?php } else { ?> class="item" <?php } ?>>
                                @foreach($chunk as $item)	
                                <div class="col-sm-4">
                                    <div class="product-image-wrapper">
                                        <div class="single-products">
                                            <div style="border: solid 1px;" class="productinfo text-center">
                                                <img style="width: 230px;" src="{{ asset('images/template_images/products/small/'.$item->image) }}" alt="" />
                                                <h2>EUR {{ $item->price }}</h2>
                                                <p>{{ $item->product_name }}</p>
                                                <a href="{{ url('product/'.$item->id) }}"><button type="button" class="btn btn-default add-to-cart">
                                                    <i class="fa fa-shopping-cart"></i>Add to cart</button></a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <?php $count++; ?>
                            @endforeach
                        </div>
                         <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                          </a>
                          <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                          </a>			
                    </div>
                </div><!--/recommended_items-->
                
            </div>
        </div>
    </div>
</section>

@endsection