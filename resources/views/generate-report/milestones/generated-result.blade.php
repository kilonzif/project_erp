@extends('layouts.app')
@push('vendor-styles')
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
@endpush
@push('other-styles')
    {{--<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
@endpush
@section('content')
    {{--@php dd(old('indicator.3')) @endphp--}}
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Report Generation</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">DLR 2.8 (Infrastructure) Report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-secondary square mb-1">Go Back</a>
        <a href="{{route('report_generation.generate.milestones.report',array_merge(['export'=>true], request()->only(['start', 'end','filter','aces'])))}}" class="btn btn-primary square mb-1">
            <i class="fa fa-file-excel-o"></i> Excel</a>
        <div class="row">
            <div class="col-12">
                <div class="card mb-1">
                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                        DLR 2.8 (Infrastructure) Report
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
                                    <table class="table table-bordered table-striped" id="theMilestones">

                                        <thead>
                                        <tr>
                                            <td><div style="min-width: 100px;"></div></td>
                                            <td><div style="min-width: 200px;"></div></td>
                                            <td>
                                                <div style="min-width: 100px;">
                                                    <span class="warning bold">Action</span>
                                                </div>
                                            </td>
                                            <td><div style="min-width: 100px;"></div></td>
                                            @foreach($steps as $key=>$step)
                                                <th>{{$process->getStatusLabel($step)}}</th>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><span class="warning bold">Responsibilty</span></td>
                                            <td><div style="min-width: 100px;"></div></td>
                                            @foreach($steps as $key=>$step)
                                                <th width="100px">
                                                    <div style="min-width: 100px;">
                                                        {{$process->getStatusLabelRep($step)}}
                                                    </div>
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th><span class="warning bold">Country</span></th>
                                            <th><span class="warning bold">ACE Host University</span></th>
                                            <th><span class="warning bold">ACE</span></th>
                                            <th><span class="warning bold">Milestones</span></th>
                                            <td colspan="{{sizeof($steps)}}"></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reports as $report)
                                            @php
                                                //$getIndicators = $report->report_indicators_status
                                                //->where('indicator_id','=',$type_indicator);
                                            //dd($getIndicators);
                                            @endphp
                                            <tr>
                                                <td rowspan="{{$report->milestone_no}}">
                                                    {{$report->country}}
                                                </td>
                                                <td rowspan="{{$report->milestone_no}}">
                                                    {{$report->university}}
                                                </td>
                                                <td rowspan="{{$report->milestone_no}}">
                                                 {{$report->acronym}}
                                                </td>
                                                @for($a=1; $a<=$report->milestone_no; $a++)
                                                    <td>
                                                        {{$a}}
                                                    </td>
                                                    @foreach($steps as $key=>$step)
                                                        @php
                                                            $milestone = $milestones->where('status','=',$step)
                                                            ->where('number','=',$a)
                                                            ->where('report_id','=',$report->id)
                                                            ->pluck('status_date')
                                                            ->first();
                                                        @endphp
                                                        @if(!is_null($milestone))
                                                            <td>
                                                                {{date('d M, Y', strtotime($milestone))}}
                                                            </td>
                                                        @else
                                                            <td style="text-align: center"> - </td>
                                                        @endif
                                                    @endforeach
                                            @if($a == $report->milestone_no)
                                            </tr>
                                            @else
                                            </tr>
                                            <tr>
                                                @endif
                                                @endfor
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
@endpush
@push('end-script')
    <script>
        $('#theMilestones').dataTable({
            "columnDefs": [{
                "visible": false,
                // "targets": -1
            }],
            dom: 'Bfrtip',
            buttons: [
                'excel',
                // 'print'
            ]
        });
    </script>
@endpush