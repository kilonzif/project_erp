@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/datetime/bootstrap-datetimepicker.css') }}">

@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Reports</a>
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
            <div class="col-md-3">
                <h5 style="margin-top: 10px;">Download All the Templates: <i class="fa fa-file-zip-o"></i></h5>
            </div>
            <div class="col-md-8">
                <a href="{{ url('download?file_path=/public/English_indicatorTemplates.zip') }}"
                   class="btn btn-s btn-outline-secondary">
                    <i class="fa fa-cloud-download"></i> English <span class="flag-icon flag-icon-gb"></span>
                </a>
                <a href="{{ url('download?file_path=/public/French_indicatorTemplates.zip') }}"
                   class="btn btn-s btn-outline-secondary">
                    <i class="fa fa-cloud-download"></i> French <span class="flag-icon flag-icon-fr"></span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
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
                                    </div>
                                </h6>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            @if (\Auth::user()->hasRole('webmaster|super-admin'))
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="ace_officer">Select ACE Officer <span class="required">*</span></label>
                                                        <select required="required" name="ace_officer" class="form-control select2" id="ace_officer">
                                                            <option selected value="" disabled>Select Officer</option>
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
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="reporting_period">Reporting Period<span class="required">*</span></label>
                                                        <select class="form-control" name="reporting_period">
                                                            @foreach($reporting_periods as $period)
                                                                @php

                                                                    $full_period = \App\Http\Controllers\ReportFormController::getReportingName($period->id);

                                                                @endphp
                                                                <option value="{{$period->id}}">{{$full_period}}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" value="">
                                                        @if ($errors->has('reporting_period'))
                                                            <p class="text-right">
                                                                <small class="warning text-muted">{{ $errors->first('reporting_period') }}</small>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if (\Auth::user()->hasRole('ace-officer'))

                                                    <input type="hidden" required name="submission_date" class="form-control form-control datepicker"
                                                           data-date-format="D-M-YYYY" value="{{ old('date_submission')? old('date_submission') : date('Y-m-d') }}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="reporting_period">Reporting Period</label>
                                                        <select class="form-control" name="reporting_period" readonly>
                                                            @foreach($active_period as $period)
                                                                @php
                                                                    $full_period = \App\Http\Controllers\ReportFormController::getReportingName($period->id);
                                                                @endphp
                                                                <option selected value="{{$period->id}}">{{$full_period}}</option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <fieldset>
                                                        <label for="date_submission">Date of Submission <span class="required">*</span></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control form-control" readonly disabled="disabled"
                                                                   data-date-format="D-M-YYYY" value="{{date('Y-m-d')}}">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            @endif

                                            <div class="{{(\Auth::user()->hasRole('ace-officer'))?'col-md-6' :'col-md-4'}}">
                                                <fieldset class="form-group">
                                                    <label>Language<span class="required">*</span></label>
                                                    <select class="form-control" name="language" id="language">
                                                        <option value="">Select One</option>
                                                        <option value="english">English</option>
                                                        <option value="french">French</option>
                                                    </select>
                                                </fieldset>
                                            </div>

                                            <div class="col-md-6">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">Which DLR will you like to report on?</label>
                                                    <select name="indicator" required class="select form-control" id="indicator" onchange=" loadFields()">
                                                        <option value="">Select One</option>
                                                        @foreach($indicators as $indicator)
                                                            <option value="{{$indicator->id}}">
                                                                {{$indicator->title}}

                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('indicator'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted" id="indicator-error">{{ $errors->first('indicator') }}</small>
                                                        </p>
                                                    @endif
                                                </fieldset>
                                            </div>

                                            @if(\Auth::user()->hasRole('webmaster|super-admin'))
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="date_submission">Date of Submission <span class="required">*</span></label>

                                                        <input type="date" required min="5" value="{{ old('date_submission')? old('date_submission') : date('Y-m-d') }}"
                                                               name="submission_date" class="form-control" id="submission_date">
                                                        @if ($errors->has('date_submission'))
                                                            <p class="text-right">
                                                                <small class="warning text-muted">{{ $errors->first('date_submission') }}</small>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                //$indicators = $project->indicators->where('is_parent','=', 1)->where('status','=', 1)->where('upload','=', 1);
                            @endphp
                            @if(false)
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
                            @endif
                            <div class="row">
                                <div class="col-md-8">
                                    <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-secondary mb-2"> <i class="ft-arrow-left"></i> Go Back</a>
                                    <button type="submit" name="save" value="continue" id="save-button" class="btn btn-light mb-2"> <i class="ft-save"></i> Proceed to Indicators</button>
                                </div>
                                <div class="col-md-1">

                                </div>
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

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
{{--<script src="../../../app-assets/js/scripts/forms/input-groups.min.js"></script>--}}



@push('vendor-script')

    <script src="{{ asset('vendors/js/pickers/dateTime/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

@endpush


{{--@push('end-script')--}}

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>--}}

<script>


    $(function () {
        $('.datepicker').datetimepicker();
    });

    $('.select2').select2({
        placeholder: "Select ACE",
        allowClear: true
    });

    $('#save-button').on('click',function () {
        console.log("Hello");
    })
</script>