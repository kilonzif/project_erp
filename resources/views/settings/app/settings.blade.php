@extends('layouts.app')



@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/datepicker/datepicker3.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/datepicker/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
@endpush
@push('other-styles')
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item active">App Settings
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-content" style="">
                        <div class="card-body">
                            <h6>Application Name</h6>
                            <form action="{{route('settings.app_settings.save_name')}}" method="post">
                                @csrf
                                <fieldset>
                                    <div class="input-group">
                                        <input type="text" name="name" style="color: #d68e2d;"
                                               value="{{$apps->where('option_name', '=', 'app_name')->pluck('display_name')->first()}}"
                                               required class="form-control" placeholder="Name">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary square" type="submit"><i class="ft-save"></i> Save</button>
                                        </div>
                                    </div>
                                    @if ($errors->has('name'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('name') }}</small>
                                        </p>
                                    @endif
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-content" style="">
                        <div class="card-body">
                            <h6>Application Notification Email</h6>
                            <form action="{{route('settings.app_settings.save_email')}}" method="post">
                                @csrf
                                <fieldset>
                                    <div class="input-group">
                                        <input type="email" style="color: #d68e2d;" name="email"
                                               value="{{$apps->where('option_name', '=', 'app_email')->pluck('display_name')->first()}}"
                                               class="form-control" placeholder="Email" aria-describedby="button-addon2">
                                        <div class="input-group-append" id="button-addon2">
                                            <button class="btn btn-primary square" type="submit"><i class="ft-save"></i> Save</button>
                                        </div>
                                    </div>
                                </fieldset>
                                @if ($errors->has('email'))
                                    <p class="text-right">
                                        <small class="warning text-muted">{{ $errors->first('email') }}</small>
                                    </p>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-content" style="">
                        <div class="card-body">
                            <h6>Report Status for Report Generation</h6>
                            <form action="{{route('settings.app_settings.save_generation_status')}}" method="post">
                                @csrf
                                <fieldset>
                                    @php
                                        $status = $apps->where('option_name', '=', 'generation_status')->pluck('option_value')->first();
                                        if (!isset($status)){
                                            $status = 101;
                                        }
                                    @endphp
                                    <div class="input-group">
                                        <select name="status" class="form-control" required id="status">
                                            <option value="">Select Status</option>
                                            <option @if($status == 1) selected @endif value="1">Submitted</option>
                                            <option @if($status == 101) selected @endif value="101">Report Verified</option>
                                        </select>
                                        <div class="input-group-append" id="button-addon00">
                                            <button class="btn btn-primary square" type="submit"><i class="ft-save"></i> Save</button>
                                        </div>
                                    </div>
                                </fieldset>
                                @if ($errors->has('status'))
                                    <p class="text-right">
                                        <small class="warning text-muted">{{ $errors->first('status') }}</small>
                                    </p>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="period_id">
            <div class="card-header bg-dark bgsize-darken-4 white card-header">
                <h4 class="card-title">Set Reporting Period</h4>
            </div>
            <div class="card-content" style="">
                <div class="card-body">
                    <div id="form_template">
                        <form action="{{route('settings.app_settings.save_reporting_period')}}" method="post">
                            <div class="row">
                                @csrf
                                <div class="col-md-5 form-group">
                                    <label>Starting Period<span class="required">*</span></label>
                                    <div class="input-group">
                                        <input type='text' name="period_start"  class="form-control datepicker" value="{{ old('period_start') }}" placeholder="Month &amp; Year" required
                                        />
                                        <div class="input-group-append">
                                                <span class="input-group-text">
                                                  <span class="fa fa-calendar-o"></span>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 form-group">
                                    <label>Ending Period<span class="required">*</span></label>
                                    <div class="input-group">
                                        <input type='text' name="period_end" class="form-control datepicker" value="{{ old('period_end') }}" placeholder="Month &amp; Year" required
                                        />
                                        <div class="input-group-append">
                                                <span class="input-group-text">
                                                  <span class="fa fa-calendar-o"></span>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 form-group">
                                    <button class="btn btn-primary" style="margin-top: 1.7rem;" type="submit"><i class="ft-save"></i> Save</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{--table--}}
                    <div class="row">
                        <div class="col-md-12 offset">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th style="width: 100px">Reporting Period #</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Active Status</th>
                                    <th>Actions</th>
                                </tr>
                                @php
                                    $counter=0;
                                @endphp
                                @foreach($periods as $period)
                                    @php
                                        $counter=$counter+1;
                                        $monthNum1=date('m',strtotime($period->period_start));
                                        $monthName1 = date("M", mktime(0, 0, 0, $monthNum1, 10));
                                        $year1 = date('Y',strtotime($period->period_start));
                                        $start = $monthName1 .', '.$year1;

                                        $monthNum2=date('m',strtotime($period->period_end));
                                        $monthName2 = date("M", mktime(0, 0, 0, $monthNum2, 10));
                                        $year2 = date('Y',strtotime($period->period_start));
                                        $end = $monthName2 .', '.$year2;

                                    @endphp
                                    <tr>
                                        <td>{{$counter}}</td>
                                        <td>{{$start}}</td>
                                        <td>{{$end}}</td>
                                        <td>
                                            @php
                                                $type = "success";
                                                $text = "Active";
                                                if ($period->active_period == false) {$type = "danger"; $text = "Closed";};
                                            @endphp<span class="badge badge-{{$type}}">{{$text}}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="#form_template" onclick="edit_period('{{\Illuminate\Support\Facades\Crypt::encrypt($period->id)}}')" class="btn btn-s btn-secondary">
                                                    <i class="ft-edit"></i></a>
                                                <a href="{{route('settings.app_settings.delete_reporting_period',[\Illuminate\Support\Facades\Crypt::encrypt($period->id)])}}"
                                                   class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this Period?');"
                                                   title="Delete Report"><i class="ft-trash-2"></i></a>


                                            </div>


                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="positions_card">
            <div class="card-content" style="">
                <div class="card-header bg-dark bgsize-darken-4 white card-header">
                    <h4 class="card-title">Add Contact Positions</h4>
                </div>
                <div class="card-body">
                    <div id="position_template">
                        <form action="{{route('settings.app_settings.save_position')}}" method="post">
                            <div class="row">
                                @csrf
                                <div class="col-md-4 form-group {{ $errors->has('position_title') ? ' form-control-warning' : '' }}">
                                    <label>Position Title<span class="required">*</span></label>
                                    <div class="input-group">
                                        <input type='text' name="position_title"  class="form-control" value="{{ old('position_title') }}" placeholder="Role" required
                                        />
                                    </div>
                                    @if ($errors->has('position_title'))
                                        <p class="text-right mb-0">
                                            <small class="warning text-muted">{{ $errors->first('position_title') }}</small>
                                        </p>
                                    @endif
                                </div>
                                <div class="col-md-4 form-group {{ $errors->has('position_type') ? ' form-control-warning' : '' }}">
                                    <label>Position Type<span class="required">*</span></label>
                                    <select name="position_type" id="position_type" class="form-control" required>
                                        <option value="">Select One</option>
                                        <option value="ACE level"> ACE level</option>
                                        <option value="Institutional level">Institutional level</option>
                                        <option value="Country level"> Country level</option>
                                        <option value="Experts level">Experts level</option>
                                        <option value="Sectoral Board level">Sectoral Board level</option>
                                    </select>
                                    @if ($errors->has('position_type'))
                                        <p class="text-right mb-0">
                                            <small class="warning text-muted">{{ $errors->first('position_type') }}</small>
                                        </p>
                                    @endif
                                </div>
                                <div class="col-md-2 form-group{{ $errors->has('position_rank') ? ' form-control-warning' : '' }}">
                                    <label>Position Rank<span class="required">*</span></label>
                                    <div class="input-group">
                                        <input type='number' name="position_rank" class="form-control" value="{{ old('position_rank') }}" placeholder="Rank e.g 1" required
                                        min="1"/>
                                    </div>
                                    @if ($errors->has('position_rank'))
                                        <p class="text-right mb-0">
                                            <small class="warning text-muted">{{ $errors->first('position_rank') }}</small>
                                        </p>
                                    @endif
                                </div>
                                <div class="col-md-2 form-group">
                                    <button class="btn btn-primary" style="margin-top: 1.7rem;" type="submit"><i class="ft-save"></i> Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{--table--}}
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width: 100px">Position #</th>
                            <th>Title</th>
                            <th>Position Type</th>
                            <th>Rank</th>
                            <th>Actions</th>
                        </tr>
                        @php
                            $counter=0;
                        @endphp
                        @foreach($roles as $role)
                            @php
                                $counter=$counter+1;
                            @endphp
                            <tr>
                                <td>{{$counter}}</td>
                                <td>{{$role->position_title}}</td>
                                <td>{{$role->position_type}}</td>
                                <td>{{$role->rank}}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="#position_template" onclick="edit_position('{{\Illuminate\Support\Facades\Crypt::encrypt($role->id)}}')" class="btn btn-s btn-secondary">
                                            <i class="ft-edit"></i></a>
                                        <a href="{{route('settings.app_settings.delete_position',[\Illuminate\Support\Facades\Crypt::encrypt($role->id)])}}"
                                           class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this Period?');"
                                           title="Delete Report"><i class="ft-trash-2"></i></a>


                                    </div>


                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('app-assets/js/core/libraries/bootstrap.min.js')}}" type="text/javascript"></script>

