<?php

$current_year = date('Y');
$current_month = date('M');

// foreach($numberCountries as $k){
// $dataPoints = array(    
// array("y" => $getUsersCountries[$k]['count'], "label" => $getUsersCountries[$k]['country']),
// )};

$dataPoints = array(
	array("y" => $getUsersCountries[0]['count'], "label" => $getUsersCountries[0]['country']),
	array("y" => $getUsersCountries[1]['count'], "label" => $getUsersCountries[1]['country']),
	array("y" => $getUsersCountries[2]['count'], "label" => $getUsersCountries[2]['country']),
	array("y" => $getUsersCountries[3]['count'], "label" => $getUsersCountries[3]['country']),
	
);
 
?>

@extends('layouts.adminLayout.admin_design')
@section('content')

<script>
    window.onload = function() {
     
     
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
            text: "Registered Users Count Countries"
        },
        subtitles: [{
            text: "<?php echo $current_month; echo $current_year; ?>"        
        }],
        data: [{
            type: "pie",
            //yValueFormatString: "#,##0.00\"%\"",
            indexLabel: "{label} ({y})",
            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();
     
    }
    </script>
    
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="{{ url("/admin/dashboard") }}" title="Go to Home" class="tip-bottom">
            <i class="icon-home"></i> Home</a> <a href="">Users</a> 
            <a href="{{ url('/admin/view-orders') }}" class="current">View Users</a> </div>
      <h1>Users</h1>

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
              <h5>Users Countries Reporting ({{ $current_year }}. Year)</h5>
            </div>
            <div class="widget-content nopadding">
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

@endsection