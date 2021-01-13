@extends('layouts.head')

@section('content')
    
{{-- <div class="log-section d-flex align-items-center">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">

                <h1>Login</h1> 

                @include('alert')

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="input-container">
                        <label for="login">{{ __('E-Mail') }}</label>
                        <input id="login" type="text" class=" @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus placeholder="Email">
                        
                        @if(session('error'))
                            <span class="invalid-feedback text-md-right" role="alert">
                                {{ session('error') }}
                            </span>
                        @endif

                        @error('login')
                            <span class="invalid-feedback text-md-right" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="input-container">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="input_password" type="password" class=" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                        <img class="eye_password" src="{{ asset('svg/show_eye_password.svg') }}">

                        @error('password')
                            <span class="invalid-feedback text-md-right" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="input-container">
                        <button type="submit" class="active-btn">
                            {{ __('Login') }}
                        </button>
                    </div>

                    <div class="d-flex justify-content-between">
                            <p hidden>Or continue via</p>
                            <a href="#"><u>Forgot password</u></a>
                    </div>

                    <div class="d-flex justify-content-first" hidden>
                        <img src="{{ asset('svg/facebook.svg') }}" hidden>
                        <img src="{{ asset('svg/twitter.svg') }}" hidden>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    {{-- SP Auth --}}
    <router-view></router-view>

{{-- <script>

    const eyePassword = document.querySelector('.eye_password');
    const inputPassword = document.querySelector('#input_password');

    function showPassword() {
        if(inputPassword.type == 'password') {
            inputPassword.type = 'text'
        }
        else {
            inputPassword.type = 'password'
        }
    }

    eyePassword.addEventListener('click', showPassword);

</script> --}}
@endsection