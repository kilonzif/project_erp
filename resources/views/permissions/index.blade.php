@extends('layouts.user-management')
@push('vendor-styles')
@endpush
@push('other-styles')
@endpush
@section('um-content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Permissions</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item active">Permissions
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card" id="action-card">
                    <div class="card-header">
                        <h4 class="card-title">Add Permission</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form id="add-form" action="{{route('user-management.permissions.create')}}" method="post">
                                @csrf
                                <div id="hidden-input"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('permission_name') ? ' form-control-warning' : '' }}">
                                            <label for="permission_name">Permission Name <span class="required">*</span></label>
                                            <input type="text" required min="2" name="permission_name" class="form-control" value="{{ old('permission_name') }}" id="permission_name">
                                            @if ($errors->has('permission_name'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('permission_name') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('display_name') ? ' form-control-warning' : '' }}">
                                            <label for="display_name">Permission Display Name <span class="required">*</span></label>
                                            <input type="text" name="display_name" class="form-control" value="{{ old('display_name') }}" id="display_name">
                                            @if ($errors->has('display_name'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('display_name') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group{{ $errors->has('permission_desp') ? ' form-control-warning' : '' }}">
                                            {{--<label for="group_desp">Group Description</label>--}}
                                            <input type="text" name="permission_desp" placeholder="Permission Description" value="{{ old('permission_desp') }}" class="form-control" id="permission_desp">
                                            @if ($errors->has('permission_desp'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('permission_desp') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                                        Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Permissions</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <thead>
                                    <tr>
                                        <th>Permission Name</th>
                                        <th>Permission code</th>
                                        <th>Permission Description</th>
                                        <th width="120px">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($permissions->count() > 0)
                                        @foreach($permissions as $permission)
                                            <tr>
                                                <td>{{$permission->display_name}}</td>
                                                <td>{{$permission->name}}</td>
                                                <td>{{$permission->description}}</td>
                                                <td>
                                                    <button class="btn btn-sm square" href="#action-card" onclick="editPermission('{{$permission->id}}')">
                                                        <i class="icon-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger square">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No Permission Set</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('vendor-script')
@endpush
@push('end-script')
    <script>
        function editPermission(key) {
            var path = "{{route('user-management.permissions.edit')}}";
            var path_update = "{{route('user-management.permissions.update')}}";
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
                    });;
                },
                success: function(data){
                    $('input#permission_name').val(data.name);
                    $('input#display_name').val(data.display_name);
                    $('input#permission_desp').val(data.description);
                    $("form#add-form").attr("action", path_update);
                    $('div#hidden-input').html('<input type="hidden" name="id" value="'+data.id+'"><input type="hidden" name="_method" value="PUT">');
                },
                complete:function(){
                    $('#action-card').unblock();
                }
                ,
                error: function (data) {
                }
            });
        }
    </script>
@endpush