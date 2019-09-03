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
            <h3 class="content-header-title mb-0">Roles</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Roles
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Roles</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th width="60px"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($roles->count() > 0)
                                        @foreach($roles as $role)
                                            <tr>
                                                <td>{{$role->display_name}}</td>
                                                <td>
                                                    <button class="btn btn-sm square loadRole" data-role="{{$role->name}}" onclick="loadRole('{{$role->name}}')">
                                                        <i class="icon-pencil"></i>
                                                    </button>
                                                    {{--<button class="btn btn-sm btn-danger square" onclick="trashRole('{{$role->name}}')">--}}
                                                        {{--<i class="icon-trash"></i>--}}
                                                    {{--</button>--}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-center">{{__('No Roles Set')}}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card" id="action-card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('Add Role')}}</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form action="{{route('user-management.roles.create')}}" method="post">
                                @csrf
                                <div id="action-form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="role_name">{{__('Role Name')}} <span class="required">*</span></label>
                                                <input type="text" required min="2" value="{{ old('role_name') }}" name="role_name" class="form-control" id="role_name">
                                                @if ($errors->has('role_name'))
                                                    <p class="text-right">
                                                        <small class="warning text-muted">{{ $errors->first('role_name') }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="display_name">{{__('Display Name')}} <span class="required">*</span></label>
                                                <input type="text" name="display_name" value="{{ old('display_name') }}" class="form-control" id="display_name">
                                                @if ($errors->has('display_name'))
                                                    <p class="text-right">
                                                        <small class="warning text-muted">{{ $errors->first('display_name') }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{--<label for="role_desp">Role Description</label>--}}
                                                <input type="text" name="role_desp" value="{{ old('role_desp') }}" placeholder="Role Description" class="form-control" id="role_desp">
                                                @if ($errors->has('role_desp'))
                                                    <p class="text-right">
                                                        <small class="warning text-muted">{{ $errors->first('role_desp') }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($permissions->count() > 0)
                                    <div class="form-group">
                                        <select multiple="multiple" name="permissions[]" id="permissions" required size="10" class="duallistbox">
                                        @foreach($permissions as $permission)
                                            <option value="{{$permission->id}}">{{$permission->name}}</option>
                                        @endforeach
                                        </select>
                                        @if ($errors->has('permissions'))
                                            <p class="text-right">
                                                <small class="warning text-muted">{{ $errors->first('permissions') }}</small>
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="message">{{__('No permissions set')}}</label>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                                        {{__('Save')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('vendor-script')
<script src="{{asset('vendors/js/forms/listbox/jquery.bootstrap-duallistbox.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/listbox/form-duallistbox.js')}}" type="text/javascript"></script>
    <script>
        function addView() {
            var path = "{{route('user-management.roles.emptyForm')}}";
            console.log(path);
            $.ajax({
                url: path,
                type: 'GET',
                beforeSend: function(){
                    $('#action-card').block({
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
                    });
                },
                success: function(data){
                    $('#action-card').empty();
                    $('#action-card').html(data);
                },
                complete:function(){
                    $('#action-card').unblock();
                    $.getScript("http://127.0.0.1:8000/vendors/js/forms/listbox/jquery.bootstrap-duallistbox.min.js")
                    $.getScript("http://127.0.0.1:8000/js/scripts/forms/listbox/form-duallistbox.js")
                }
                ,
                error: function (data) {
                }
            });
        }

        function loadRole(key) {
            var path = "{{route('user-management.roles.edit')}}";
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                url: path,
                type: 'GET',
                data: {name:key},
                beforeSend: function(){
                    $('#action-card').block({
                        message: '<div class="ft-loader icon-spin font-large-1"></div>',
                        // timeout: 2000, //unblock after 2 seconds
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
                    $('#action-card').empty();
                    $('#action-card').html(data.theView);
                },
                complete:function(){
                    $('#action-card').unblock();
                    $.getScript("http://127.0.0.1:8000/vendors/js/forms/listbox/jquery.bootstrap-duallistbox.min.js")
                    $.getScript("http://127.0.0.1:8000/js/scripts/forms/listbox/form-duallistbox.js")
                }
                ,
                error: function (data) {
                }
            });
        }

        function trashRole(key) {
            console.log("Trash "+key);
        }
    </script>
@endpush