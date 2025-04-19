<!--
=========================================================
* Soft UI Dashboard 3 - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Order Management System">
    <meta name="author" content="Agyad Maka">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('media/agyad_maka.jpeg')}}">
    <link rel="icon" type="image/jpeg" sizes="32x32" href="{{asset('media/agyad_maka.jpeg')}}">
    <link rel="icon" type="image/jpeg" sizes="16x16" href="{{asset('media/agyad_maka.jpeg')}}">
    <link rel="manifest" href="{{asset('media/site.webmanifest')}}">
    <meta name="theme-color" content="#ffffff">
    <title>
        @yield('title')
    </title>
    <link id="pagestyle" href="{{asset('assets/css/soft-ui-dashboard.css?v=1.1.0')}}" rel="stylesheet" />
</head>

<body class="">

@yield('content')

<!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
<!--   Core JS Files   -->
<script src="{{asset('assets/js/core/popper.min.js')}}"></script>
<script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>

<script src="{{asset('assets/js/soft-ui-dashboard.min.js?v=1.1.0')}}"></script>
</body>

</html>
