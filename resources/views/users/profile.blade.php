@extends('layouts.user-management')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/listbox/bootstrap-duallistbox.min.css')}}">
@endpush
@push('end-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/dual-listbox.css')}}">
@endpush
@section('um-content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">{{__('User Profile')}}</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('user-management.users')}}">{{__('Users')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('Profile') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="configuration">
            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header card-head-inverse bg-primary">
                            <h4 class="card-title">{{$user->name}}</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <p class="card-text">{{$user->email}}</p>
                                <p class="card-text">{{$user->phone}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div id="profileEditCollapse" role="tablist" aria-multiselectable="true">
                        <div class="card collapse-icon accordion-icon-rotate left bb-header">
                            <div id="cpass-collapse" class="card-header">
                                <a data-toggle="collapse" data-parent="#profileEditCollapse" href="#cpass-body" aria-expanded="true"
                                   aria-controls="cpass-collapse" class="card-title lead">{{__('Change Password')}}</a>
                            </div>
                            <div id="cpass-body" role="tabpanel" aria-labelledby="cpass-collapse" class="collapse show">
                                <div class="card-content">
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('user-management.user.change_password',[\Illuminate\Support\Facades\Crypt::encrypt($user->id)]) }}">
                                            @csrf {{method_field('PUT')}}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group{{ $errors->has('password') ? ' form-control-warning' : '' }}">
                                                        <label for="password">{{ __('Password') }}</label>
                                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required autofocus>

                                                        @if ($errors->has('password'))
                                                            <p class="text-right mb-0">
                                                                <small class="warning text-muted">{{ $errors->first('password') }}</small>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-secondary">
                                                            {{ __('Save') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="heading22" class="card-header">
                                <a data-toggle="collapse" data-parent="#profileEditCollapse" href="#accordion22" aria-expanded="false"
                                   aria-controls="accordion22" class="card-title lead collapsed">User Information</a>
                            </div>
                            <div id="accordion22" role="tabpanel" aria-labelledby="heading22" class="collapse"
                                 aria-expanded="false">
                                <div class="card-content">
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('user-management.user.edit_user',[\Illuminate\Support\Facades\Crypt::encrypt($user->id)]) }}">
                                            @csrf {{method_field("PUT")}}
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group{{ $errors->has('name') ? ' form-control-warning' : '' }}">
                                                        <label for="permission_name">{{ __('Name') }}</label>
                                                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name')?old('name'):$user->name }}" required autofocus>

                                                        @if ($errors->has('name'))
                                                            <p class="text-right mb-0">
                                                                <small class="warning text-muted">{{ $errors->first('name') }}</small>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group{{ $errors->has('phone') ? ' form-control-warning' : '' }}">
                                                        <label for="phone">{{ __('Phone Number') }}</label>
                                                        <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                               name="phone" value="{{ old('phone')?old('phone'):$user->phone }}" required>

                                                        @if ($errors->has('phone'))
                                                            <p class="text-right mb-0">
                                                                <small class="warning text-muted">{{ $errors->first('phone') }}</small>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                                                        <label for="permission_name">{{ __('E-Mail Address') }}</label>
                                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                               name="email" value="{{ old('email')?old('email'):$user->email }}" required>

                                                        @if ($errors->has('email'))
                                                            <p class="text-right mb-0">
                                                                <small class="warning text-muted">{{ $errors->first('email') }}</small>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group}}">
                                                        <button type="submit" class="btn btn-secondary" style="margin-top: 1.9rem;">
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @role('webmaster|super-admin')
                            <div id="heading23" class="card-header">
                                <a data-toggle="collapse" data-parent="#profileEditCollapse" href="#accordion23" aria-expanded="false"
                                   aria-controls="accordion23" class="card-title lead collapsed">Attach Permissions</a>
                            </div>
                            <div id="accordion23" role="tabpanel" aria-labelledby="heading23" class="collapse"
                                 aria-expanded="false">
                                <div class="card-content">
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('user-management.user.permissions_save',[\Illuminate\Support\Facades\Crypt::encrypt($user->id)]) }}">
                                            @csrf
                                            <h6>Select Permissions</h6>
                                            <div class="row">
                                                    <div class="form-group">
                                                        @if($permissions->count() > 0)
                                                            <div class="form-group">
                                                                <select multiple="multiple" name="permissions[]" id="permissions_list" size="10" class="duallistbox">
                                                                    @foreach($permissions as $permission)
                                                                        <option value="{{$permission->id}}" @if(in_array($permission->id,$user_permissions)) selected @endif>
                                                                            {{$permission->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @if ($errors->has('permissions_list'))
                                                                <p class="text-right">
                                                                    <small class="warning text-muted">{{ $errors->first('permissions') }}</small>
                                                                </p>
                                                            @endif
                                                        @else
                                                            <div class="form-group">
                                                                <label for="message">No permissions set</label>
                                                            </div>
                                                        @endif
                                                    </div>
                                            </div>

                                            <div class="form-group row mb-0">
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-primary mr-md-3">
                                                        {{ __('Save') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div id="heading24" class="card-header">
                                <a data-toggle="collapse" data-parent="#profileEditCollapse" href="#accordion24" aria-expanded="false"
                                   aria-controls="accordion24" class="card-title lead collapsed">Attach Roles</a>
                            </div>
                            <div id="accordion24" role="tabpanel" aria-labelledby="heading24" class="collapse"
                                 aria-expanded="false" style="height: 0px;">
                                <div class="card-content">
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('user-management.user.roles_save',[\Illuminate\Support\Facades\Crypt::encrypt($user->id)]) }}">
                                            @csrf
                                            <h6>Select Roles</h6>
                                            <div class="row">
                                                <div class="form-group">
                                                    @if($roles->count() > 0)
                                                        <div class="form-group">
                                                            <select multiple="multiple" name="roles[]" id="roles_list" size="10" class="duallistbox">
                                                                @foreach($roles as $role)
                                                                    <option value="{{$role->id}}" @if(in_array($role->id,$user_roles)) selected @endif>
                                                                        {{$role->display_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @if ($errors->has('roles_list'))
                                                            <p class="text-right">
                                                                <small class="warning text-muted">{{ $errors->first('roles') }}</small>
                                                            </p>
                                                        @endif
                                                    @else
                                                        <div class="form-group">
                                                            <label for="message">No roles set</label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row mb-0">
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-primary mr-md-3">
                                                        {{ __('Save') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('vendor-script')
<script src="{{asset('vendors/js/forms/listbox/jquery.bootstrap-duallistbox.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/listbox/form-duallistbox.js')}}" type="text/javascript"></script>
@endpush
