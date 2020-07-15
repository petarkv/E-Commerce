@extends('layouts.adminLayout.admin_design')
@section('content')
    
  <div id="content">
    <div id="content-header">
      <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom">
          <i class="icon-home"></i> Home</a> <a href="{{ url('/admin/view-products') }}">Products</a> 
          <a href="" class="current">Add Product</a> </div>
      <h1>Products</h1>

    @if (Session::has('flash_message_error'))                
      <div class="alert alert-error alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>                
        <strong>{!! session('flash_message_error') !!}</strong>                
      </div>
    @endif 
          
    @if (Session::has('flash_message_success'))                
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>                
        <strong>{!! session('flash_message_success') !!}</strong>                
      </div>
    @endif

    </div>
    <div class="container-fluid"><hr>
      <div class="row-fluid">
        <div class="span12">
          <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
              <h5>Add Product</h5>
            </div>
            <div class="widget-content nopadding">
              <form class="form-horizontal" method="post" action="{{ url('/admin/add-product') }}" 
                    name="add_product" id="add_product" novalidate="novalidate"
                    enctype="multipart/form-data">{{ csrf_field() }}                    

                <div class="control-group" id="main_category">
                    <label class="control-label">Sub Category</label>
                    <div class="controls">
                        <select name="category_id" id="category_id">
                            <?php echo $categories_dropdown; ?>                      
                        </select>
                    </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Product Name</label>
                  <div class="controls">
                    <input type="text" name="product_name" id="product_name">
                  </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Product Code</label>
                    <div class="controls">
                      <input type="text" name="product_code" id="product_code">
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Product Color</label>
                    <div class="controls">
                      <input type="text" name="product_color" id="product_color">
                    </div>
                </div>
      
                <div class="control-group">
                    <label class="control-label">Description</label>
                    <div class="controls">
                      <textarea name="description" id="description" class="textarea_editor span8"></textarea>
                    </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Material & Care</label>
                  <div class="controls">
                    <textarea name="care" id="care" class="textarea_care span8"></textarea>
                  </div>
                </div>

                <div class="control-group" style="width: 445px;">
                  <label class="control-label">Sleeve</label>
                  <div class="controls">
                    <select name="sleeve" class="form-control">
                      <option value="">Select Sleeve</option>
                      @foreach ($sleeveArray as $sleeve)
                        <option value="{{ $sleeve }}">{{ $sleeve }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="control-group" style="width: 445px;">
                  <label class="control-label">Pattern</label>
                  <div class="controls">
                    <select name="pattern">
                      <option value="">Select Pattern</option>
                      @foreach($patternArray as $pattern)
                        <option value="{{ $pattern }}">{{ $pattern }}</option>
                      @endforeach
                    </select>  
                  </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Price</label>
                    <div class="controls">
                      <input type="text" name="price" id="price">
                    </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Product Weight (g)</label>
                  <div class="controls">
                    <input type="text" name="product_weight" id="product_weight">
                  </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Image</label>
                    <div class="controls">
                      <input type="file" name="image" id="image">
                    </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Video</label>
                  <div class="controls">
                    <input type="file" name="video" id="video">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Feature Item</label>
                  <div class="controls">
                    <input type="checkbox" name="feature_item" id="feature_item" value="1">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Enable</label>
                  <div class="controls">
                    <input type="checkbox" name="status" id="status" value="1">
                  </div>
                </div>
                
                <div class="form-actions">
                  <input type="submit" value="Add Product" class="btn btn-success">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>      
    </div>
  </div>

@endsection