@extends('layouts.auth')
@section('form-title')
    {{ __('Login') }}
@endsection
@section('form')
    <div class="card-body">
        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" class="form-horizontal form-simple" novalidate>
            @csrf

            <div class="form-group position-relative has-icon-left{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                <input type="text"  required placeholder="email" min="2" name="email" class="form-control" value="{{ old('email') }}" required="required" . id="email">
                <div class="form-control-position">
                <i class="ft-user"></i>
                </div>
                @if ($errors->has('email'))
                    <p class="text-right">
                        <small class="warning text-muted">{{ $errors->first('email') }}</small>
                    </p>
                @endif
            </div>

            <div class="form-group position-relative has-icon-left{{ $errors->has('password') ? ' form-control-warning' : '' }}">
                <input type="password"  required placeholder="password" min="2" name="password" class="form-control" value="{{ old('password')}}" id="password" required="required">
                <div class="form-control-position">
                <i class="fa fa-key"></i>
                </div>
                @if ($errors->has('password'))
                    <p class="text-right">
                        <small class="warning text-muted">{{ $errors->first('password') }}</small>
                    </p>
                @endif
            </div>
            <div class="form-group row">
                <div class="col-md-6 col-12 text-center text-md-left">
                </div>
                <div class="col-md-6 col-12 text-center text-md-right"><a href="{{ route('password.request') }}" class="card-link">{{ __('Forgot Password?') }}</a></div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="ft-unlock"></i> {{ __('Login') }}</button>
        </form>
    </div>
@endsection
