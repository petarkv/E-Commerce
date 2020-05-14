<!DOCTYPE html>
<html lang="en">
<head>
<title>MyShop - Admin</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="{{ asset('css/template_css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/template_css/bootstrap-responsive.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/template_css/uniform.css') }}" />
<link rel="stylesheet" href="{{ asset('css/template_css/fullcalendar.css') }}" />
<link rel="stylesheet" href="{{ asset('css/template_css/matrix-style.css') }}" />
<link rel="stylesheet" href="{{ asset('css/template_css/matrix-media.css') }}" />
<link href="{{ asset('fonts/template_fonts/css/font-awesome.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/template_css/jquery.gritter.css') }}" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<!-- Number of entries in table -->
<link rel="stylesheet" href="{{ asset('css/template_css/select2.css') }}" />

<!-- Sweet Alert -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" />

<!-- Date Picker -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body>

@include('layouts.adminLayout.admin_header')

@include('layouts.adminLayout.admin_sidebar')

@yield('content')

@include('layouts.adminLayout.admin_footer')

<!--
<script src="{{ asset('js/template_js/excanvas.min.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.min.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.ui.custom.js') }}"></script> 
<script src="{{ asset('js/template_js/bootstrap.min.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.flot.min.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.flot.resize.min.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.peity.min.js') }}"></script> 
<script src="{{ asset('js/template_js/fullcalendar.min.js') }}"></script> 
<script src="{{ asset('js/template_js/matrix.js') }}"></script> 
<script src="{{ asset('js/template_js/matrix.dashboard.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.gritter.min.js') }}"></script> 
<script src="{{ asset('js/template_js/matrix.interface.js') }}"></script> 
<script src="{{ asset('js/template_js/matrix.chat.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.validate.js') }}"></script> 
<script src="{{ asset('js/template_js/matrix.form_validation.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.wizard.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.uniform.js') }}"></script> 
<script src="{{ asset('js/template_js/select2.min.js') }}"></script> 
<script src="{{ asset('js/template_js/matrix.popover.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.dataTables.min.js') }}"></script> 
<script src="{{ asset('js/template_js/matrix.tables.js') }}"></script> 

<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
      
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();            
          } 
          // else, send page to designated URL            
          else {  
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>-->

<script src="{{ asset('js/template_js/jquery.min.js') }}"></script> 
<!--<script src="{{ asset('js/template_js/jquery.ui.custom.js') }}"></script>--> 
<script src="{{ asset('js/template_js/bootstrap.min.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.uniform.js') }}"></script> 
<script src="{{ asset('js/template_js/select2.min.js') }}"></script> 
<script src="{{ asset('js/template_js/jquery.validate.js') }}"></script> 
<script src="{{ asset('js/template_js/matrix.js') }}"></script> 
<script src="{{ asset('js/template_js/matrix.form_validation.js') }}"></script>

<!-- Pop Up Window -->
<script src="{{ asset('js/template_js/matrix.popover.js') }}"></script>

<!-- Table -->
<script src="{{ asset('js/template_js/jquery.dataTables.min.js') }}"></script> 
<script src="{{ asset('js/template_js/matrix.tables.js') }}"></script>

<!-- Sweet Alert -->
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script--> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<!-- Date Picker -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#expiry_date" ).datepicker({
      minDate: 0,
      dateFormat: 'yy-mm-dd'
      });
  } );
  </script>

</body>
</html>