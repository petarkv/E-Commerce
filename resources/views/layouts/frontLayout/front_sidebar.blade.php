<?php use App\Product; ?>
<form action="{{ url('/products-filter') }}" method="POST">{{ csrf_field() }}
@if(!empty($url))
<input type="hidden" name="url" value="{{ $url }}">
@endif
<div class="left-sidebar">
    <h2>Category</h2>
    <div class="panel-group category-products" id="accordian"><!--category-productsr-->
        @foreach ($categories as $cat)
        <div class="panel panel-default">
            <?php //echo $categories_menu; ?>            
            <div class="panel-heading">                                
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordian" href="#{{ $cat->id }}">
                        <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                        {{ $cat->name }}
                    </a>
                </h4> 
            </div>
            <div id="{{ $cat->id }}" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>
                        @foreach ($cat->categories as $subcat)
                            <?php $productCount = Product::productCount($subcat->id); ?>
                            @if($subcat->status==1)
                                <li><a href="{{ asset('/products/'.$subcat->url) }}">
                                        {{ $subcat->name }}</a> ({{ $productCount }})</li>
                            @endif     
                        @endforeach                                                                             
                    </ul>
                </div>
            </div>        
        </div>
        @endforeach
    </div><!--/category-products-->

    @if(!empty($url))
    <h2>Colors</h2>
    <div class="panel-group" style="margin-bottom: 30px;"><!--color-productsr-->
        
        @foreach($colorArray as $color)
        @if(!empty($_GET['color']))
            <?php $colorArr = explode('-',$_GET['color']) ?>
            @if(in_array($color,$colorArr))
                <?php $colorcheck="checked"; ?>
            @else
                <?php $colorcheck=""; ?>
            @endif
        @else
            <?php $colorcheck=""; ?>
        @endif
        <div class="panel panel-default">                
            <div class="panel-heading">                                
                <h4 class="panel-title">                    
                    <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="{{ $color }}" type="checkbox" 
                    value="{{ $color }}" {{ $colorcheck }}>
                    &nbsp;&nbsp;<span class="products-color">{{ $color }}</span>
                </h4> 
            </div>                  
        </div>
        @endforeach        
    </div>

    <h2>Sleeve</h2>
    <div class="panel-group" style="margin-bottom: 20px;"><!--color-productsr-->        
        @foreach($sleeveArray as $sleeve)
        @if(!empty($_GET['sleeve']))
            <?php $sleeveArr = explode('-',$_GET['sleeve']) ?>
            @if(in_array($sleeve,$sleeveArr))
                <?php $sleevecheck="checked"; ?>
            @else
                <?php $sleevecheck=""; ?>
            @endif
        @else
            <?php $sleevecheck=""; ?>
        @endif
        <div class="panel panel-default">                
            <div class="panel-heading">                                
                <h4 class="panel-title">                    
                    <input name="sleeveFilter[]" onchange="javascript:this.form.submit();" id="{{ $sleeve }}" type="checkbox" 
                    value="{{ $sleeve }}" {{ $sleevecheck }}>
                    &nbsp;&nbsp;<span class="products-sleeve">{{ $sleeve }}</span>
                </h4> 
            </div>                  
        </div>
        @endforeach        
    </div>

    @endif

</div>
</form>