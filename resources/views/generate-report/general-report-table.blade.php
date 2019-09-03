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
            <h3 class="content-header-title mb-0">General Report</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">General Reports</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-secondary square mb-1">Go Back</a>
        <a href="{{route('report_generation.general_report_table',array_merge(['export'=>true], request()->only(['start', 'end','filter','aces'])))}}"
         class="btn btn-primary square mb-1">
            <i class="fa fa-file-excel-o"></i> Excel</a>
        <div class="row">
            <div class="col-12">
                <div class="card mb-1">
                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                        Generated Report
                    </h6>
                    <div class="card-content collapse show">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <tr>

                                    <th style="width: 150px;">Reporting Period (Start Date)</th>
                                    <td>{{date('F d, Y',strtotime($start))}}</td>
                                    <th style="width: 150px;">Reporting Period (End Date)</th>
                                    <td>{{date('F d, Y',strtotime($end))}}</td>

                                </tr>
                            </table>
                            @if(sizeof($report_values) > 1)
                            @include('generate-report.gtable')
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
        $('#generalReport').dataTable({
            // "columnDefs": [{
            //     "visible": false,
            //     "targets": 1
            // }],
            // dom: 'Bfrtip',
            // buttons: [
            //     'excel', 'print'
            // ]
        });
    </script>
@endpush


