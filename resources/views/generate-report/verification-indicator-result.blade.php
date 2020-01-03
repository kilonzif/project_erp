@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">--}}

    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
@endpush
@push('other-styles')
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
@endpush
@section('content')
    {{--@php dd(old('indicator.3')) @endphp--}}
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Indicator Verification Status Report</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-secondary square mb-1">Go Back</a>
        <a href="{{route('report_generation.indicator_status_report',array_merge(['export'=>true], request()->only([ 'start', 'end', 'filter','aces'])))}}" class="btn btn-primary square mb-1">

            <i class="fa fa-file-excel-o"></i> Excel</a>
        <div class="row">
            <div class="col-12">
                <div class="card mb-1">
                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                        Indicator Verification Status Report
                    </h6>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th style="width: 150px;">Reporting Period (Start Date)</th>
                                    <td>{{date('F d, Y',strtotime($start))}}</td>
                                    <th style="width: 150px;">Reporting Period (End Date)</th>
                                    <td>{{date('F d, Y',strtotime($end))}}</td>
                                </tr>
                            </table>
                            @if($reports->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="verifications">
                                        <thead>
                                        <tr>
                                            <td><div style="min-width: 100px;"></div></td>
                                            <th width="100px">
                                                <div style="min-width: 100px;">
                                                    <span class="warning bold">Responsibilty</span>
                                                </div>
                                            </th>
                                            @foreach($steps as $key=>$step)
                                                <th width="100px">
                                                    <div style="min-width: 100px;">
                                                    {{$process->getStatusLabelRep($step)}}
                                                    </div>
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <th><span class="warning bold">Actions</span></th>
                                            @foreach($steps as $key=>$step)
                                                <th>{{$process->getStatusLabel($step)}}</th>
                                            @endforeach
                                        </tr>
                                        <tr>

                                            <th><span class="warning bold">Country</span></th>
                                            <th><span class="warning bold">ACE</span></th>
                                            <td colspan="{{sizeof($steps)}}"></td>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{--@php--}}
                                            {{--$old_report = 0;--}}
                                        {{--@endphp--}}
                                        @foreach($reports as $report)

                                            @php
                                                $getIndicators = $report->report_indicators_status
                                                ->where('indicator_id','=',$type_indicator);
                                            @endphp
                                            <tr>
                                                <td>{{$report->ace->university->country->country}}</td>
                                                <td>{{$report->ace->acronym}}</td>
                                                @foreach($steps as $key=>$step)
                                                    @php
                                                        $getIndicator = $getIndicators->where('status','=',$step)->pluck('status_date')->first();
                                                    @endphp
                                                    @if(!is_null($getIndicator))
                                                    <td>
                                                        {{date('d M, Y', strtotime($getIndicator))}}
                                                    </td>
                                                    @else
                                                        <td style="text-align: center"> - </td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h2 class="text-center danger mt-3 mb-3">No Report can be generated within the specified range</h2>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vendor-script')

    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    {{--    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>--}}

@endpush

@push('end-script')
    <script>
        $('#verifications').dataTable({
            "columnDefs": [{
                "visible": false,
                // "targets": -1
            }]
            // dom: 'Bfrtip',
            // buttons: [
            //     'excel', 'print'
            // ]
        });
    </script>
@endpush