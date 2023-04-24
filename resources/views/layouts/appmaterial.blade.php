<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="background: #ECEEEE;">
    <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PICKBET</title>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/pick.png') }}">
    <link rel="icon" type="image/ico" href="{{asset('/img/pick.png')}}" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="{{ asset('material') }}/css/material-dashboard.css" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet" />
      <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="{{ $class ?? '' }}" style="background: #ECEEEE; height:100vh; max-height:auto">
        @auth()
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @include('layouts.page_templates.auth')
        @endauth
        @guest()
            <div id="app">

            </div>
            @include('layouts.page_templates.guest')
        @endguest
        <!--   Core JS Files   -->
        <script src="{{ asset('material') }}/js/core/jquery.min.js"></script>
        <!-- <script src="/js/search.js"></script> -->
        <script src="{{ asset('material') }}/js/core/popper.min.js"></script>
        <script src="{{ asset('material') }}/js/core/bootstrap-material-design.min.js"></script>
        <!-- <script src="{{ asset('material') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script> -->
        <!-- Plugin for the momentJs  -->
        <!-- <script src="{{ asset('material') }}/js/plugins/moment.min.js"></script> -->
        <!--  Plugin for Sweet Alert -->
        <script src="{{ asset('material') }}/js/plugins/sweetalert2.js"></script>
        <!-- Forms Validations Plugin -->
        <script src="{{ asset('material') }}/js/plugins/jquery.validate.min.js"></script>
        <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
        <script src="{{ asset('material') }}/js/plugins/jquery.bootstrap-wizard.js"></script>
        <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
        <script src="{{ asset('material') }}/js/plugins/bootstrap-selectpicker.js"></script>
        <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
        <!-- <script src="{{ asset('material') }}/js/plugins/bootstrap-datetimepicker.min.js"></script> -->
        <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
        <!-- <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script> -->
        <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
        <script src="{{ asset('material') }}/js/plugins/bootstrap-tagsinput.js"></script>
        <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
        <!-- <script src="{{ asset('material') }}/js/plugins/jasny-bootstrap.min.js"></script> -->
        <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
        <!-- <script src="{{ asset('material') }}/js/plugins/fullcalendar.min.js"></script> -->
        <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
        <!-- <script src="{{ asset('material') }}/js/plugins/jquery-jvectormap.js"></script> -->
        <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
        <!-- <script src="{{ asset('material') }}/js/plugins/nouislider.min.js"></script> -->
        <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
        <!-- Library for adding dinamically elements -->
        <script src="{{ asset('material') }}/js/plugins/arrive.min.js"></script>
        <!--  Google Maps Plugin    -->
        <!-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE'"></script> -->
        <!-- Chartist JS -->
        <script src="{{ asset('material') }}/js/plugins/chartist.min.js"></script>
        <!--  Notifications Plugin    -->
        <script src="{{ asset('material') }}/js/plugins/bootstrap-notify.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="{{ asset('material') }}/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
        <!-- Material Dashboard DEMO methods, don't include it in your project! -->
        <!-- <script src="{{ asset('material') }}/demo/demo.js"></script> -->
        <script src="{{ asset('material') }}/js/settings.js"></script>
        @stack('js')

		  @if(Auth::user()->role ===5||Auth::user()->role ===8)
        <script>
// helper function to set cookies
function setCookie(cname, cvalue, seconds) {
    var d = new Date();
    d.setTime(d.getTime() + (seconds * 1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// helper function to get a cookie
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// Do not allow multiple call center tabs
// if (~window.location.hash.indexOf('#admin/callcenter')) {
    $(window).on('beforeunload onbeforeunload', function(){
        document.cookie = 'ic_window_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    });

    function validateCallCenterTab() {
        var win_id_cookie_duration = 1; // in seconds

        if (!window.name) {
            window.name = Math.random().toString();
        }

        if (!getCookie('ic_window_id') || window.name === getCookie('ic_window_id')) {
            // This means they are using just one tab. Set/clobber the cookie to prolong the tab's validity.
            setCookie('ic_window_id', window.name, win_id_cookie_duration);
        } else if (getCookie('ic_window_id') !== window.name) {
            // this means another browser tab is open, alert them to close the tabs until there is only one remaining
            var message = 'You cannot have this website open in multiple tabs. ' +
                'Please close them until there is only one remaining. Thanks!';
            $('html').html(message);
            clearInterval(callCenterInterval);
            throw 'Multiple call center tabs error. Program terminating.';
        }
    }

    callCenterInterval = setInterval(validateCallCenterTab, 1000);
// }
</script>
@endif
    </body>
</html>
