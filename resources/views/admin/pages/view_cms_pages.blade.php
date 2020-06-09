@extends('layouts.adminLayout.admin_design')
@section('content')
    
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="{{ url("/admin/dashboard") }}" title="Go to Home" class="tip-bottom">
            <i class="icon-home"></i> Home</a> <a href="#">CMS Pages</a> 
            <a href="#" class="current">View CMS Pages</a> </div>
      <h1>CMS Pages</h1>

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
    <div class="container-fluid">
      <hr>
      <div class="row-fluid">
        <div class="span12">

          <div class="widget-box">
            <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
              <h5>View CMS Pages</h5>
            </div>
            <div class="widget-content nopadding">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>Page ID</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Created on</th>                    
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>

                    @foreach ($cmsPages as $cms)
                    <tr class="gradeX">
                        <td>{{ $cms->id }}</td>
                        <td>{{ $cms->title }}</td>
                        <td>{{ $cms->url }}</td>
                        <td>@if($cms->status == 1) Active @else Inactive @endif</td>
                        <td>{{ $cms->created_at }}</td>                                             
                        <td class="center">
                            <a href="#myModal{{ $cms->id }}" data-toggle="modal" 
                                class="btn btn-success btn-mini" title="View CMS Page">View</a>
                            <a href="{{ url('/admin/edit-cms-page/'.$cms->id) }}" 
                                class="btn btn-primary btn-mini" title="Edit CMS Page">Edit</a>
                            <a href="{{ url('/admin/delete-cms-page/'.$cms->id) }}" class="btn btn-danger btn-mini" 
                                title="Delete CMS Page">Delete</a></td>
                        </tr>                      
                      
                        <div id="myModal{{ $cms->id }}" class="modal hide">
                          <div class="modal-header">
                            <button data-dismiss="modal" class="close" type="button">×</button>
                            <h3>{{ $cms->title }} - Page Details</h3>
                          </div>
                          <div class="modal-body">
                            <p><strong>ID:</strong> {{ $cms->id }}</p>
                            <p><strong>Title:</strong> {{ $cms->title }}</p>
                            <p><strong>URL:</strong> {{ $cms->url }}</p>
                            <p><strong>Status:</strong> @if($cms->status == 1) Active @else Inactive @endif</p>
                            <p><strong>Created on:</strong> {{ $cms->created_at }}</p>
                            <p><strong>Description:</strong> {{ $cms->description }}</p>          
                          </div>
                        </div>                        

                    @endforeach
                                            
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection