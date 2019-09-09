@extends('layouts.app')
@push('vendor-styles')
        <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
@endpush
@push('other-styles')
{{--    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Submitted Reports</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
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
                                        {{--<th>Project Title</th>--}}
                                        <th>ACE</th>
                                        <th width="100px">Reporting Year</th>
                                        <th width="100px">Submitted On</th>
                                        <th width="100px">Status</th>
                                        <th width="50px">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ace_reports as $report)
                                        <tr>
                                            {{--<td>{{$report->project->title}}</td>--}}
                                            <td>
                                                @if($report->ace)
                                                    {{$report->ace->name}} <strong>{{'('.$report->ace->acronym.')'}}</strong>
                                              {{--@else--}}
                                                    {{--{{$report->user->institution->acronym}}--}}
                                                @endif
                                            </td>
                                            <td>{{date('Y', strtotime($report->start_date))}}</td>
                                            <td>{{date('M d, Y', strtotime($report->submission_date))}}</td>
                                            <td>{!! $me->reportStatusTag($report->status) !!}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{route('report_submission.view',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                                                       class="btn btn-s btn-dark" data-toggle="tooltip" data-placement="top" title="View Report"><i class="ft-eye"></i></a>
                                                    @if($report->editable == 1 or \Auth::user()->hasRole('webmaster|super-admin'))
                                                        <a href="{{route('report_submission.edit',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                                                           class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Report"><i class="ft-edit-3"></i></a>
                                                    @else
                                                        <a href="#" class="btn btn-s btn-secondary disabled" data-toggle="tooltip" data-placement="top" title="In Review Mode">
                                                            <i class="ft-edit-3"></i></a>
                                                    @endif
                                                        <a href="{{route('report_submission.indicators_status',[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}"
                                                       class="btn btn-s btn-success" data-toggle="tooltip" data-placement="top" title="Report Status"><i class="fa fa-hourglass-start"></i></a>
                                                    @if(\Auth::user()->hasRole('webmaster|super-admin'))
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
    {{--<script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>--}}
@endpush
@push('end-script')
    <script>
        $('.reports-table').DataTable({
            "order": [[ 1, "asc" ]]
        });
    </script>

    {{--<script src="{{asset('js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>--}}
@endpush