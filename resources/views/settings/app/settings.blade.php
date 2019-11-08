@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
@endpush
@push('other-styles')
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
@endpush
@section('content')
    {{--@php dd(old('indicator.3')) @endphp--}}
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Application Settings</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
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
            <div class="col-md-6">
                <div class="card" id="deadline-box">
                    <div class="card-content" style="">
                        <div class="card-body">
                            <h6>Report Submission Activation</h6>
                            <hr>
                            <div class="mb-1">
                                @php
                                    $action = $apps->where('option_name', '=', 'app_deadline')->pluck('status')->first();
                                @endphp
                                @if(isset($action))
                                    <span title="Click to Open Report Submission" style="display: {{($action == 0)? 'inline-block':'none'}}"
                                          class="btn btn-success square" onclick="changeSubmisssion(0)" id="open-btn">
                                        <i class="ft-check"></i> Open Submission
                                    </span>

                                    <span style="display: {{($action == 1)? 'inline-block':'none'}}" title="Click to Lock Report Submission"
                                          class="btn btn-danger square text-right" onclick="changeSubmisssion(1)" id="lock-btn">
                                        <i class="ft-lock"></i> Close Submission
                                    </span>
                                    <span class="ml-2" id="deadline-message"></span>
                                @endif
                            </div>

                            <form action="{{route('settings.app_settings.save_deadline')}}" method="post">
                                @csrf
                                <fieldset>
                                    <div class="input-group">
                                        <input type="date" name="deadline" style="color: #d68e2d;"
                                               value="{{$apps->where('option_name', '=', 'app_deadline')->pluck('display_name')->first()}}"
                                               class="form-control" placeholder="Date" aria-describedby="button-addon2">
                                        <div class="input-group-append" id="button-addon2">
                                            <button class="btn btn-primary square" type="submit"><i class="ft-save"></i> Save</button>
                                        </div>
                                    </div>
                                    @if ($errors->has('deadline'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('deadline') }}</small>
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
                    <div class="card-header">
                        <h4 class="card-title">Set Reporting Period</h4>
                    </div>
                    <div class="card-content" style="">
                        <div class="card-body">
                            <div id="form_template">
                                <form action="{{route('settings.app_settings.save_reporting_period')}}" method="post">
                                    <div class="row">
                                        @csrf
                                        <div class="col-md-6">
                                            <label>Starting Period</label>
                                            <input type="month" id="period_start" name="period_start" min="1900-March" max="today" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label>End</label>
                                            <input type="month" id="period_start" name="period_end" min="1900-March" max="today"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 20px;">
                                        <div class="col-md-6 offset-md-3" >
                                            <input type="submit" name="submit" value="Save Reporting Period" class="btn btn-primary mb-2">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{--table--}}
                            <div class="row">
                                <div class="col-md-8 offset-2">
                                    <h4 class="mb-lg-3 card-header">Reporting Periods</h4>
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th>No</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Actions</th>
                                        </tr>
                                        @php
                                            $counter=0;
                                        @endphp
                                        @foreach($periods as $period)
                                            @php
                                                $counter=$counter+1;
                                            @endphp
                                            <tr>
                                                <td>{{$counter}}</td>
                                                <td>{{$period->period_start}}</td>
                                                <td>{{$period->period_end}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a href="{{route('settings.app_settings.edit_reporting_period',[\Illuminate\Support\Facades\Crypt::encrypt($period->id)])}}" class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Period" >
                                                            <i class="ft-edit-3"></i></a>
                                                        {{--<a class="btn btn-secondary square" href="#form_template" onclick="editPeriod('{{\Illuminate\Support\Facades\Crypt::encrypt($period->id)}}')">--}}
                                                        {{--<i class="icon-pencil"></i>--}}
                                                        {{--</a>--}}
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
            </div>
        </div>
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script>
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