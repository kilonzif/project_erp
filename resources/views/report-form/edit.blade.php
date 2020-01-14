@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
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
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Reports</a>
                        </li>
                        <li class="breadcrumb-item active">Edit report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <h5 class="pb-1 pt-1 mt-1 text-danger text-uppercase">All fields marked * are required</h5>
                @if($project->indicators->count() > 0)
                    <form action="{{route('report_submission.update_report')}}" id="indicators-form" method="post">
                        @csrf
                        <input type="hidden" name="report_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($report->id)}}">
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="ace_officer">Select ACE Officer <span class="required">*</span></label>
                                                    <select name="ace_officer" class="form-control select2" id="ace_officer" required>
                                                        <option value="">Select Officer</option>
                                                        @foreach($ace_officers as $key=>$ace_officer)
                                                            <option @if($key == $report->user_id) selected="selected" @endif value="{{\Illuminate\Support\Facades\Crypt::encrypt($key)}}">{{$ace_officer}}</option>
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
                                                                $start_period = date('m-Y',strtotime($period->period_start));
                                                                $end_period = date('m-Y',strtotime($period->period_end));
                                                                $monthNum1=date('m',strtotime($period->period_start));
                                                                $monthName1 = date("M", mktime(0, 0, 0, $monthNum1, 10));
                                                                $year1 = date('Y',strtotime($period->period_start));
                                                                $start = $monthName1 .', '.$year1;
                                                                $monthNum2=date('m',strtotime($period->period_end));
                                                                $monthName2 = date("M", mktime(0, 0, 0, $monthNum2, 10));
                                                                $year2 = date('Y',strtotime($period->period_end));
                                                                $end =$monthName2 .', '.$year2;
                                                                $full_period = $start ."    -  ". $end;
                                                            @endphp
                                                            <option {{($report->reporting_period_id == $period->id)  ? "selected":""}} value="{{$period->id}}">{{$full_period}}</option>
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="reporting_period">Reporting Period<span class="required">*</span></label>
                                                    @php
                                                        $start_period = date('m-Y',strtotime($reporting_period->period_start));
                                                        $end_period = date('m-Y',strtotime($reporting_period->period_end));
                                                        $monthNum1=date('m',strtotime($start_period));
                                                        $monthName1 = date("M", mktime(0, 0, 0, $monthNum1, 10));
                                                        $year1 =date('Y',strtotime($reporting_period->period_start));
                                                        $start = $monthName1 .', '.$year1;
                                                        $monthNum2=date('m',strtotime($reporting_period->period_end));
                                                        $monthName2 = date("M", mktime(0, 0, 0, $monthNum2, 10));
                                                        $year2 = date('Y',strtotime($reporting_period->period_end));
                                                        $end =$monthName2 .', '.$year2;
                                                        $full_period = $start ."    -  ". $end;
                                                    @endphp
                                                    <h6>{{$full_period}}</h6>
                                                    <input type="hidden" name="reporting_period" value="{{$reporting_period->id}}">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="date_submission">Date of Submission <span class="required">*</span></label>
                                                @if(\Auth::user()->hasRole('webmaster|super-admin'))
                                                    <input type="date" required min="5" value="{{ old('date_submission')? old('date_submission') : $report->submission_date }}"
                                                           name="submission_date" class="form-control" id="submission_date">
                                                    @if ($errors->has('date_submission'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('date_submission') }}</small>
                                                        </p>
                                                    @endif
                                                @else
                                                    <input type="text" disabled="disabled" readonly value="{{ $report->submission_date }}" class="form-control">
                                                @endif
                                            </div>
                                        </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="label">Fiduciary Report Submitted ?</label>
                                                    <select name="fiduciary_report" id="fiduciary_report" class="form-control">
                                                        <option {{($report->fiduciary_report == '1')  ? "selected":""}} value="1">YES</option>
                                                        <option {{($report->fiduciary_report == '0')  ? "selected":""}} value="0">NO</option>
                                                    </select>
                                                    @if ($errors->has('fiduciary_report'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('fiduciary_report') }}</small>
                                                        </p>
                                                    @endif
                                                </div>


                                            </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display: inline; margin-right:10px;">
                            <a href="{{route('report_submission.upload_indicator', [\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}" class="btn btn-secondary mb-2">
                                <i class="ft-upload"></i> Upload Indicators</a>
                            <a  class="pb-1 pt-1 mt-1 text-danger text-uppercase" href="{{route('report_submission.edit',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}" style="margin-left:10px;">Preview and scroll down this page to submit the report</a>
                        </div>
                        @foreach($indicators as $indicator)
                            <div class="card mb-1">
                                <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                                    {{--<h6 class="card-title"></h6>--}}
                                    <strong>{{$indicator->identifier}}:</strong> {{$indicator->title}}
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
                                                <span class="text-secondary text-bold-500">Unit of Measure: {{$indicator->unit_measure}}</span>
                                            </small>
                                        </h5>
                                        <table class="table table-bordered table-striped">
                                            @if($indicator->indicators->count() > 0)
                                                @php

                                                        $sub_indicators = $indicator->indicators->where('status','=',1);
                                                        $filter_index = ['national_and_men','national_and_women','regional_and_men','regional_and_women'];
                                                        $pdo_indicator_1 = config('app.indicator_3');

                                                        $pdo_indicator_2 = config('app.indicator_2');

                                                        $indicator_5_2_filter = ['national','regional'];
                                                        $indicator_4_1_filter = ['international','national','gap-assessment','regional','self-evaluation','course'];
                                                        $indicator_4_2_filter = ['non-regional','regional'];
                                                        $indicator_5_1_filter = ['External_Revenue_National ','External_Revenue_Regional'];
                                                        $indicator_7_3_filter = ['national','self_evaluation','international'];
                                                        $indicator_identifier = (string)$indicator->identifier;
                                                        $counter = 0;
                                                    //dd($sub_indicators);
                                                @endphp
                                                @foreach($sub_indicators as $sub_indicator)
                                                    {{--{{dd($sub_indicator)}}--}}
                                                @if($sub_indicator->status == 0) @continue @endif
                                                @php
                                                    $pdo_indicator = str_replace('-','_',\Illuminate\Support\Str::slug(strtolower($indicator_identifier)));
                                                @endphp
                                                    <tr>
                                                        <td>{{$sub_indicator->title}} <span class="required">*</span>
                                                            {{--@if($sub_indicator->unit_measure)--}}
                                                                {{--<br><small><strong>Unit of Measure: </strong>{{$sub_indicator->unit_measure->title}}</small>--}}
                                                            {{--@endif--}}
                                                        </td>
                                                        <td style="width: 200px">

                                                            <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}"
                                                            style="margin-bottom: 0;">



                                                                @if($indicator->parent_id == 3)

                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                           value="{{$pdo_1[$pdo_indicator][$pdo_indicator_1[$pdo_indicator][$counter]]}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">

                                                                    @elseif($indicator->parent_id == 4)

                                                                    {{--{{dd($pdo_indicator_2)}};--}}

                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                           value="{{$pdo_2[$pdo_indicator][$pdo_indicator_2[$pdo_indicator][$counter]]}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                                @endif
                                                                @if(false)

                                                            @if($indicator->identifier == "4.1")
                                                                <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                       value="{{$indicator_4_1[$indicator_4_1_filter[$counter]]}}"
                                                                       class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                            @elseif($indicator->identifier == "4.2")
                                                                <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                       value="{{$indicator_4_2[$indicator_4_2_filter[$counter]]}}"
                                                                       class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                            {{--@elseif($indicator->identifier == "5.1")--}}
                                                                {{--<input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"--}}
                                                                       {{--value="{{$indicator_5_1[$indicator_5_1_filter[$counter]]}}"--}}
                                                                       {{--class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">--}}
                                                            @elseif($indicator->identifier == "5.2")
                                                                <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                       value="{{$indicator_5_2[$indicator_5_2_filter[$counter]]}}"
                                                                       class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                            @elseif($indicator->identifier == "7.3")
                                                                <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                       value="{{$indicator_7_3[$indicator_7_3_filter[$counter]]}}"
                                                                       class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                            @elseif(isset($values[$sub_indicator->id]))
                                                                <input type="number" step="0.01" min="0" id="indicator_{{$sub_indicator->id}}"
                                                                       class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}" placeholder="Eg. 1"
                                                                       value="{{old('indicators.'.$sub_indicator->id)?old('indicators.'.$sub_indicator->id):$values[$sub_indicator->id]}}"
                                                                       name="indicators[{{$sub_indicator->id}}]">
                                                            @else
                                                                <input type="number" step="0.01" min="0" id="indicator_{{$sub_indicator->id}}"
                                                                       class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}" placeholder="Eg. 1"
                                                                       value="{{old('indicators.'.$sub_indicator->id)?old('indicators.'.$sub_indicator->id):''}}"
                                                                       name="indicators[{{$sub_indicator->id}}]">
                                                            @endif
                                                            @endif
                                                                @if(false)
                                                                    <input type="number" step="0.01" min="0" id="indicator_{{$sub_indicator->id}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}" placeholder="Eg. 1"
                                                                           value="{{old('indicators.'.$sub_indicator->id)?old('indicators.'.$sub_indicator->id):''}}"
                                                                           name="indicators[{{$sub_indicator->id}}]">
                                                                @endif

                                                                @if ($errors->has('indicators.'.$sub_indicator->id))
                                                                    <p class="text-right mb-0">
                                                                        <small class="warning text-muted">{{ $errors->first('indicators.'.$sub_indicator->id) }}</small>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    @php
                                                        $counter += 1;
                                                    @endphp
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="form-grou{{ $errors->has('indicators.'.$indicator->id) ? ' form-control-warning' : '' }}">
                                                            @if ($indicator->identifier == 11)
                                                                @if(isset($values[$indicator->id]))
                                                                    <select name="indicators[{{$indicator->id}}]" id="indicator_{{$indicator->id}}"
                                                                            class="form-control">
                                                                        <option @if($values[$indicator->id] == '0') selected @endif value="0">No</option>
                                                                        <option @if($values[$indicator->id] == '1') selected @endif value="1">Yes</option>
                                                                    </select>
                                                                @else
                                                                    <select name="indicators[{{$indicator->id}}]" id="indicator_{{$indicator->id}}"
                                                                            class="form-control">
                                                                        <option value="0">No</option>
                                                                        <option value="1">Yes</option>
                                                                    </select>
                                                                @endif
                                                            @else
                                                                @if(isset($values[$indicator->id]))
                                                                    <input type="number" step="0.01" min="0" id="indicator_{{$indicator->id}}" name="indicators[{{$indicator->id}}]"
                                                                           value="{{old('indicators.'.$indicator->id)?old('indicators.'.$indicator->id):$values[$indicator->id]}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$indicator->id) ? ' is-invalid' : '' }}"
                                                                           placeholder="Eg. 1">
                                                                @else
                                                                    <input type="number" step="0.01" min="0" id="indicator_{{$indicator->id}}" name="indicators[{{$indicator->id}}]"
                                                                           value="{{old('indicators.'.$indicator->id)?old('indicators.'.$indicator->id): ''}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$indicator->id) ? ' is-invalid' : '' }}"
                                                                           placeholder="Eg. 1">
                                                                @endif
                                                                @if ($errors->has('indicators.'.$indicator->id))
                                                                    <p class="text-right mb-0">
                                                                        <small class="warning text-muted">{{ $errors->first('indicators.'.$indicator->id) }}</small>
                                                                    </p>
                                                                @endif
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
                            <div class="col-lg-12">
                                <div class="card mb-1">
                                    <div class="card-header p-1 card-head-inverse bg-grey-blue">
                                        <strong>Challenges faced (if any)</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <textarea class="form-control" placeholder="Comment" name="report_comment">@isset($comment){{$comment->comments}}@endisset</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-secondary mb-2"> <i class="ft-arrow-left"></i> Go Back</a>
                                <button type="submit" name="continue" value="continue" id="save-button" class="btn btn-light mb-2"> <i class="ft-save"></i> Save and continue later</button>
                                <button type="submit" name="save" value="save" class="btn btn-info mb-2"> <i class="ft-upload-cloud"></i> Save</button>
                            </div>
                            <div class="col-md-1">

                            </div>
                            <div class="col-md-3 text-right">
                                <button type="submit" name="submit" value="complete" class="btn btn-success mb-2"> <i class="ft-check-circle"></i> Submit Full Report</button>
                            </div>
                        </div>
                    </form>
                @else
                    <h2 class="center">No Indicators available</h2>
                @endif

            </div>
        </div>
    </div>


@endsection
@push('vendor-script')


<script type="text/javascript" src="{{ asset("js/scripts/customizer.js") }}"></script>
<script src="{{ asset("js/scripts/pages/chat-application.js")}}" type="text/javascript"></script>

<script type="text/javascript" >

  $('.customizer-toggle').on('click',function(){
        $('.customizer').toggleClass('open');
    });

</script>
@endpush
@push('end-script')

@endpush


