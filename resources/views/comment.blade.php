@extends('layouts.app')
@push('vendor-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}" />

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
        <div class="text-right">
            <a class="btn btn-secondary square" href="{{route('report_submission.edit', [\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}">
                {{__('Edit Report')}}
            </a>
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
                                </div>
                            </div>
                        </div>
                        @php
                            $indicators = $project->indicators->where('parent_id','=',0)->where('status','=',1);
                        @endphp
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
                                            <table class="table table-bordered table-striped">
                                                @if($indicator->indicators->count() > 0)
                                                    @php
                                                        $sub_indicators = $indicator->indicators->where('status','=',1);
                                                    @endphp
                                                    @foreach($sub_indicators as $sub_indicator)
                                                        @if(isset($values[$sub_indicator->id]))
                                                            <tr>
                                                                <td>{{$sub_indicator->title}}
                                                                    @if($sub_indicator->unit_measure)
                                                                        <br><small><strong>Unit of Measure: </strong>{{$sub_indicator->unit_measure->title}}</small>
                                                                    @endif
                                                                </td>
                                                                <td style="width: 200px">
                                                                    <input type="text" disabled="disabled" readonly class="form-control" value="{{$values[$sub_indicator->id]}}">
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td>{{$sub_indicator->title}}
                                                                    @if($sub_indicator->unit_measure)
                                                                        <br><small><strong>Unit of Measure: </strong>{{$sub_indicator->unit_measure->title}}</small>
                                                                    @endif
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
                                                        <tr>
                                                            <td colspan="2">
                                                                <input type="text" disabled="disabled" readonly value="{{$values[$indicator->id]}}" class="form-control">
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
    </div>

    @push('side-drawer')
<div class="customizer border-left-blue-grey border-left-lighten-4 d-none d-xl-block">
   <a class="customizer-close" href="#"><i class="ft-x font-medium-3"></i></a>
   <a class="customizer-toggle bg-danger" href="#">
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

      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

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