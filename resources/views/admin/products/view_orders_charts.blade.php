<?php

$current_year = date('Y');
$current_month = date('M');
$last_month = date('M',strtotime("-1 month"));
$before_2_month = date('M',strtotime("-2 month"));
$before_3_month = date('M',strtotime("-3 month"));
 
$dataPoints = array(
	array("y" => $current_month_orders, "label" => $current_month),
	array("y" => $last_month_orders, "label" => $last_month),
	array("y" => $before_2_month_orders, "label" => $before_2_month),
	array("y" => $before_3_month_orders, "label" => $before_3_month)	
);
 
?>

@extends('layouts.adminLayout.admin_design')
@section('content')

<script>
  window.onload = function() {
   
  var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    theme: "light2",
    title:{
      text: "Orders Reporting"
    },
    axisY: {
      title: "Number of Orders"
    },
    data: [{
      type: "column",
      yValueFormatString: "#,##0.## orders",
      showInLegend: true,
      legendMarkerColor: "grey",
      legendText: "Last 4 Months",
      dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
    }]
  });
  chart.render();
   
  }
  </script>
    
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="{{ url("/admin/dashboard") }}" title="Go to Home" class="tip-bottom">
            <i class="icon-home"></i> Home</a> <a href="">Orders</a> 
            <a href="{{ url('/admin/view-orders') }}" class="current">View Orders</a> </div>
      <h1>Orders</h1>

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
    <div style="margin-left:20px;">
      <a href="{{ url('/admin/export-orders') }}" class="btn btn-primary btn-mini">Export Orders</a>
      <p>Export Orders in Excel file (.xlsx)</p>
    </div>
    <div class="container-fluid">
      <hr>
      <div class="row-fluid">
        <div class="span12">

          <div class="widget-box">
            <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
              <h5>Orders Reporting ({{ $current_year }}. Year)</h5>
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