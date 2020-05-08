@extends('layouts.frontLayout.front_design')
@section('content')
    
<section>
    <div class="container">
        <div class="row">
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
                        <div id="similar-product" class="carousel slide" data-ride="carousel">
                            
                              <!-- Wrapper for slides -->
                                <div class="carousel-inner">
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
                                 </div>

                              
                        </div>

                    </div>
                    <div class="col-sm-7">
                        <div class="product-information"><!--/product-information-->
                            <img src="images/product-details/new.jpg" class="newarrival" alt="" />
                            <h2>{{ $productDetails->product_name }}</h2>
                            <p>Code: {{ $productDetails->product_code }}</p>

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
                                <span id="getPrice">EUR {{ $productDetails->price }}</span>
                                <label>Quantity:</label>
                                <input type="text" value="3" />
                                @if($total_stock>0)
                                <button type="button" class="btn btn-fefault cart" id="cartButton">
                                    <i class="fa fa-shopping-cart"></i>
                                    Add to cart
                                </button>
                                @endif
                            </span>
                            <p><b>Availability:</b><span id="Availability">@if($total_stock>0) In Stock @else Out of Stock @endif</p></span>
                            <p><b>Condition:</b> New</p>                           
                            <a href=""><img src="{{ asset('images/main_images/product-details/share.png') }}" class="share img-responsive"  alt="" /></a>
                        </div><!--/product-information-->
                    </div>
                </div><!--/product-details-->
                
                <div class="category-tab shop-details-tab"><!--category-tab-->
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#description" data-toggle="tab">Description</a></li>
                            <li><a href="#care" data-toggle="tab">Material & Care</a></li>
                            <li><a href="#delivery" data-toggle="tab">Delivery Options</a></li>
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
                                                <a href="{{ url('product/'.$item->id) }}"><button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button></a>

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