@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/extended/form-extended.css')}}">
    <style>
        span.symbol{
            font-size: 12px;
        }
    </style>
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('user-management.aces')}}">ACEs</a>
                        </li>
                        <li class="breadcrumb-item active">{{$ace->acronym}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-dark bg-darken-4 white">
                        <h4 class="card-title">{{$ace->name}}</h4>
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

                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td ><strong>ACE</strong><br>{{$ace->name}}</td>
                                    <td ><strong>Acronym</strong><br>{{$ace->acronym}}</td>
                                    <td><strong>Institution</strong><br>{{$ace->university->name}}</td>
                                    <td><strong>Contact</strong><br>{{$ace->contact}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong><br>{{$ace->email}}</td>
                                    <td><strong>Grant Amount1</strong><br>{{$ace->grant1}} - {{$currency1->name}}</td>
                                    <td><strong>Grant Amount2</strong><br>{{$ace->grant2}} - {{$currency2->name}}</td>
                                    <td><strong>Field</strong><br>{{$ace->field}}</td>
                                </tr>
                            </table>

                            <div class="form-group">
                                <a class="btn btn-primary btn-min-width mr-1 mb-1" href="{{route('user-management.ace.indicator_one',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">Institutional Readiness</a>
                                <a class="btn btn-primary btn-min-width mr-1 mb-1" href="{{route('user-management.ace.baselines',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}" role="button">Indicator Baselines</a>
                                <a class="btn btn-primary btn-min-width mr-1 mb-1"href="{{route('user-management.ace.targets',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}"> <i class="ft-plus-circle"></i> New Targets</a>
                                @if($target_years->isNotEmpty())
                                <div class="btn-group mr-1 mb-1">
                                    <button type="button" class="btn bg-warning bg-darken-4 btn-min-width dropdown-toggle white" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"><i class="ft-crosshair"></i>  Select Target Year</button>
                                    <div class="dropdown-menu">
                                        @foreach($target_years as $target_year)
                                            <a class="dropdown-item" href="{{route('user-management.ace.targets',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id),$target_year->id])}}">
                                            {{date('F Y',strtotime($target_year->start_period))." - ".date('F Y',strtotime($target_year->end_period))}}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
        {{--<div class="row">--}}
            <div class="col-md-7">
                <div class="card">
                    <h6 class="card-header p-1 card-head-inverse bg-primary" style="border-radius:0">
                        Contact Group
                    </h6>
                    <div class="card-content">
                        <div class="card-body table-responsive">
                            <div id="edit_contact">
                                <form class="form" action="{{route('user-management.mailinglist.save')}}" method="post">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="hidden" value="{{ $ace->id }}" name="ace_id" id="ace_id" class=" form-control">
                                                <div class="form-group{{ $errors->has('mailing_name') ? ' form-control-warning' : '' }}">

                                                    <label for="email">Name <span class="required">*</span></label><input type="text" required placeholder="Name" min="2" name="mailing_name" class="form-control" value="{{ old('mailing_name') }}" id="mailing_name">
                                                    @if ($errors->has('mailing_name'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('mailing_name') }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('mailing_title') ? ' form-control-warning' : '' }}">
                                                    <label for="mailing_title">Position <span class="required">*</span></label>
                                                    <select class="form-control" name="mailing_title">
                                                        <option value="" selected disabled>Select Title</option>
                                                        <option value="Center Leader">Center Leader</option>
                                                        <option value="Deputy Center Leader">Deputy Center Leader</option>
                                                        <option value="Finance Officer">Finance Officer</option>
                                                        <option value="Procurement Officer">Procurement Officer</option>
                                                        <option value="Project / Program Manager">Project / Program Manager</option>
                                                        <option value="PSC Member">MEL Officer</option>
                                                    </select>

                                                    @if ($errors->has('mailing_title'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('mailing_title') }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('mailing_phone') ? ' form-control-warning' : '' }}">
                                                    <label for="email">Phone <span class="required">*</span></label><input type="text" required placeholder="Phone Number" min="2" name="mailing_phone" class="form-control" value="{{ old('mailing_phone') }}" id="mailing_phone">
                                                    @if ($errors->has('mailing_phone'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('mailing_phone') }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                                                    <label for="email">Email <span class="required">*</span></label><input type="email" required placeholder="Email Address" min="2" name="mailing_email" class="form-control" value="{{ old('mailing_email') }}" id="mailing_email">
                                                    @if ($errors->has('mailing_email'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('mailing_email') }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-primary" style="margin-top: 1.9rem">
                                                    Submit
                                                </button><br><br>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <br>
                            </div>

                            <table class="table table-striped table-bordered all_indicators">
                                <thead>
                                <tr>
                                    <th> Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Title</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($aceemails as $contact)
                                    <tr>
                                        <td>{{$contact->contact_name}}</td>
                                        <td>{{$contact->email}}</td>
                                        <td>{{$contact->contact_phone}}</td>
                                         <td>{{$contact->contact_title}}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">

                                                    @if($contact->edit_status == true)
                                                <a href="#edit_contact" onclick="edit_contact('{{\Illuminate\Support\Facades\Crypt::encrypt($contact->id)}}')" class="btn btn-s btn-secondary">
                                                    <i class="ft-edit"></i></a>
                                                <a href="{{route('user-management.mailinglist.delete',[\Illuminate\Support\Facades\Crypt::encrypt($contact->id)])}}"
                                                   class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this Contact?');"
                                                   title="Delete Report"><i class="ft-trash-2"></i></a>
                                                        @else
                                                    <a href="#" class="btn btn-s btn-secondary">
                                                        <i class="ft-eye"></i></a>
                                                        @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card">
                    <h6 class="card-header p-1 card-head-inverse bg-primary" style="border-radius:0">
                        Programmes
                    </h6>
                    <div class="card-content">
                        <div class="card-body table-responsive">
                            <form action="{{route('user-management.ace.add_courses',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Course Names <span class="required"></span> </label>
                                            <textarea class="form-control" placeholder="course names" name="ace_programmes"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                                        Save</button>
                                </div>
                            </form>
                            <br>
                            @php
                                $allcourses=explode(';',$ace->programmes)  ;
                            @endphp

                            @foreach($allcourses as $course)
                            @if($course != "")
                                <div class="btn-group">
                                    <button type="button" class="btn btn-md btn-outline-secondary">{{$course}}</button>
                                    <a href="{{ route('user-management.ace.delete_course',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id),$course]) }}" type="button"
                                       onclick="return confirm('Are you sure you want to delete this Course?');" data-id="{{$course}}" class="btn btn-md btn-outline-secondary" data-toggle="remove" aria-haspopup="true" aria-expanded="false">
                                        <span class="fa fa-remove" style="color:red"></span>
                                    </a>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-md-12">
                <h4>DLR Indicators
                    @if($ace_dlrs->count() <= 0)
                        <small> - <span class="mr-md-1 text-danger">No DLRs Added.</span></small>
                    @endif
                </h4>
            </div>

            @if($ace_dlrs->count() > 0)
                @foreach($ace_dlrs as $ace_dlr)
                    <div class="col-md-6">
                        <div class="card">
                            <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                                {{$ace_dlr->indicator_title}}
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    </ul>
                                </div>
                            </h6>
                            <div class="card-content collapse show">
                                <div class="card-body table-responsive">
                                    <form action="{{route('settings.save_dlr_indicators_cost',[$ace->id])}}" method="post">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{$ace_dlr->id}}">
                                        <div class="row">
                                            <label class="col-md-7" style="padding-top: 0.9rem;">Maximum SDR per DLR</label>
                                            <div class="col-md-5">
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="number" class="form-control" name="max"
                                                           value="{{isset($dlr_max_costs[$ace_dlr->id])?$dlr_max_costs[$ace_dlr->id]:0}}">
                                                    {{--<div class="form-control-position">--}}
                                                        {{--<span class="symbol">{{$ace->currency->symbol}}</span>--}}
                                                    {{--</div>--}}
                                                </fieldset>
                                            </div>
                                        </div>
                                        {{--                            @if($unit > $max)--}}
                                        {{--<p class="text-right">--}}
                                        {{--<small class="danger text-muted">The Unit Cost is more than the Maximum</small>--}}
                                        {{--</p>--}}
                                        {{--@endif--}}

                                        @if($ace_dlr->indicators->count() > 0)
                                            <table class="table table-striped table-bordered">
                                                <tr>
                                                    <th>DLR Indicators</th>
                                                    <th style="width: 200px;">Cost per Unit (SDR)</th>
                                                    {{--<th style="width: 200px;">Maximum SDR per DLR</th>--}}
                                                </tr>
                                                @php
                                                    $sub_indicators = $ace_dlr->indicators->where('status','=',1);
                                                @endphp
                                                @foreach($sub_indicators as $sub_indicator)
                                                    @php
                                                        $unit = 0;
                                                            if(isset($dlr_unit_costs[$sub_indicator->id])){
                                                                $unit = $dlr_unit_costs[$sub_indicator->id];
                                                            }
                                                    @endphp
                                                    <tr>
                                                        <td>{{$sub_indicator->indicator_title}}</td>
                                                        <td>
                                                            <fieldset class="form-group position-relative has-icon-left mb-0">
                                                                <input type="number" class="form-control" id="single_{{$sub_indicator->id}}"
                                                                       value="{{$unit}}" name="single[{{$sub_indicator->id}}]">
                                                                <div class="form-control-position">
                                                                    <span class="symbol">{{$ace->currency->symbol}}</span>
                                                                </div>
                                                            </fieldset>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-secondary square">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/extended/form-inputmask.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>
    <script>

        $('.select2').select2({
            placeholder: "Select Courses",
            allowClear: true
        });
        // Currency in USD
        {{--$('.currency-inputmask').inputmask("{{$ace->currency->symbol}} 99999999");--}}


        function edit_contact(key) {
            var path = "{{route('user-management.mailinglist.edit')}}";
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
                    $('#edit_contact').block({
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
                    $('#edit_contact').empty();
                    $('#edit_contact').html(data.theView);
                    // console.log(data)
                },
                complete:function(){
                    $('#edit_contact').unblock();
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }


        $("#removeCourse").click(function(){
            var id = $(this).data("course");
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax(
                {
                    url: "users/"+id,
                    type: 'DELETE',
                    data: {
                        "id": id,
                        "_token": token,
                    },
                    success: function (){
                        console.log("it Works");
                    }
                });

        });
    </script>

@endpush