@extends('layouts.app')

@section('content')
<div class="container px-5">
    <div class="row justify-content-center align-itmes-center">
        <div class="col-md-8">
            <div class="card ">
                <div class="text-center mt-4">
                    <h4>{{ __('HanSeinThant') }}</h4>
                    <p>Sign in</p>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6 mx-auto">
                                <label for="user_name" class="col-md-4 col-form-label text-md-start">{{ __('Username') }}</label>
                                <input id="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name') }}" required autocomplete="user_name" autofocus>

                                @error('user_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mx-auto">
                                <label for="password" class="col-md-4 col-form-label text-md-start">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mx-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mx-auto">
                                <button type="submit" class="btn btn-primary col-12">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 text-center">
                                @if (Route::has('password.request'))
                                    <a class="btn " href="{{ route('password.request') }}" 
                                        onMouseOver="this.style.color='#00F'"
                                        onMouseOut="this.style.color='#4d525b'"
                                    >
                                        <i class="fa fa-solid fa-lock" style="color: #4d525b;" ></i>
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            
                                @if (Route::has('password.request'))
                                    <a class="btn " href="{{ route('register') }}"
                                        onMouseOver="this.style.color='#00F'"
                                        onMouseOut="this.style.color='#4d525b'"
                                    >
                                        <i class="far fa-user-circle" style="color: #4d525b;"></i>
                                        {{-- {{ __('Create an account?') }} --}}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
