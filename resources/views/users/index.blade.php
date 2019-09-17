@extends('layouts.user-management')
@push('vendor-styles')
        <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/toggle/switchery.min.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">--}}
@endpush
@push('other-styles')
    {{--<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/switch.css')}}">--}}
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-tooltip.css')}}">--}}
@endpush
@section('um-content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Users</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Users
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        {{--<div class="content-header-right col-md-6 col-12 mb-2">--}}
            {{--<a class="btn btn-secondary square float-right" href="{{route('user-management.user.create')}}">--}}
                {{--<i class="icon-user-follow"></i> New User--}}
            {{--</a>--}}
        {{--</div>--}}
    </div>

    <div class="content-body">
        <section id="configuration">
            <div class="row">
                <div class="col-12">
                    <div class="card" id="add-box">
                        <div class="card-header">
                            <h4 class="card-title">Add User</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <form method="POST" action="{{ route('user-management.user.save_user') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('name') ? ' form-control-warning' : '' }}">
                                                <label for="permission_name">{{ __('Name') }} <span class="required">*</span></label>
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
                                                <label for="permission_name">{{ __('E-Mail Address') }} <span class="required">*</span></label>
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
                                                <label for="phone">{{ __('Phone Number') }} <span class="required">*</span></label>
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
                                                <label for="role">{{ __('Role') }} <span class="required">*</span></label>
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
                                                <label for="institution">{{ __('Select Institution') }} <span class="required">*</span></label>
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
                                                <label for="ace">{{ __('Select ACE') }} <span class="required">*</span></label>
                                                <select id="ace" class="form-control{{ $errors->has('ace') ? ' is-invalid' : '' }}" name="ace" value="{{ old('ace') }}">
                                                    <option value="">Select ACE</option>
                                                    @foreach($aces as $ace)
                                                        <option {{old('ace') == $ace->id ? 'selected': ''}} value="{{$ace->id}}">{{$ace->name." (".$ace->acronym.")"}}</option>
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('ace'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('ace') }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary mr-2">
                                                {{ __('Add User') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">List of Users</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <table class="table table-striped table-bordered" id="users-table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Institution</th>
                                        <th>Date Added</th>
                                        <th width="30px">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach($users as $user)
                                        @php
                                            $count += 1;
                                            $institution = "-";
                                            if (isset($user->ace)){
                                                $institution = $user->ace_->name;
                                            }elseif (isset($user->institution)){
                                                $institution = $user->institution_->name;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>
                                                {{$institution}}
                                                {{--@isset($user->ace)--}}
                                                    {{--{{$user->ace}}--}}
                                                {{--@else--}}
                                                    {{-----}}
                                                {{--@endisset--}}
                                            </td>
                                            <td>{{date('M d, Y',strtotime($user->created_at))}}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{route('user-management.user.profile',[\Illuminate\Support\Facades\Crypt::encrypt($user->id)])}}"
                                                       class="btn btn-s btn-dark"><i class="ft-eye"></i></a>
                                                    <a href="#add-box" onclick="edit_user('{{\Illuminate\Support\Facades\Crypt::encrypt($user->id)}}')" class="btn btn-s btn-secondary">
                                                        <i class="ft-edit"></i></a>
                                                    <a class="dropdow-item btn {{($user->status == 0)?'btn-success' : 'btn-warning'}} btn-s"
                                                       href="{{ route('user-management.user.delete',[\Illuminate\Support\Facades\Crypt::encrypt($user->id)]) }}"
                                                       onclick="event.preventDefault();
                                                     document.getElementById('status-form-{{$count}}').submit();">
                                                        @if($user->status == 0)
                                                            <i class="ft-user-check"></i>
                                                        @else
                                                            <i class="ft-user-x"></i>
                                                        @endif
                                                    </a>
                                                    <a class="dropdow-item btn btn-danger btn-s"
                                                       href="{{ route('user-management.user.remove',[\Illuminate\Support\Facades\Crypt::encrypt($user->id)]) }}"
                                                       onclick="event.preventDefault();
                                                     document.getElementById('delete-form-{{$count}}').submit();">
                                                        <i class="ft-trash-2"></i>
                                                    </a>
                                                </div>

                                                <form id="status-form-{{$count}}" action="{{ route('user-management.user.delete',[\Illuminate\Support\Facades\Crypt::encrypt($user->id)]) }}" method="POST" style="display: none;">
                                                    @csrf {{method_field('DELETE')}}
                                                    <input type="hidden" name="user" value="{{\Illuminate\Support\Facades\Crypt::encrypt($user->id)}}">
                                                </form>

                                                <form id="delete-form-{{$count}}" action="{{ route('user-management.user.remove',[\Illuminate\Support\Facades\Crypt::encrypt($user->id)]) }}" method="POST" style="display: none;">
                                                    @csrf {{method_field('DELETE')}}
                                                    <input type="hidden" name="user" value="{{\Illuminate\Support\Facades\Crypt::encrypt($user->id)}}">
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('vendor-script')
        <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script>
        $('#users-table').dataTable();

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
        }

        //Script to call the edit view for user
        function edit_user(key) {

            var path = "{{route('user-management.user.edit_user_view')}}";
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                url: path,
                type: 'GET',
                data: {id:key},
                beforeSend: function(){
                    $('#add-box').block({
                        message: '<div class="ft-loader icon-spin font-large-1"></div>',
                        overlayCSS: {
                            backgroundColor: '#ccc',
                            opacity: 0.8,
                            cursor: 'wait'
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: 'transparent'
                        }
                    });;
                },
                success: function(data){
                    $('#add-box').empty();
                    $('#add-box').html(data.theView);
                    // console.log(data)
                },
                complete:function(){
                    $('#add-box').unblock();
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }
    </script>
@endpush