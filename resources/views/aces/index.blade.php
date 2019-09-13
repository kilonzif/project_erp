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
            <h3 class="content-header-title mb-0">ACEs</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">ACEs
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <h4 class="card-title">Add ACEs</h4>
        <form id="add-form" action="{{route('user-management.aces.create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card" id="action-card">
                        <div class="card-header">
                            <h4 class="card-title">ACE Details</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
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
                                            <label for="dlr">DLR Amount <span class="required">*</span></label>
                                            <input type="number" required placeholder="DLR Amount" min="0" name="dlr" class="form-control"
                                                   value="{{ old('dlr') }}" id="dlr" style="text-align: right;">
                                            @if ($errors->has('dlr'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('dlr') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group{{ $errors->has('currency') ? ' form-control-warning' : '' }}">
                                            <label for="field">{{__('Currency')}} <span class="required">*</span></label>
                                            <select class="form-control" required name="currency" id="currency">
                                                <option value="">--Choose--</option>
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
                                    <div class="col-md-2">
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
                                        <div class="form-group{{ $errors->has('contact_name') ? ' form-control-warning' : '' }}">
                                            <label for="contact_name">Contact Person Name</label>
                                            <input type="text" required min="2" name="contact_name" placeholder="Contact Person Name" class="form-control" value="{{ old('contact_name') }}" id="contact_name">
                                            @if ($errors->has('contact_name'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('contact_name') }}</small>
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
                                            <label for="contact">Phone Number <span class="required">*</span></label>
                                            <input type="text" name="contact" required placeholder="Phone Number" class="form-control" value="{{ old('contact') }}" id="contact">
                                            @if ($errors->has('contact'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('contact') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('contact_person_phone') ? ' form-control-warning' : '' }}">
                                            <label for="contact">Contact Person's Number</label>
                                            <input type="text" name="contact_person_phone" placeholder="Phone Number" class="form-control" value="{{ old('contact_person_phone') }}" id="contact_person_phone">
                                            @if ($errors->has('contact_person_phone'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('contact_person_phone') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group{{ $errors->has('courses') ? ' form-control-warning' : '' }}">
                                            <label for="courses">Programmes <span class="required">*</span></label>
                                            <select name="courses[]" id="courses" class="select2 form-control" multiple style="width: 100%">
                                                @foreach($courses as $course)
                                                    <option value="{{$course->id}}">{{$course->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('courses'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('courses') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('contact_email') ? ' form-control-warning' : '' }}">
                                            <label for="contact_email">Contact Person Email</label>
                                            <input type="email" placeholder="Contact Person Email" min="6" name="contact_email" class="form-control" value="{{ old('contact_email') }}" id="contact_email">
                                            @if ($errors->has('contact_email'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('contact_email') }}</small>
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
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('ace_type') ? ' form-control-warning' : '' }}">
                                            <label for="ace_type">Type of Centres</label>
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
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('position') ? ' form-control-warning' : '' }}">
                                            <label for="position">Contact Person's Position</label>
                                            <input type="text" name="position" placeholder="Position" class="form-control" value="{{ old('position') }}" id="position">
                                            @if ($errors->has('position'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('position') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h4 class="card-title">INDICATOR 1(INSTITUTION READINESS)</h4>
            {{ csrf_field() }}
            @foreach($requirements as $key=>$req)
                <div class="row">
                    <div class="col-12">
                        <div class="card" id="action-card">
                            <div class="card-header">
                                <h4 class="card-title">{{$req}}</h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                <input type="hidden" name="requirement[]" value="{{$req}}" >
                                <div class="row">
                                    <div style="margin-top: 2rem;" class="col-md-1">
                                        <div class="form-group">
                                            <label>Finalised</label>
                                        </div>
                                    </div>
                                    <div style="margin-top: 2rem;" class="col-md-1">
                                        <div class="form-group">
                                            <div class="skin skin-square">
                                                <label for="finalised" class="">Yes</label>
                                                <input type="radio" name="{{'finalised'.$key}}" value="1"  id="finalised">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="margin-top: 2rem;" class="col-md-1">
                                        <div class="form-group">
                                            <div class="skin skin-square">
                                                <label for="finalised" class="">NO</label>
                                                <input type="radio" name="{{'finalised'.$key}}" value="0"  id="finalised">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Submission Date:</label>
                                            <input type="date" class="form-control" name="submission_date[]"  id="submission_date" value="{{old('submission_date1[]')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>File Upload:</label>
                                            <input type="file" class="form-control" id="file_name[]" name="file_name[]" value="{{old('file_name')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('web_link[]') ? ' form-control-warning' : '' }}">
                                            <label>URL:</label>
                                            <input type="text" name="url[]" placeholder="url" class="form-control"  value="{{ old('url[]') }}" id="url">
                                            @if ($errors->has('url[]'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('url[]') }}</small>
                                                </p>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('web_link[]') ? ' form-control-warning' : '' }}">
                                            <label for="web_link1">Web Link</label>
                                            <input type="text" name="web_link[]" placeholder="web_link" class="form-control"  value="{{ old('web_link[]') }}" id="web_link1">
                                            @if ($errors->has('web_link[]'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('web_link[]') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group{{ $errors->has('comments[]') ? ' form-control-warning' : '' }}">
                                            <label for="comments1">Comments</label>
                                            <input type="text" name="comments[]" placeholder="comments" class="form-control"  value="{{ old('comments[]') }}" id="comments1">
                                            @if ($errors->has('comments[]'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('comments[]') }}</small>
                                                </p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                {{--<hr>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                            Save</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
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
            var path = "{{route('user-management.ace.edit')}}";
            var check1 = "{{asset('vendors/js/forms/icheck/icheck.min.js')}}";
            var check3 = "{{asset('vendors/js/forms/select/select2.full.min.js')}}";
            var check2 = "{{asset('js/scripts/forms/checkbox-radio.js')}}";
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
                    // alert(key);
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
                    // console.log(data);
                    $('#action-card').empty();
                    $('#action-card').html(data.theView);
                },
                complete:function(data){
                    // console.log(data.ace);
                    $('#action-card').unblock();
                    $.getScript(check3);
                    $.getScript(check2);
                    $.getScript(check1);
                    $.getScript($('.select2').select2({placeholder: 'Select Courses',allowClear: true}));
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    </script>

        <script>
            function changeFile(key) {
                $('#'+key).show();
            }

        </script>

@endpush