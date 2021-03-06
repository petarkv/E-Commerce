<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@if(!empty($meta_title)) {{ $meta_title }} @else Home | MyShop @endif </title>
    @if(!empty($meta_description)) <meta name="description" content="{{ $meta_description }}"> 
    @endif
    @if(!empty($meta_keywords)) <meta name="keywords" content="{{ $meta_keywords }}"> 
    @endif
    <link href="{{ asset('css/main_css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main_css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main_css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main_css/price-range.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main_css/animate.css') }}" rel="stylesheet">
	<link href="{{ asset('css/main_css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main_css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main_css/easyzoom.css') }}" rel="stylesheet"> <!-- Easy Zoom Image --> 
    
    <!-- Password Strength -->
    <link href="{{ asset('css/main_css/passtrength.css') }}" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]--> 
    
    <!-- Awesome Icons 4.7.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
</head><!--/head-->

<body>
    
    @include('layouts.frontLayout.front_header')
	
	@yield('content')
	
	@include('layouts.frontLayout.front_footer')

    <script src="{{ asset('js/main_js/jquery.js') }}"></script>
	<script src="{{ asset('js/main_js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/main_js/jquery.scrollUp.min.js') }}"></script>
	<script src="{{ asset('js/main_js/price-range.js') }}"></script>
    <script src="{{ asset('js/main_js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('js/main_js/main.js') }}"></script>
    <script src="{{ asset('js/main_js/easyzoom.js') }}"></script> <!-- Easy Zoom Image -->
    <!--script src="{{ asset('js/app.js') }}"></script-->

    <!-- Login / Register Users -->
    <script src="{{ asset('js/main_js/jquery.validate.js') }}"></script>

    <!-- Password Strength -->
    <script src="{{ asset('js/main_js/passtrength.js') }}"></script>

    <!-- Price in EUR Tooltip -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <!-- Go to www.addthis.com/dashboard to customize your tools --> 
    <!--script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5ef67d55f5ae7e2f"></script--> 

    <!-- Share This -->
    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5ef687462e23bf0012362e0c&product=inline-share-buttons" 
    async="async"></script>

</body>
</html>