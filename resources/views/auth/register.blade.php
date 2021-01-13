{{-- @extends('layouts.head')

@section('content')
<div class="log-section d-flex align-items-center">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <h1>Sign Up</h1>

                @include('alert')

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="input-container">
                        <label for="login">{{ __('E-Mail') }}</label>
                        <input id="email" type="text" class=" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus placeholder="Email">

                        @error('login')
                            <span class="invalid-feedback text-md-right" role="alert">
                                {{ $message }}
                            </span>
                        @enderror

                        @if(session('error'))
                            <span class="invalid-feedback text-md-right" role="alert">
                                {{ session('error') }}
                            </span>
                        @endif
                    </div>

                    <div class="input-container">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="input_password" type="password" class=" @error('password') is-invalid @enderror" name="password"  autocomplete="current-password" placeholder="Password">
                        <img class="eye_password" src="{{ asset('svg/show_eye_password.svg') }}">

                        @error('password')
                            <span class="invalid-feedback text-md-right" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="input-container">
                        <label for="password">{{ __('Confirm Password') }}</label>
                        <input id="input_password2" type="password" class="" name="password_confirmation"  autocomplete="new-password" placeholder="Confirm Password">
                        <img class="eye_password2" src="{{ asset('svg/show_eye_password.svg') }}">

                        @error('password')
                            <span class="invalid-feedback text-md-right" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="input-container">
                        <button type="submit" class="active-btn">
                            {{ __('Sign Up') }}
                        </button>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                            <p>Or continue via</p>
                            <a href="#">Forgot password</a>
                    </div>

                    <div class="d-flex justify-content-first">
                        <img src="{{ asset('svg/facebook.svg') }}">
                        <img src="{{ asset('svg/twitter.svg') }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>

    const eyePassword = document.querySelector('.eye_password');
    const eyePassword2 = document.querySelector('.eye_password2');
    const inputPassword = document.querySelector('#input_password');
    const inputPassword2 = document.querySelector('#input_password2');

    function showPassword() {
        if(inputPassword.type == 'password') {
            inputPassword.type = 'text'
        }
        else {
            inputPassword.type = 'password'
        }
    }

    function showPassword2() {
        if(inputPassword2.type == 'password') {
            inputPassword2.type = 'text'
        }
        else {
            inputPassword2.type = 'password'
        }
    }

    eyePassword.addEventListener('click', showPassword);
    eyePassword2.addEventListener('click', showPassword2);

</script>
@endsection --}}
