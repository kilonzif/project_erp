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
            <h3 class="content-header-title mb-0">New Report Submission</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Submitted Reports</a>
                        </li>
                        <li class="breadcrumb-item active">Add new report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                {{--<h3 class="pb-1 pt-1 mt-1 bg-lighten-1 bg-teal white pl-1">{{$project->title}}</h3>--}}
                {{--<div class="card">--}}
                    {{--<div class="card-content">--}}
                        {{--<div class="card-body">--}}
                            {{--<div class="row custom-dd">--}}
                                {{--<div class="col-md-4">--}}
                                    {{--<dt><h6 class="text-bold-500">Grant ID</h6></dt>--}}
                                    {{--<dd><h6>{{$project->grant_id}}</h6></dd>--}}
                                    {{--<dt><h6 class="text-bold-500">Total Grant (US$)</h6></dt>--}}
                                    {{--<dd><h6>{{$project->total_grant}}</h6></dd>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-4">--}}
                                    {{--<dt><h6 class="text-bold-500">Project Start Date</h6></dt>--}}
                                    {{--<dd><h6>{{$project->start_date}}</h6></dd>--}}
                                    {{--<dt><h6 class="text-bold-500">Project End Date</h6></dt>--}}
                                    {{--<dd><h6>{{$project->end_date}}</h6></dd>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-4">--}}
                                    {{--<dt><h6 class="text-bold-500">Project Coordinator</h6></dt>--}}
                                    {{--<dd><h6>{{$project->project_coordinator}}</h6></dd>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                @if($me->isSubmissionOpen())
                    <h5 class="pb-1 pt-1 mt-1 text-danger text-uppercase">All fields marked * are required</h5>
                    @if($project->indicators->count() > 0)
                        <form action="{{route('report_submission.save_report')}}" id="indicators-form" method="post">
                            @csrf
                            <input type="hidden" name="project_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($project->id)}}">
                            <div class="card mb-1">
                                <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                                    Report Information
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        </ul>
                                    </div>
                                </h6>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            @if (\Auth::user()->hasRole('webmaster|super-admin'))
                                                {{--<div class="col-md-8">--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--<label for="ace_id">Select Ace <span class="required">*</span></label>--}}
                                                        {{--<select name="ace_id" class="form-control select2" id="ace_id" required>--}}
                                                            {{--<option value="">Select Ace</option>--}}
                                                            {{--@foreach($aces as $ace)--}}
                                                                {{--<option {{old('ace_id')? "selected": ''}} value="{{\Illuminate\Support\Facades\Crypt::encrypt($ace->id)}}">{{$ace->name}}</option>--}}
                                                            {{--@endforeach--}}
                                                        {{--</select>--}}
                                                        {{--@if ($errors->has('ace_id'))--}}
                                                            {{--<p class="text-right">--}}
                                                                {{--<small class="warning text-muted">{{ $errors->first('ace_id') }}</small>--}}
                                                            {{--</p>--}}
                                                        {{--@endif--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="ace_officer">Select ACE Officer <span class="required">*</span></label>
                                                        <select name="ace_officer" class="form-control select2" id="ace_officer" required>
                                                            <option value="">Select Officer</option>
                                                            @foreach($ace_officers as $key=>$ace_officer)
                                                                <option {{old('ace_officer')? "selected": ''}} value="{{\Illuminate\Support\Facades\Crypt::encrypt($key)}}">{{$ace_officer}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('ace_officer'))
                                                            <p class="text-right">
                                                                <small class="warning text-muted">{{ $errors->first('ace_officer') }}</small>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            {{--@else--}}
                                                {{--<input type="hidden" name="ace_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt(\Auth::user()->ace)}}">--}}
                                            @endif
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="submission_period">Submission Period (Start Date)<span class="required">*</span></label>
                                                    <input type="date" required value="{{ old('start')? old('start') : '' }}"
                                                           name="start" class="form-control" id="start">
                                                    {{--<div class='input-group'>--}}
                                                        {{--<input type='text' value="{{old('submission_period')}}" name="submission_period" class="form-control daterange" />--}}
                                                        {{--<div class="input-group-append">--}}
                                                            {{--<span class="input-group-text">--}}
                                                              {{--<span class="fa fa-calendar"></span>--}}
                                                            {{--</span>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                    @if ($errors->has('submission_period'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('submission_period') }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="submission_period">Submission Period (End Date) <span class="required">*</span></label>
                                                <input type="date" required value="{{ old('end')? old('end') : '' }}"
                                                       name="end" class="form-control" id="end">
                                                @if ($errors->has('end'))
                                                    <p class="text-right">
                                                        <small class="warning text-muted">{{ $errors->first('end') }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="date_submission">Date of Submission <span class="required">*</span></label>
                                                    @if(\Auth::user()->hasRole('webmaster|super-admin'))
                                                        <input type="date" required min="5" value="{{ old('date_submission')? old('date_submission') : date('Y-m-d') }}"
                                                           name="submission_date" class="form-control" id="submission_date">
                                                        @if ($errors->has('date_submission'))
                                                            <p class="text-right">
                                                                <small class="warning text-muted">{{ $errors->first('date_submission') }}</small>
                                                            </p>
                                                        @endif
                                                    @else
                                                        <input type="text" disabled="disabled" readonly value="{{ date('Y-m-d') }}" class="form-control">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $indicators = $project->indicators->where('parent_id','=',0)->where('status','=',1);
                            @endphp
                            @foreach($indicators as $indicator)
                                <div class="card mb-1">
                                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                                        {{--<h6 class="card-title"></h6>--}}
                                        <strong>{{"Indicator ".$indicator->identifier}}:</strong> {{$indicator->title}}
                                        {{--<br>--}}
                                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            </ul>
                                        </div>
                                    </h6>
                                    <div class="card-content collapse show">
                                        <div class="card-body table-responsive">
                                            <h5>
                                                <small>
                                                    <span class="text-secondary text-bold-500">Unit of Measure:</span>
                                                    {{$indicator->unit_measure}}
                                                </small>
                                            </h5>
                                            <table class="table table-bordered table-striped">
                                                @if($indicator->indicators->count() > 0)
                                                    @php
                                                        $sub_indicators = $indicator->indicators->where('status','=',1);
                                                    @endphp
                                                        @foreach($sub_indicators as $sub_indicator)
                                                            <tr>
                                                                <td>{{$sub_indicator->title}} <span class="required">*</span></label>
                                                                    @if($sub_indicator->unit_measure)
                                                                        <br><small><strong>Unit of Measure: </strong>{{$sub_indicator->unit_measure->title}}</small>
                                                                    @endif
                                                                </td>
                                                                <td style="width: 200px">
                                                                    <div class="form-grou{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}">
                                                                    <input type="number" step="0.01"  id="indicator_{{$sub_indicator->id}}" min="0"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}" placeholder="Eg. 1"
                                                                           value="{{old('indicators.'.$sub_indicator->id)}}" name="indicators[{{$sub_indicator->id}}]">

                                                                    @if ($errors->has('indicators.'.$sub_indicator->id))
                                                                        <p class="text-right mb-0">
                                                                            <small class="warning text-muted">{{ $errors->first('indicators.'.$sub_indicator->id) }}</small>
                                                                        </p>
                                                                    @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                @else
                                                    <tr>
                                                    <td colspan="2">
                                                        <div class="form-grou{{ $errors->has('indicators.'.$indicator->id) ? ' form-control-warning' : '' }}">

                                                        @if ($indicator->identifier == 11)
                                                                <select name="indicators[{{$indicator->id}}]" id="indicator_{{$indicator->id}}"
                                                                        class="form-control">
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                        @else
                                                            <input type="number" step="0.01" min="0" id="indicator_{{$indicator->id}}"
                                                                   name="indicators[{{$indicator->id}}]" value="{{old('indicators.'.$indicator->id)}}"
                                                                   class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$indicator->id) ? ' is-invalid' : '' }}" placeholder="Eg. 1">
                                                        @endif
                                                        @if ($errors->has('indicators.'.$indicator->id))
                                                            <p class="text-right mb-0">
                                                                <small class="warning text-muted">{{ $errors->first('indicators.'.$indicator->id) }}</small>
                                                            </p>
                                                        @endif
                                                        </div>
                                                    </td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-md-8">
                                    <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-secondary mb-2"> <i class="ft-arrow-left"></i> Go Back</a>
                                    <button type="submit" name="save" value="continue" id="save-button" class="btn btn-light mb-2"> <i class="ft-save"></i> Save and Continue</button>
                                    <button type="submit" name="toIndicators" value="toIndicators" class="btn btn-info mb-2"> <i class="ft-upload-cloud"></i> Submit & Proceed to Indicator Uploads</button>
                                </div>
                                <div class="col-md-1">

                                </div>
                                {{--<div class="col-md-3 text-right">--}}
                                    {{--<button type="submit" disabled name="submit" value="complete" class="btn btn-success mb-2"> <i class="ft-check-circle"></i> Submit Full Report</button>--}}
                                {{--</div>--}}
                            </div>
                        </form>
                    @else
                        <h2 class="center">No Indicators available</h2>
                    @endif
                @else
                    <h1 class="text-center text-info mt-4">
                        Report Submission has been closed!<br>
                        <small class="blue-grey mt-3">Please contact the Monitoring and Evaluation Officer for assistance.</small>
                    </h1>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script>
        $('.select2').select2({
            placeholder: "Select ACE",
            allowClear: true
        });

        $('#save-button').on('click',function () {
            console.log("Hello");
        })
    </script>
@endpush