
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
    {{-- <link href="{{ asset('css/landing.css') }}" rel="stylesheet"> --}}
    
</head>

<body class="landing-body" onload="pageLoader()">

    <div id="auth">
        {{-- <nav class="navbar navbar-expand-md navbar-light sticky-top navbar-section">
            <div class="container cts-container">
                <a href="#"><img class="" src="{{ asset('svg/full_logo.svg') }}"></a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto nav-icons">
                        <li class="nav-link social-media-link">
                        <a class="" href="https://www.facebook.com/bitshareslabs/" target="_blank"><img src="{{ asset('svg/landing-facebook.svg') }}"></a>
                        </li>
                        <li class="nav-link twitter social-media-link">
                        <a class="" href="https://twitter.com/bitshareslabs" target="_blank"><img src="{{ asset('svg/landing-twitter.svg') }}"></a>
                        </li>
                        <li class="nav-link insta social-media-link">
                            <a class="" href="https://www.instagram.com/bitshareslabs" target="_blank"><img src="{{ asset('svg/instagram.svg') }}"></a>
                        </li>
                        <li class="nav-link social-media-link">
                            <a class="" href="https://www.linkedin.com/company/bitshareslabs" target="_blank"><img src="{{ asset('svg/linkedin.svg') }}"></a>
                        </li>
                        <li class="nav-link medium social-media-link">
                            <a class="" href="https://medium.com/bitshares-labs" target="_blank"><img src="{{ asset('svg/medium.svg') }}"></a>
                        </li>
                    </ul>
        
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-link download-icon">
                            <a class="nav-link nav-right-side" href="#">Download App</a> 
                        </li>
                        <li>
                            <div class="vl"></div>
                        </li>
 
                        @if (Route::has('login'))
                            @auth
                            <li class="nav-link">
                                <a class="" href="{{ route('login') }}">
                                    <button class="active-btn">
                                        Go to My Wallet
                                    </button>
                                </a>
                            </li>
                            @else 
                                @if (Route::has('register'))
                                    <li class="nav-link">
                                        <a class="nav-link nav-right-side" href="{{ route('register') }}">Sign Up</a> 
                                    </li>
                                    <li class="nav-link">
                                        <a class=" " href="{{ route('login') }}">
                                            <button class="active-btn">
                                                Go to My Wallet
                                            </button>
                                        </a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                        
                    </ul>
                </div>
            </div>
        </nav> --}}
        
        {{-- SP Auth --}}
        <landing-nav-component></landing-nav-component>

        <main>
            @yield('content')
        </main>

        {{-- @include('layouts.footer') --}}

        <footer class="footer-section">
            <div class="container cts-container">
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

    </div>
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