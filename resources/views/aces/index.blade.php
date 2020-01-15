@extends('layouts.user-management')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
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
                        <li class="breadcrumb-item active">user management</li>
                        <li class="breadcrumb-item active">ACEs
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
                        <h4 class="card-title">Add ACEs</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form id="add-form" action="{{route('user-management.aces.create')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div id="hidden-input"></div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group{{ $errors->has('name') ? ' form-control-warning' : '' }}">
                                            <label for="permission_name">ACE Name <span class="required">*</span></label>
                                            <input type="text" required min="2" name="name" placeholder="ACE Name" class="form-control" value="{{ old('name') }}" id="permission_name">
                                            @if ($errors->has('name'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('name') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group{{ $errors->has('acronym') ? ' form-control-warning' : '' }}">
                                            <label for="acronym">Acronym <span class="required">*</span></label>
                                            <input type="text" required placeholder="Acronym" min="2" name="acronym" class="form-control" value="{{ old('acronym') }}" id="acronym">
                                            @if ($errors->has('acronym'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('acronym') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group{{ $errors->has('dlr') ? ' form-control-warning' : '' }}">
                                            <label for="dlr">Grant Amount </label>
                                            <input type="number" placeholder="DLR Amount" min="0" name="dlr" class="form-control"
                                                   value="{{ old('dlr') }}" id="dlr" style="text-align: right;">
                                            @if ($errors->has('dlr'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('dlr') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group{{ $errors->has('currency') ? ' form-control-warning' : '' }}">
                                            <label for="field">{{__('Currency')}} <span class="required">*</span></label>
                                            <select class="form-control" required name="currency" id="currency">
                                                <option value="" selected disabled>--Choose--</option>
                                                @foreach($currency as $cc)
                                                    <option {{($cc->id == old('currency'))?"selected":""}} value="{{$cc->id}}">
                                                        {{$cc->name.' - '.$cc->symbol}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('currency'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('currency') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group{{ $errors->has('field') ? ' form-control-warning' : '' }}">
                                            <label for="field">{{__('Field of Study')}} <span class="required">*</span></label>
                                            <select class="form-control" required name="field" id="field">
                                                <option value="">--Choose--</option>
                                                <option value="Agriculture">Agriculture</option>
                                                <option value="Health">Health</option>
                                                <option value="STEM">STEM</option>
                                                <option value="Education">Education</option>
                                                <option value="Applied Soc. Sc.">Applied Soc. Sc.</option>
                                              </select>
                                            @if ($errors->has('field'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('field') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                                            <label for="email">Email <span class="required">*</span></label>
                                            <input type="email" required placeholder="Email Address" min="2" name="email" class="form-control" value="{{ old('email') }}" id="email">
                                            @if ($errors->has('email'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('email') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('ace_type') ? ' form-control-warning' : '' }}">
                                            <label for="ace_type">Type of Centres<span class="required">*</span></label>
                                            <select class="form-control" required name="ace_type" id="ace_type">
                                                <option value="">--Choose--</option>
                                                <option value="engineering">Colleges of Engineering </option>
                                                <option value="emerging">Emerging Centre</option>
                                                <option value="ACE">ACE</option>
                                            </select>
                                            @if ($errors->has('ace_type'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('ace_type') }}</small>
                                                </p>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('university') ? ' form-control-warning' : '' }}">
                                            <label for="country">University <span class="required">*</span></label>
                                            <select class="form-control" required name="university" id="university">
                                                <option value="">Choose university</option>
                                                @foreach($universities as $university)
                                                    <option value="{{$university->id}}">{{$university->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('university'))
                                                <p class="text-right">

                                                    <small class="warning text-muted">{{ $errors->first('email') }}</small>
                                                    <small class="warning text-muted">{{ $errors->first('university') }}</small>

                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
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
                                    <div style="margin-top: 2rem;" class="col-md-2">
                                        <div class="form-group">
                                            <div class="skin skin-square">
                                                <input type="radio" name="active" value="1" checked id="active">
                                                <label for="active" class="">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="margin-top: 2rem;" class="col-md-2">
                                        <div class="form-group">
                                            <div class="skin skin-square">
                                                <input type="radio" name="active" value="0" id="inactive">
                                                <label for="inactive" class="">Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                                            Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">ACEs</h4>
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
                                <table class="table table-bordered table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>ACE</th>
                                        <th>Institution</th>
                                        <th>Country</th>
                                        <th>Contact</th>
                                        <th width="80px">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($aces->count() > 0)
                                        @foreach($aces as $ace)
                                            <tr>
                                                <td><a href="{{route('user-management.aces.profile',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">
                                                        {{$ace->name}}</a></td>
                                                <td>{{$ace->university->name}}</td>
                                                <td>{{$ace->university->country->country}}</td>
                                                <td>{{$ace->contact}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a class="btn btn-primary square" href="{{route('user-management.aces.profile',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">
                                                        <i class="icon-eye"></i>
                                                    </a>
                                                    <a class="btn btn-secondary square" href="#action-card" onclick="editAce('{{\Illuminate\Support\Facades\Crypt::encrypt($ace->id)}}')">
                                                        <i class="icon-pencil"></i>
                                                    </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No ACEs Set</td>
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
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>
    <script>

        $('.select2').select2({
            placeholder: "Select Programmes",
            allowClear: true
        });

        function editAce(key) {
            var path = "{{route('user-management.ace.edit-view')}}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                complete:function(data){
                    $('#action-card').unblock();
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }

        {{--function edit_view(key) {--}}
            {{--var path = "{{route('user-management.contacts.edit_view')}}";--}}
            {{--$.ajaxSetup(    {--}}
                {{--headers: {--}}
                    {{--'X-CSRF-Token': $('meta[name=_token]').attr('content')--}}
                {{--}--}}
            {{--});--}}
            {{--$.ajax({--}}
                {{--url: path,--}}
                {{--type: 'GET',--}}
                {{--data: {id:key},--}}
                {{--beforeSend: function(){--}}
                    {{--$('#edit_view').block({--}}
                        {{--message: '<div class="ft-loader icon-spin font-large-1"></div>',--}}
                        {{--overlayCSS: {--}}
                            {{--backgroundColor: '#ccc',--}}
                            {{--opacity: 0.8,--}}
                            {{--cursor: 'wait'--}}
                        {{--},--}}
                        {{--css: {--}}
                            {{--border: 0,--}}
                            {{--padding: 0,--}}
                            {{--backgroundColor: 'transparent'--}}
                        {{--}--}}
                    {{--});;--}}
                {{--},--}}
                {{--success: function(data){--}}
                    {{--$('#edit_view').empty();--}}
                    {{--$('#edit_view').html(data.theView);--}}
                    {{--// console.log(data)--}}
                {{--},--}}
                {{--complete:function(){--}}
                    {{--$('#edit_view').unblock();--}}
                {{--}--}}
                {{--,--}}
                {{--error: function (data) {--}}
                    {{--console.log(data)--}}
                {{--}--}}
            {{--});--}}
    </script>

        <script>
            function changeFile(key) {
                $('#'+key).show();
            }
            
        </script>

@endpush