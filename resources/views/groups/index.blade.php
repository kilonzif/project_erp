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
            <h3 class="content-header-title mb-0">Groups</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Groups
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
                        <h4 class="card-title">Groups</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <thead>
                                    <tr>
                                        <th>Group Name</th>
                                        <th>Description</th>
                                        <th width="120px">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($groups->count() > 0)
                                        @foreach($groups as $group)
                                            <tr>
                                                <td>{{$group->display_name}}</td>
                                                <td>{{$group->description}}</td>
                                                <td>
                                                    <button class="btn btn-sm square">
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
                                            <td colspan="3" class="text-center">No Group Set</td>
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
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Group</h4>
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
                            <form action="{{route('user-management.groups.create')}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="group_name">Group Name</label>
                                            <input type="text" required min="2" name="group_name" class="form-control" id="group_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="display_name">Group Display Name</label>
                                            <input type="text" name="display_name" class="form-control" id="display_name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{--<label for="group_desp">Group Description</label>--}}
                                            <input type="text" name="group_desp" placeholder="Group Description" class="form-control" id="group_desp">
                                        </div>
                                    </div>
                                </div>
                                @if($permissions->count() > 0)
                                    <div class="form-group">
                                        <select multiple="multiple" name="permissions[]" size="10" class="duallistbox">
                                            @foreach($permissions as $permission)
                                                <option value="{{$permission->id}}">{{$permission->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="message">No permissions set</label>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                                        Save</button>
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
@endpush