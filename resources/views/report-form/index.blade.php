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
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0 reports-table">
                                    <thead>
                                    <tr>
                                        <th>ACE</th>
                                        <th width="100px">Reporting Period</th>
                                        <th width="100px">Uploaded On</th>
                                        <th width="100px">Status</th>
                                        <th width="50px">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ace_reports as $report)

                                        <tr>
                                            <td>
                                                @if($report->ace)
                                                    {{$report->ace->name}} <strong>{{'('.$report->ace->acronym.')'}}</strong>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $reporting_period = \App\Http\Controllers\ReportFormController::getReportingName($report->reporting_period_id);
                                                @endphp
                                                {{$reporting_period}}
                                            </td>
                                            <td>{{date('M d, Y', strtotime($report->submission_date))}}</td>
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
                                                        {{--<a href="{{route('report_submission.indicators_status',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"--}}
                                                           {{--class="btn btn-s btn-success" data-toggle="tooltip" data-placement="top" title="Report Status"><i class="fa fa-hourglass-start"></i></a>--}}
                                                        <a href="{{route('report_submission.reports.delete',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                                                           class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this report?');"
                                                           title="Delete Report"><i class="ft-trash-2"></i></a>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
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