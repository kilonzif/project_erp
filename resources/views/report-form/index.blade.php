@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
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
                        <li class="breadcrumb-item active">All Reports
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                @ability('webmaster|super-admin|admin', 'submit-report')
                                <li>
                                    <a href="{{route('report_submission.add')}}" class="btn btn-secondary" aria-label="Previous">
                                        New Report Submission
                                    </a>
                                </li>
                                @endability
                            </ul>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @foreach($periods as $period)
                                @php
                                    $reporting_period = \App\Http\Controllers\ReportFormController::getReportingName($period->id);
                                    $all_reports = \App\Http\Controllers\ReportFormController::aceReports($period->id);
                                @endphp
                                <div class="card mb-1">
                                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                                        {{$reporting_period}}
                                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            </ul>
                                        </div>
                                    </h6>
                                    <div class="card-content collapse show">
                                        <div class="card-body table-responsive">
                                            <table class="table table-bordered table-striped mb-0 reports-table">
                                                <thead>
                                                <tr>
                                                    <th>DLR</th>
                                                    <th>ACE</th>
                                                    <th width="100px">Uploaded On</th>
                                                    <th width="100px">Status</th>
                                                    <th width="50px">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($all_reports as $report)
                                                    @php

                                                        $dlr = \App\Indicator::where('id','=',$report->indicator_id)->first();

                                                    @endphp

                                                    <tr>
                                                        <td>
                                                            {{$dlr->title}}
                                                        </td>
                                                        <td>
                                                            @if($report->ace)
                                                                {{$report->ace->name}} <strong>{{'('.$report->ace->acronym.')'}}</strong>
                                                            @endif
                                                        </td>
                                                        <td>{{date('d M, Y', strtotime($report->submission_date))}}</td>
                                                        <td>{!! $me->reportStatusTag($report->status) !!}</td>
                                                        <td>
                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                <a href="{{route('report_submission.view',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                                                                   class="btn btn-s btn-dark" data-toggle="tooltip" data-placement="top" title="View Report"><i class="ft-eye"></i></a>
                                                                @if($report->status != 1)
                                                                    <a href="{{route('report_submission.edit',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                                                                       class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Report"><i class="ft-edit-3"></i></a>
                                                                @else
                                                                    <a href="#" class="btn btn-s btn-secondary disabled" data-toggle="tooltip" data-placement="top" title="In Review Mode">
                                                                        <i class="ft-edit-3"></i></a>
                                                                @endif
                                                                @if(\Auth::user()->hasRole('webmaster|super-admin'))
                                                                    <a href="{{route('report_submission.reports.delete',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                                                                       class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this report?');"
                                                                       title="Delete Report"><i class="ft-trash-2"></i></a>
                                                                @endif
                                                                @if($report->status != 1 && \Auth::user()->hasRole('ace-officer'))
                                                                    <a href="{{route('report_submission.reports.delete',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                                                                       class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this report?');"
                                                                       title="Delete Report"><i class="ft-trash-2"></i></a>
                                                                @elseif($report->status == 1 && \Auth::user()->hasRole('ace-officer'))
                                                                    <a href="{{route('report_submission.reports.delete',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                                                                       class="btn btn-s btn-danger disabled" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this report?');"
                                                                       title="Delete Report"><i class="ft-trash-2"></i></a>

                                                                @endif
                                                            </div>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
        $('.reports-table').DataTable({
            "order": [[ 1, "asc" ]]
        });
    </script>
@endpush