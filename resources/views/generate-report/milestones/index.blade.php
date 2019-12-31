@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/pickers/daterange/daterange.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/chat-application.css')}}">
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
@endpush


@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Submitted Reports</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{\Illuminate\Support\Facades\URL::previous()}}">Report</a>
                        </li>
                        <li class="breadcrumb-item active">Update Status
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="mb-1">
            <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-secondary square"
               data-toggle="tooltip" data-placement="top" title="Go back.">
                <i class="ft-arrow-left mr-sm-1"></i> Go back
            </a>
        </div>
        <div class="row">
            <div class="col-12">
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
                                <div class="col-md-4">
                                    <h6>Submission Period (Start Date)</h6>
                                    <p><strong>{{date('M d, Y', strtotime($report->start_date))}}</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Submission Period (End Date)</h6>
                                    <p><strong>{{date('M d, Y', strtotime($report->end_date))}}</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Date Submitted</h6>
                                    <p><strong>{{date('M d, Y', strtotime($report->submission_date))}}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $milestone_no = $report->ace->milestone_no
            @endphp

            @for($a=1;$a <= $milestone_no; $a++)
                <div class="col-12">
                    <div class="card mb-1">
                        <h6 class="card-header p-1" style="border-radius:0">
                            <strong>{{"Milestone ".$a}}</strong>
                            {{--<span class="btn btn-sm btn-amber ml-sm-1">--}}
                            {{--@php--}}
                            {{--$getStatus = $current_status->where('indicator_id','=', $indicator->id)->first();--}}
                            {{--@endphp--}}
                            {{--{{$all_status->getStatusLabel($getStatus->status)}}--}}
                            {{--</span>--}}
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                </ul>
                            </div>
                        </h6>
                        <div class="card-content collapse">
                            <div class="card-body">
                                <form action="{{route('report_generation.report.milestones_save',[$report->id])}}"
                                      method="post">
                                    @csrf
                                    <input type="hidden" name="number" value="{{$a}}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select name="status_label" required id="status_label" style="width: 100%;" class="select2 form-control">
                                                    <option value="">Select Status</option>
                                                    @for($b=0; $b < sizeof($milestone_statuses); $b++)
                                                        @php $status = $milestone_statuses[$b]; @endphp
                                                        <option value="{{$status}}">{{$all_status[$status]}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        {{--<div class="col-md-3">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<select name="status_rep" required id="status_rep" style="width: 100%;" class="selectRep form-control">--}}
                                                    {{--<option value="">Select Responsibility</option>--}}
                                                    {{--@for($b=0; $b < sizeof($milestone_statuses); $b++)--}}
                                                        {{--@php $status = $milestone_statuses[$b]; @endphp--}}
                                                        {{--<option value="{{$status}}">{{$all_label[$status]}}</option>--}}
                                                    {{--@endfor--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="date" required class="form-control" name="sub_date" id="sub_date">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-secondary square">Change Status</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <h6>Status History</h6>
                                    <table class="table table-sm table-bordered table-striped">
                                        <tr>
                                            <th>Status</th>
                                            <th>Responsibility</th>
                                            <th>Date</th>
                                        </tr>
                                        @php
                                            $this_history = $status_history->where('number','=',$a);
                                        @endphp
                                        @foreach($this_history as $history)
                                            @isset($history)
                                                <tr>
                                                    <td>
                                                        @if(isset($history->status))
                                                            {{$all_status[$history->status]}}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($history->responsibility))
                                                            {{$all_label[$history->responsibility]}}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($history->status_date))
                                                            {{date('d F, Y',strtotime($history->status_date))}}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endisset
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    @push('side-drawer')
        <div class="customizer border-left-blue-grey border-left-lighten-4 d-none d-xl-block">
            <a class="customizer-close" href="#"><i class="ft-x font-medium-3"></i></a>
            <a class="customizer-toggle bg-danger" href="#" style=" top:12%">
                <i class="font-medium-3 fa fa-comments white"></i>
            </a>
            <div class="customizer-content p-2 ps-container chat-application" >

                @comments(['model' =>$report]) @endcomments
            </div>
        </div>
    @endpush

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