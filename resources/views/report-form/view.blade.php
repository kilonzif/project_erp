@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/pickers/daterange/daterange.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/chat-application.css')}}">
@endpush


@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">View Report</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Submitted Reports</a>
                        </li>
                        <li class="breadcrumb-item active">View report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="mb-1">

            <div class="row">
                <div class="col-md-8">
                    <button type="button" class="btn btn-outline-dark  dropdown-toggle square" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false"><i class="ft-more-vertical"></i> More Actions</button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" data-toggle="tooltip" data-placement="top" title="Upload Indicator Details"
                           href="{{route('report_submission.upload_indicator', [\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}">
                            <i class="ft-upload mr-sm-1"></i> {{__('Upload Indicator Details')}}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{route('report_submission.edit', [\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                           @if($report->editable == 0) style="display: none;" @endif id="editable" data-toggle="tooltip"
                           data-placement="top" title="Edit Report">
                            <i class="ft-edit-2 mr-sm-1"></i>{{__('Edit Report')}}
                        </a>
                        @ability('webmaster|super-admin','generate-report')
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{route('report_submission.indicators_status',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                           data-toggle="tooltip" data-placement="right" title="Update Indicator Status">
                            <i class="ft-check-circle mr-sm-1"></i> Indicators Status
                        </a>
                        <a class="dropdown-item" href="{{route('report_generation.report.milestones',[$report->id])}}" data-toggle="tooltip"
                           data-placement="right" title="Update DLR 2.8 status">
                            <i class="ft-grid mr-sm-1"></i> Milestones Status
                        </a>
                        <a class="dropdown-item" href="{{route('report_generation.verificationletter.report',[$report->id])}}" data-toggle="tooltip"
                           data-placement="right" title="Add or Edit the verification log">
                            <i class="ft-check-square mr-sm-1"></i> Verification Log
                        </a>
                        @endability
                    </div>
                </div>
                @ability('webmaster|super-admin','set-report-mode')
                <div class="col-md-4 text-right">
                    <span id="reviewMode" class="btn {{($report->editable == 0) ? 'btn-secondary':'btn-primary'}} square"  data-toggle="tooltip" data-placement="top" title="Reports can't be edited in Review Mode."
                          onclick="setReviewMode()">
                        <i class="fa fa-spinner spinner mr-sm-1" style="display: none;"></i> <span id="note">{{($report->editable == 0) ? 'In Review Mode':'In Edit Mode'}}</span>
                    </span>
                </div>
                @endability
            </div>
        </div>
        <div class="row">
            <div class="col-12">

                @if($project->indicators->count() > 0)
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
                                        <div class="col-md-8">
                                            <h6>
                                                ACE Name
                                            </h6>
                                            <p><strong>{{$report->ace->name." (".$report->ace->acronym.")"}}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>
                                                ACE Officer
                                            </h6>
                                            <p><strong>{{$report->user->name}}</strong></p>
                                        </div>
                                    @endif

                                    @php
                                        $start_period = date('m-Y',strtotime($reporting_period->period_start));
                                        $end_period = date('m-Y',strtotime($reporting_period->period_end));
                                        $monthNum1=date('m',strtotime($reporting_period->period_start));
                                        $monthName1 = date("M", mktime(0, 0, 0, $monthNum1, 10));
                                        $year1 = date('Y',strtotime($reporting_period->period_start));
                                        $start = $monthName1 .', '.$year1;
                                        $monthNum2=date('m',strtotime($reporting_period->period_end));
                                        $monthName2 = date("M", mktime(0, 0, 0, $monthNum2, 10));
                                        $year2 = date('Y',strtotime($reporting_period->period_end));
                                        $end =$monthName2 .', '.$year2;
                                        $full_period = $start ."    -  ". $end;
                                    @endphp



                                    <div class="col-md-4">
                                        <h6>Reporting Period (Start) </h6>
                                        <p><strong>{{$start}}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Reporting Period (End)</h6>
                                        <p><strong>{{$end}}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Date Submitted</h6>
                                        <p><strong>{{date('M d, Y', strtotime($report->submission_date))}}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--@php--}}
                        {{--$indicators = $project->indicators->where('parent_id','=',0)->where('status','=',1);--}}
                    {{--@endphp--}}
                    <div id="indicators-form">
                        @foreach($indicators as $indicator)
                            <div class="card mb-1">
                                <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                                    {{--<h6 class="card-title"></h6>--}}
                                    <strong>{{"Indicator ".$indicator->identifier}}:</strong> {{$indicator->title}}
                                    {{--<br>--}}
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                        </ul>
                                    </div>
                                </h6>
                                <div class="card-content collapse">
                                    <div class="card-body table-responsive">
                                        <h5>
                                            <small>
                                                <span class="text-secondary text-bold-500">Unit of Measure:</span>
                                                {{$indicator->unit_measure}}
                                            </small>
                                        </h5>
                                        @role('webmaster|super-admin|admin')
                                        @if($indicator->IsUploadable($indicator->id))
                                        <a class="btn btn-dark square text-left mr-3 mb-sm-1"
                                           href="{{route('report_submission.upload_indicator', [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$indicator->id])}}">
                                            <i class="ft-upload mr-sm-1"></i>{{__('Upload details')}}
                                        </a>
                                        @endif
                                        @endrole
                                        <table class="table table-bordered table-striped">
                                            @if($indicator->indicators->count() > 0)
                                                @php
                                                    $sub_indicators = $indicator->indicators->where('status','=',1);
                                                    $filter_index = ['national_and_men','national_and_women','regional_and_men','regional_and_women'];
                                                    $indicator_5_2_filter = ['national','regional'];
                                                    $indicator_identifier = (string)$indicator->identifier;
                                                    $counter = 0;
                                                @endphp
                                                @foreach($sub_indicators as $sub_indicator)
                                                    @if(isset($values[$sub_indicator->id]))
                                                        <tr>
                                                            <td>{{$sub_indicator->title}}
                                                            </td>
                                                            <td style="width: 200px">
                                                                <input type="text" disabled="disabled" readonly class="form-control" value="{{$values[$sub_indicator->id]}}">
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td>{{$sub_indicator->title}}
                                                            </td>
                                                            <td style="width: 200px">
                                                                <input type="text" disabled="disabled" readonly class="form-control" value="N/A">
                                                            </td>
                                                        </tr>
                                                    @endif

                                                @endforeach
                                            @else
                                                @if(!isset($values[$indicator->id]))
                                                    <tr>
                                                        <td colspan="2">
                                                            <input type="text" disabled="disabled" readonly value="N/A" class="form-control">
                                                        </td>
                                                    </tr>
                                                @else
                                                    @php
                                                        if ($indicator->identifier == 11){
                                                            if ($values[$indicator->id] == 0){
                                                                $val = "No";
                                                            }else{
                                                                $val = "Yes";
                                                            }
                                                        }
                                                        else{
                                                            $val = $values[$indicator->id];
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td colspan="2">
                                                            <input type="text" disabled="disabled" readonly value="{{$val}}" class="form-control">
                                                        </td>
                                                    </tr>
                                                @endif

                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else
                    <h2 class="center">No Indicators available</h2>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-1">
                    <h6 class="card-header p-1 card-head-inverse bg-grey-blue" style="border-radius:0">
                        <strong>Challenges faced(if any)</strong>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                            </ul>
                        </div>
                    </h6>
                    <div class="card-content collapse">
                        <div class="card-body">
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Comment" name="report_comment">@isset($comment){{$comment->comments}}@endisset</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('vendor-script')

     
<script type="text/javascript" src="{{ asset("js/scripts/customizer.js") }}"></script>
<script src="{{ asset("js/scripts/pages/chat-application.js")}}" type="text/javascript"></script>
<script type="text/javascript" >
    function setReviewMode() {
        // console.log(id);
        $.ajaxSetup(    {
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            }
        });
        var path = "{{route('report_submission.report_review_mode',[$report->id])}}";
        $.ajax({
            url: path,
            type: 'GET',
            // data: {id:id},
            beforeSend: function(){
                $('#reviewMode > i').css('display','inline-block')
            },
            success: function(data){
                if(data.status == 0){
                    $('#reviewMode').removeClass('btn-primary')
                    $('#editable').css('display','none')
                }
                else{
                    $('#reviewMode').removeClass('btn-secondary')
                    $('#editable').css('display','inline-block')
                }
                $('#reviewMode').addClass(data.btnclass)
                $('#note').text(data.note)
            },
            complete:function(){
                $('#reviewMode > i').css('display','none')
                // $.getScript(check1);
                // $.getScript(check2);
            }
            ,
            error: function (data) {
                console.log(data)
            }
        });
    }
  $('.customizer-toggle').on('click',function(){
        $('.customizer').toggleClass('open');
    });
</script>






@endpush
@push('end-script')

@endpush