@extends('layouts.auth')
@section('form-title')
    {{ __('Login') }}
@endsection
@section('form')
    <div class="card-body">
        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" class="form-horizontal form-simple" novalidate>
            @csrf
            <fieldset class="form-group position-relative has-icon-left mb-0">
                <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-control-lg" id="email" name="email"
                       placeholder="Email"  value="{{ old('email') }}" required>
                <div class="form-control-position">
                    <i class="ft-user"></i>
                </div>
            </fieldset>
            <fieldset class="form-group position-relative has-icon-left">
                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} form-control-lg"
                       name="password" id="password" placeholder="Enter Password"
                       required>
                <div class="form-control-position">
                    <i class="fa fa-key"></i>
                </div>
            </fieldset>
            <div class="form-group row">
                <div class="col-md-6 col-12 text-center text-md-left">
                    {{--<fieldset>--}}
                        {{--<input type="checkbox" id="remember-me" name="remember-me" class="chk-remember" {{ old('remember-me') ? 'checked' : '' }}>--}}
                        {{--<label for="remember-me"> {{ __('Remember Me') }}</label>--}}
                    {{--</fieldset>--}}
                </div>
                <div class="col-md-6 col-12 text-center text-md-right"><a href="{{ route('password.request') }}" class="card-link">{{ __('Forgot Password?') }}</a></div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="ft-unlock"></i> {{ __('Login') }}</button>
        </form>
    </div>
@endsection
