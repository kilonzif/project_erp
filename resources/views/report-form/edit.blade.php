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
                                                  $full_period = \App\Http\Controllers\ReportFormController::getReportingName($period->id);
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
                                                        $full_period = \App\Http\Controllers\ReportFormController::getReportingName($reporting_period->id);
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


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display: inline; margin-right:10px;">
                                <a href="{{route('report_submission.upload_indicator', [\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}" class="btn btn-secondary mb-2">
                                    <i class="ft-upload"></i> Upload Indicators</a>
                                <a  class="pb-1 pt-1 mt-1 text-danger text-uppercase" href="{{route('report_submission.edit',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}" style="margin-left:10px;">Preview and scroll down this page to submit the report</a>
                        </div>
                        {{--indicators3--}}
                        <div class="card mb-1">
                            <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                                {{$indicators->title}}
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
                                            <span class="text-secondary text-bold-500">Unit of Measure: {{$indicators->unit_measure}}</span>
                                        </small>
                                    </h5>
                                    @if($indicators->indicators->count() > 0)
                                        @php
                                            $sub_indicators = $indicators->indicators->where('parent_id','=',$indicators->id);

                                            $pdo_indicator_1 = config('app.indicator_3');
                                            $pdo_indicator_41 = config('app.indicator_41');
                                            $pdo_indicator_52 = config('app.indicator_52');
                                            $pdo_indicator_42 = config('app.indicator_42');



                                            $counter_one = 0;
                                        @endphp
                                        @foreach($sub_indicators as $sub_indicator)
                                            @php
                                                $indicator_identifier = (string)$sub_indicator->identifier;
                                                $pdo_indicator = str_replace('-','_',\Illuminate\Support\Str::slug(strtolower($indicator_identifier)));
                                              $child_dlr = \App\Indicator::where('parent_id',$sub_indicator->id)->get();


                                            @endphp

                                            @if($child_dlr->isNotEmpty())

                                            <table class="table table-bordered table-striped">

                                                    @if($sub_indicator->status == 0) @continue @endif

                                                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                                                        <strong>{{$sub_indicator->identifier}}:</strong> {{$sub_indicator->title}}
                                                    </h6>
                                                    @php $counter = 0; @endphp

                                                    @foreach($child_dlr as $child)


                                                        <tr>
                                                            <td>{{$child->title}} <span class="required">*</span>
                                                            </td>
                                                            <td style="width: 200px">

                                                                @if($sub_indicator->parent_id == 1)

                                                                <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}"
                                                                     style="margin-bottom: 0;">
                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$child->id}}]"
                                                                           value="{{!empty($pdo_1) ?$pdo_1[$pdo_indicator][$pdo_indicator_1[$pdo_indicator][$counter]]:0}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">

                                                                </div>
                                                                @elseif($sub_indicator->parent_id == 2)
                                                                    {{--programme accreditation--}}
                                                                    <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}" style="margin-bottom: 0;">
                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                           value="{{!empty($pdo_41) ?$pdo_41[$pdo_indicator][$pdo_indicator_41[$pdo_indicator][$counter]]:0}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}"> </div>


                                                                @elseif($sub_indicator->parent_id == 3)
                                                                    {{--4.2 publications--}}
                                                                    <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}" style="margin-bottom: 0;">
                                                                        <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                               value="{{!empty($pdo_42) ?$pdo_42[$pdo_indicator][$pdo_indicator_42[$pdo_indicator][$counter]]:0}}"
                                                                               class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}"> </div>


                                                                @elseif($sub_indicator->parent_id == 6)
                                                                    @php
                                                                        $pdo_indicator = 'pdo_indicator_52';
                                                                    @endphp
                                                                    {{--internships 5.2--}}
                                                                    <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}"
                                                                         style="margin-bottom: 0;">
                                                                        <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$child->id}}]"
                                                                               value="{{!empty($pdo_52) ?$pdo_52[$pdo_indicator][$pdo_indicator_52[$pdo_indicator][$counter]]:0}}"
                                                                               class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                                    </div>




                                                                @endif

                                                            </td>
                                                        </tr>
                                                        @php
                                                            $counter += 1;
                                                        @endphp
                                                    @endforeach


                                            </table>
                                            @else
                                                {{--@php dd($pdo_41); @endphp--}}


                                                <table class="table table-bordered table-striped">

                                                    <tr>
                                                        <td>{{$sub_indicator->title}} <span class="required">*</span>
                                                        </td>
                                                        <td style="width: 200px">
                                                            @if($sub_indicator->parent_id == 2)
                                                                {{--programme accreditation--}}
                                                                <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}" style="margin-bottom: 0;">
                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                           value="{{$pdo_41['pdo_indicator_41'][$pdo_indicator_41['pdo_indicator_41'][$counter_one]]}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}"> </div>
                                                            @endif
                                                        </td>
                                                </table>
                                            @endif

                                            @php
                                                $counter_one += 1;
                                            @endphp



                                        @endforeach

                                    @endif


                                </div>
                            </div>
                        </div>








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