@endpush
@push('end-script')

    <script src="{{asset('app-assets/datepicker/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script>
        $('.datepicker').datepicker({
            format:"mm-yyyy",
            startView:"months",
            minViewMode:"months",

        });
        $('.datepicker').attr('autocomplete', 'off');

        function edit_position(key) {

            var path = "{{route('settings.app_settings.edit_position')}}";
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
                    $('#position_template').block({
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
                    $('#position_template').empty();
                    $('#position_template').html(data.theView);
                    // console.log(data)
                },
                complete:function(){
                    $('#position_template').unblock();
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }





        //Script to call the edit view for period
        function edit_period(key) {

            var path = "{{route('settings.app_settings.edit_reporting_period')}}";
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
                    $('#form_template').block({
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
                    $('#form_template').empty();
                    $('#form_template').html(data.theView);
                    // console.log(data)
                },
                complete:function(){
                    $('#form_template').unblock();
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }


        (function(window, document, $) {
            'use strict';

            // Custom Show / Hide Configurations
            $('.contact-repeater,.repeater-default').repeater({
                show: function () {
                    $(this).slideDown();
                },
                hide: function(remove) {
                    if (confirm('Are you sure you want to remove this item?')) {
                        $(this).slideUp(remove);
                    }
                }
            });


        })(window, document, jQuery);

        function select_options() {
            var input_select_opts = $('.select_options').closest('.select_options');
            console.log(input_select_opts);
        }

        function changeSubmisssion(key) {

            var path = "{{route('settings.app_settings.change_deadline_status')}}"
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                url: path,
                type: 'get',
                data: {id:key},
                beforeSend: function(){
                    $('#deadline-box').block({
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
                    if (data.key == 0){
                        $('#open-btn').css("display","inline-block");
                        $('#lock-btn').css("display","none");
                    }
                    else{
                        $('#lock-btn').css("display","inline-block");
                        $('#open-btn').css("display","none");
                    }
                    $('#deadline-message').html("<span class='text-"+data.type+"'><strong>"+data.message+"</strong></span>")
                },
                complete:function(){
                    $('#deadline-box').unblock();
                    // $.getScript("http://127.0.0.1:8000/vendors/js/forms/select/select2.full.min.js")
                }
                ,
                error: function (data) {
                }
            });
        }



        $('.select2').select2({
            placeholder: "Select Indicator",
            allowClear: true
        });
    </script>
@endpush