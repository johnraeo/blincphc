
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ URL::asset('/svg/BLINC_Emblem_Blue.png') }}" type="image/x-icon"/>
    <title>Blinc.ph</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    
</head>
<body class="dashboard-body" onload="pageLoader()">
{{-- <body class="dashboard-body"> --}}

    <div class="content-holder">
        @yield('content')
    </div>

    {{-- @include('layouts.footer') --}}

    <footer class="footer-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <p>BLINC.ph, All rights reserved 2020 | Powered by <a href="https://www.bitshareslabs.com/" target="_blank"><u>Bitshares Labs Inc.</u></a></p>
                </div>
                <div class="col-lg-6 text-md-right">
                    <ul>
                        <li>
                            <a href="#">About</a> |
                        </li>
                        <li>
                            <a href="#">Terms & Conditions</a> |
                        </li>
                        <li>
                            <a href="#"> Privacy Policy</a> |
                        </li>
                        <li>
                            <a href="#">Cookies Notice</a>
                        </li>
                    </ul>  
                </div>
            </div>
        </div>
    </footer>

</body>

<div class="page_loader" >
    <div class="page_loader_cont">
        <img src="{{ asset('svg/loader.svg') }}" alt="Loading..."> 
    </div>
</div>

<script>
    function pageLoader() {
        var xyz = document.querySelector('.page_loader').remove();
        // alert("Page loaded");
    }
</script>
</html>