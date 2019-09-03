@extends('layouts.user-management')
@section('um-content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Users</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Users
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="configuration">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">{{ __('Add User') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('user-management.user.save_user') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('name') ? ' form-control-warning' : '' }}">
                                            <label for="permission_name">{{ __('Name') }}</label>
                                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                            @if ($errors->has('name'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('name') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                                            <label for="permission_name">{{ __('E-Mail Address') }}</label>
                                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                            @if ($errors->has('email'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('email') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group{{ $errors->has('phone') ? ' form-control-warning' : '' }}">
                                            <label for="phone">{{ __('Phone Number') }}</label>
                                            <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required>

                                            @if ($errors->has('phone'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('phone') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group{{ $errors->has('role') ? ' form-control-warning' : '' }}">
                                            <label for="role">{{ __('Role') }}</label>
                                            <select id="role" onchange="changeOnRole()" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" value="{{ old('email') }}" required>
                                                <option value="">Select Role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}">{{$role->display_name}}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('role'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('role') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('institution') ? ' form-control-warning' : '' }}" style="display: block;" id="institution_toggle">
                                            <label for="institution">{{ __('Select Institution') }}</label>
                                            <select id="institution" class="form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" name="institution" value="{{ old('institution') }}">
                                                <option value="">Select Institution</option>
                                                @foreach($institutions as $institution)
                                                    <option value="{{$institution->id}}">{{$institution->name}}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('institution'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('institution') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('ace') ? ' form-control-warning' : '' }}" id="ace_toggle" style="display: none;">
                                            <label for="ace">{{ __('Select ACE') }}</label>
                                            <select id="ace" class="form-control{{ $errors->has('ace') ? ' is-invalid' : '' }}" name="ace" value="{{ old('ace') }}">
                                                <option value="">Select ACE</option>
                                                @foreach($aces as $ace)
                                                    <option {{old('ace') == $ace->id ? 'selected': ''}} value="{{$ace->id}}">{{$ace->name}}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('ace'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('ace') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    {{--<div class="col-md-6">--}}
                                    {{--</div>--}}
                                </div>
                                {{--<div class="form-group row">--}}
                                    {{--<label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>--}}

                                    {{--<div class="col-md-6">--}}
                                        {{----}}
                                        {{--@if ($errors->has('name'))--}}
                                            {{--<span class="invalid-feedback" role="alert">--}}
                                                            {{--<strong>{{ $errors->first('name') }}</strong>--}}
                                                        {{--</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group row">--}}
                                    {{--<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

                                    {{--<div class="col-md-6">--}}
                                        {{--<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>--}}

                                        {{--@if ($errors->has('email'))--}}
                                            {{--<span class="invalid-feedback" role="alert">--}}
                                                            {{--<strong>{{ $errors->first('email') }}</strong>--}}
                                                        {{--</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group row">--}}
                                    {{--<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

                                    {{--<div class="col-md-6">--}}
                                        {{--<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>--}}

                                        {{--@if ($errors->has('password'))--}}
                                            {{--<span class="invalid-feedback" role="alert">--}}
                                                            {{--<strong>{{ $errors->first('password') }}</strong>--}}
                                                        {{--</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group row">--}}
                                    {{--<label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>--}}

                                    {{--<div class="col-md-6">--}}
                                        {{--<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            {{ __('Add User') }}
                                        </button>
                                        <a href="{{route('user-management.users')}}" class="btn btn-secondary left">
                                            {{ __('Back') }}
                                        </a>
                                        {{--<a href="{{URL::previous()}}" class="btn btn-secondary left">--}}
                                            {{--{{ __('Back') }}--}}
                                        {{--</a>--}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('vendor-script')
@endpush
@push('end-script')
    <script>
        function changeOnRole(){
            var e = document.getElementById("role");
            var role = e.options[e.selectedIndex].value;
            if(role == 3){
                $('#institution_toggle').css("display", "none");
                $('#ace_toggle').css("display", "block");
            }
            else{
                $('#institution_toggle').css("display", "block");
                $('#ace_toggle').css("display", "none");
            }
            // alert(role)
        }
    </script>
@endpush
