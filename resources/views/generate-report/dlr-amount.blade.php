@extends('layouts.app')
@push('vendor-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item active">Report Generation
                        </li>
                        <li class="breadcrumb-item active">DLR Amount Report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <h5>Generate Report</h5>

        <form action="{{route('report_generation.dlr_amount.result')}}" method="GET">
            @csrf
            {{--<div class="row">--}}
                {{--<div class="col-md-5">--}}
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="submission_period">Select Reporting Year<span class="required">*</span></label>
                                    <br>
                                    @php
                                        $reporting_year_start = config('app.reporting_year_start');
                                        $reporting_year_length = config('app.reporting_year_length');
                                    @endphp
                                    @for($a=$reporting_year_start;$a < $reporting_year_length+$reporting_year_start; $a++)
                                        <div class="d-inline-block custom-control custom-checkbox mr-1">
                                            <input type="checkbox" class="custom-control-input" value="{{$a}}" name="reporting_year[]"
                                                   id="reporting_year{{$a}}">
                                            <label class="custom-control-label" for="reporting_year{{$a}}">{{$a}}</label>
                                        </div>
                                    @endfor
                                    @if ($errors->has('reporting_year'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('reporting_year') }}</small>
                                        </p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="ace"> <i class="ft-filter"></i> Select Ace</label>
                                    <select name="ace" id="ace" required class="form-control select2">
                                        <option value=""></option>
                                        @foreach($aces as $ace)
                                            <option value="{{$ace->id}}"><strong>({{$ace->acronym}})</strong>  {{$ace->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-secondary square">
                                            <i class="ft-pie-chart mr-sm-1"></i>{{__('Generate')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{--</div>--}}
            {{--</div>--}}
        </form>

    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>
    <script>
        $('.select2').select2({
            placeholder: "Select ACE",
            allowClear: true
        });
    </script>
@endpush