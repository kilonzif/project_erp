@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/calendars/fullcalendar.min.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/calendars/fullcalendar.css')}}">
    <style>
        #report_calendar.fc-scroller{
            overflow: hidden !important;
        }
    </style>
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item active">Report Status Calendar
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
                        <h4 class="card-title">Calendar</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <p class="card-text">This shows all the reports and their current status.</p>
                            <div id='report_calendar' style="overflow:hidden"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/extensions/moment.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/extensions/fullcalendar.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/extensions/fullcalendar.js')}}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {

            /************************************
             *                Default                *
             ************************************/
            $('#report_calendar').fullCalendar({
                defaultDate: "{{date('Y-m-d')}}",
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: [
                    @foreach($reports as $report)
                        {
                            title : '{{$report->ace->acronym}}',
                            url : '{{route("report_submission.view",[\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}',
                            start : '{{$report->updated_at}}',
                            color : '#16D39A'
                        },
                    @endforeach
                ]
            });

        });
    </script>
@endpush