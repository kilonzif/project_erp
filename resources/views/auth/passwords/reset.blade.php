@extends('layouts.auth')
@section('form-title')
    {{ __('Reset Password') }}
@endsection
@section('form')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('password.request') }}" aria-label="{{ __('Reset Password') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group position-relative has-icon-left{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                    <input id="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus placeholder="email">
                    <div class="form-control-position">
                        <i class="ft-mail"></i>
                    </div>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group position-relative has-icon-left{{ $errors->has('password') ? ' form-control-warning' : '' }}">
                    <input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="password" required>
                    <div class="form-control-position">
                        <i class="fa fa-key"></i>
                    </div>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group position-relative has-icon-left{{ $errors->has('password_confirmation') ? ' form-control-warning' : '' }}">
                    <input id="password-confirm" type="password" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" placeholder="confirm password" required>
                    <div class="form-control-position">
                        <i class="fa fa-key"></i>
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <p class="text-right">
                            <small class="warning text-muted">{{ $errors->first('password_confirmation') }}</small>
                        </p>
                    @endif
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
@endsection
