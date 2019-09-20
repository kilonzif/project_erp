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
            <h3 class="content-header-title mb-0">Report Indicator Status</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">{{__('Submitted Reports')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{__('Indicator Status')}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="mb-1">
            <div class="row">
                <div class="col-md-4">
                    <span id="reviewMode" class="btn {{($report->editable == 0) ? 'btn-secondary':'btn-primary'}} square"  data-toggle="tooltip" data-placement="top" title="Reports can't be edited in Review Mode."
                       onclick="setReviewMode()">
                        <i class="fa fa-spinner spinner mr-sm-1" style="display: none;"></i> <span id="note">{{($report->editable == 0) ? 'In Review Mode':'In Edit Mode'}}</span>
                    </span>
                </div>
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
                                        <div class="col-md-12">
                                            <h6>
                                                ACE Name
                                            </h6>
                                            <p><strong>{{$report->ace->name." (".$report->ace->acronym.")"}}</strong></p>
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
                                <hr>
                                <form action="{{route('report_submission.report_status_save',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                                      method="post">@csrf
                                    <div class="row">
                                        <div class="col-md-3">
                                            {{--<h5>Current Status</h5>--}}
                                            {!! $all_status->reportStatusTag($report->status) !!}
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{--<label for="status_label">Select Status</label>--}}
                                                <select name="status_label" required id="status_label" style="width: 100%;" class="form-control">
                                                    <option @if($report->status == 1) selected @endif value="1">Submitted</option>
                                                    <option @if($report->status == 101) selected @endif value="101">Verified</option>
                                                    <option @if($report->status == 100) selected @endif value="100">Under Review</option>
                                                    <option @if($report->status == 99) selected @endif value="99">Uncompleted</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="date" required class="form-control" name="sub_date" id="sub_date">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-secondary square">Change Status</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{--@php--}}
                        {{--$indicators = $project->indicators->where('parent_id','=',0)->where('status','=',1);--}}
                    {{--@endphp--}}
                    <div id="indicators-form">
                        @foreach($indicators as $indicator)
                            <div class="card mb-1">
                                <h6 class="card-header p-1" style="border-radius:0">
                                    {{--<h6 class="card-title"></h6>--}}
                                    <strong>{{"Indicator ".$indicator->identifier}}</strong>
                                    <br>
                                    <span style="margin-bottom: 7px">{{$indicator->title}}</span>
                                    <br>
                                    <br>
                                    <span class="btn btn-sm btn-amber">
                                        @php
                                            $getStatus = $current_status->where('indicator_id','=', $indicator->id)->first();
                                        @endphp
                                        {{$all_status->getStatusLabel($getStatus->status)}}
                                    </span>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                        </ul>
                                    </div>
                                </h6>
                                <div class="card-content collapse">
                                    <div class="card-body">
                                        <form action="{{route('report_submission.indicators_status_save',[\Illuminate\Support\Facades\Crypt::encrypt($report->id),$indicator->id])}}"
                                              method="post">@csrf
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{--<label for="status_label">Select Status</label>--}}
                                                        <select name="status_label" required id="status_label" style="width: 100%;" class="select2 form-control">
                                                            <option value="">Select Status</option>
                                                            @foreach($all_status->getStatusLabel() as $key=>$status)
                                                                <option value="{{$key}}">{{$status}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {{--<label for="status_rep">Select Responsibility</label>--}}
                                                        <select name="status_rep" required id="status_rep" style="width: 100%;" class="selectRep form-control">
                                                            <option value="">Select Responsibility</option>
                                                            @foreach($all_status->getStatusResponsibility() as $key=>$status)
                                                                <option value="{{$key}}">{{$status}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
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
                                                $this_history = $status_history->where('indicator_id','=',$indicator->id);
                                            @endphp
                                            @foreach($this_history as $history)
                                                @isset($history)
                                                <tr>
                                                    <td>
                                                        @if(isset($history->status))
                                                            {{$all_status->getStatusLabel($history->status)}}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                    @if(isset($history->responsibility))
                                                        {{$all_status->getStatusResponsibility($history->responsibility)}}
                                                    @else
                                                        N/A
                                                    @endif
                                                    </td>
                                                    <td>
                                                    @if(isset($history->status_date))
                                                        {{date('D F, Y',strtotime($history->status_date))}}
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
                        @endforeach
                    </div>

                @else
                    <h2 class="center">No Indicators available</h2>
                @endif
            </div>
        </div>
    </div>

    @push('side-drawer')
        <div class="customizer border-left-blue-grey border-left-lighten-4 d-none d-xl-block">
            <a class="customizer-close" href="#"><i class="ft-x font-medium-3"></i></a>
            <a class="customizer-toggle bg-danger" href="#" style=" top:12%">
                <i class="font-medium-3 fa fa-comments white"></i>
            </a>
            <div class="customizer-content p-2 ps-container chat-application" >
                @comments(['model' =>$report])
                @endcomments
            </div>
        </div>
    @endpush

@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset("js/scripts/customizer.js") }}"></script>
    <script src="{{ asset("js/scripts/pages/chat-application.js")}}" type="text/javascript"></script>
    <script type="text/javascript" >
        $('.select2').select2({
            placeholder: "Select Status",
            allowClear: true
        });
        $('.selectRep').select2({
            placeholder: "Select Responsibility",
            allowClear: true
        });

        $('.customizer-toggle').on('click',function(){
            $('.customizer').toggleClass('open');
        });
    </script>
@endpush
@push('end-script')

@endpush