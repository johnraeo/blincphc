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

<body class="landing-body" onload="pageLoader()">

    <div id="auth">
        <nav class="navbar navbar-expand-md navbar-light sticky-top navbar-section">
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
        </nav>

        <main>
            <div class="log-section d-flex align-items-center">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-5">

                                <h1>{{ __('Reset Password') }}</h1>
                
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
            
                                    <input type="hidden" name="token" value="{{ $token }}">
            
                                    <div class="input-container">
                                        <label for="email">{{ __('E-Mail Address') }}</label>
            
                                        <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
        
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
            
                                    <div class="input-container">
                                        <label for="password">{{ __('Password') }}</label>
            
                                        <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
        
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                    </div>
            
                                    <div class="input-container">
                                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
            
                                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                                    </div>
            
                                    <div class="input-container">
                                        <button type="submit" class="active-btn">
                                            {{ __('Reset Password') }}
                                        </button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

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

{{-- @extends('layouts.head')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
