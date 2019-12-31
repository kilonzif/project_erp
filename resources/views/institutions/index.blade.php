@extends('layouts.user-management')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">
@endpush
@section('um-content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item">user management
                        </li>
                        <li class="breadcrumb-item active">Institutions
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
                        <h4 class="card-title">Add Institution</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form id="add-form" action="{{route('user-management.institutions.create')}}" method="post">
                                @csrf
                                <div id="hidden-input"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('name') ? ' form-control-warning' : '' }}">
                                            <label for="permission_name">Institution Name <span class="required">*</span></label>
                                            <input type="text" required min="2" name="name" placeholder="Institution Name" class="form-control" value="{{ old('name') }}" id="permission_name">
                                            @if ($errors->has('name'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('name') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('contact') ? ' form-control-warning' : '' }}">
                                            <label for="contact">Phone Number</label>
                                            <input type="text" name="contact" placeholder="Phone Number" class="form-control" value="{{ old('contact') }}" id="contact">
                                            @if ($errors->has('contact'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('contact') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                                            <label for="email">Institution Email </label>
                                            <input type="email" placeholder="Email Address" min="2" name="email" class="form-control" value="{{ old('email') }}" id="email">
                                            @if ($errors->has('email'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('email') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('country') ? ' form-control-warning' : '' }}">
                                            <label for="country">Country <span class="required">*</span></label>
                                            <select class="form-control" required name="country" id="country">
                                                <option selected disabled>Choose country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{$country->id}}">{{$country->country}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('country'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('country') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="skin skin-square">
                                                <input type="radio" name="is_uni" value="1" checked id="is_uni_true">
                                                <label for="is_uni_true" class="">University</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="skin skin-square">
                                                <input type="radio" name="is_uni" value="0" id="is_uni_false">
                                                <label for="is_uni_false" class="">Partner</label>
                                            </div>
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
                        <h4 class="card-title">Institutions</h4>
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
                                <table class="table table-striped table-bordered" id="institutions_table">
                                    <thead>
                                    <tr>
                                        <th>Institution Name</th>
                                        <th>Country</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th width="80px"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($institutions->count() > 0)
                                        @foreach($institutions as $institution)
                                            <tr>
                                                <td>{{$institution->name}}</td>
                                                <td>{{$institution->country->country}}</td>
                                                <td>{{$institution->email}}</td>
                                                <td>{{$institution->contact}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a href="#action-card" onclick="editInstitution('{{$institution->id}}')" class="btn btn-s btn-secondary">
                                                            {{__('Edit')}}</a>
                                                        {{--<a class="dropdow-item btn {{($user->status == 0)?'btn-success' : 'btn-danger'}} btn-s"--}}
                                                           {{--href="{{ route('user-management.user.delete',[\Illuminate\Support\Facades\Crypt::encrypt($institution->id)]) }}"--}}
                                                           {{--onclick="event.preventDefault();--}}
                                                                   {{--document.getElementById('delete-form-{{$count}}').submit();">--}}
                                                            {{--@if($user->status == 0)--}}
                                                                {{--{{ __('Activate') }}--}}
                                                            {{--@else--}}
                                                                {{--{{ __('Deactivate') }}--}}
                                                            {{--@endif--}}
                                                        {{--</a>--}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No Institutions Set</td>
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
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>
    <script>

        $('#institutions_table').dataTable();

        function editInstitution(key) {
            var check1 = "{{asset('vendors/js/forms/icheck/icheck.min.js')}}";
            var check2 = "{{asset('js/scripts/forms/checkbox-radio.js')}}";
            var path = "{{route('user-management.institution.edit')}}";
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
                    });
                },
                success: function(data){
                    $('#action-card').empty();
                    $('#action-card').html(data.theView);
                },
                complete:function(){
                    $('#action-card').unblock();
                    $.getScript(check1);
                    $.getScript(check2);
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }
    </script>
@endpush