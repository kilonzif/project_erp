@extends('layouts.auth')
@section('form-title')
    {{ __('We will send you a link to reset password.') }}
@endsection
@section('form')
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
            <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}" novalidate>
                @csrf
                <fieldset class="form-group position-relative has-icon-left">
                <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} form-control-lg" id="user-email"
                       placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}"
                       required>
                <div class="form-control-position">
                    <i class="ft-mail"></i>
                </div>
            </fieldset>
            <button type="submit" class="btn btn-outline-primary btn-lg btn-block"><i class="ft-unlock"></i>
                {{ __('Send Password Reset Link') }}
            </button>
        </form>
    </div>
@endsection
@section('form-footer')
    <div class="card-footer border-0">
        <p class="float-sm-left text-center"><a href="{{route('login')}}" class="card-link">{{ __('Login') }}</a></p>
        <p class="float-sm-right text-center">New to Stack ? <a href="{{route('register')}}" class="card-link">{{ __('Create Account') }}</a></p>
    </div>
@endsection
