@extends('layouts.app')
@push('vendor-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
@endpush
@push('other-styles')
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
@endpush
@section('content')
    {{--@php dd(old('indicator.3')) @endphp--}}
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Upload Indicators Detail</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Submitted Reports</a>
                        </li>
                        <li class="breadcrumb-item active">Upload Indicators
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="mb-1 row ">
            <div class="col-lg-12 text-right">
                <a class="btn btn-dark square" href="{{route('report_submission.edit',[\Illuminate\Support\Facades\Crypt::encrypt($d_report_id)])}}">
                    <i class="ft-arrow-right mr-md-2"></i>Preview and Submit Report
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header p-1 card-head-inverse bg-teal">
                        <h6>{{$ace->name}} - ({{$ace->acronym}})</h6>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            {{--<h5>Select Indicator</h5>--}}
                            <form action="{{route('report_submission.save_excel_upload')}}" enctype="multipart/form-data" method="post">
                                @csrf
                                <input type="hidden" name="report_id" value="{{$report_id}}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <fieldset class="form-group">
                                            <label for="basicInputFile">Select Indicator</label>
                                            <select name="indicator" required class="select form-control" id="indicator" onchange=" loadFields()">
                                                {{--<option value="">Choose...</option>--}}
                                                @foreach($indicators as $indicator)
                                                    @if($indicator->IsUploadable($indicator->id))
                                                    <option @if($indicatorID == $indicator->id) selected @endif value="{{$indicator->id}}">Indicator {{$indicator->identifier}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            {{--<p class="text-right mb-0">--}}
                                                {{--<small class="warning text-muted" id="indicator-error"></small>--}}
                                            {{--</p>--}}
                                            @if ($errors->has('indicator'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted" id="indicator-error">{{ $errors->first('indicator') }}</small>
                                                </p>
                                            @endif
                                        </fieldset>
                                    </div>
                                    <div class="col-md-5">
                                        <fieldset class="form-group">
                                            <label for="upload_file">Browse File <span class="warning text-muted">{{__('Please upload only Excel (.xlsx) files')}}</span></label>
                                            <input type="file" style="padding: 8px;" required class="form-control" name="upload_file" id="upload_file">
                                            @if ($errors->has('upload_file'))
                                                <p class="text-right mb-0">
                                                    <small class="danger text-muted" id="file-error">{{ $errors->first('upload_file') }}</small>
                                                </p>
                                            @endif
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <button style="margin-top: 2rem;" type="submit" class="btn btn-secondary"
                                                id="uploadData">
                                            <i class="ft-upload mr-1"></i> Upload Indicator
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-12">
                <div class="card" id="action-loader">
                    <div class="card-header" style="padding: 10px 20px;">
                        <h5>Indicator Fields <span class="ml-1 warning">(The fields on the excel sheet must match the fields listed for the selected indicator and in similar order.)</span></h5>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body" id="action-card">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="action-card">

        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <table class="table table-striped table-bordered indicators-details">
                                <thead>
                                <tr>
                                    <th>Indicator</th>
                                    <th style="width: 200px;">Created Date</th>
                                    <th style="width: 200px;">Modified Date</th>
                                    <th style="width: 50px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($indicator_details as $indicator_detail)
                                    <tr>
                                        {{--<td>{{$indicator->number}}</td>--}}
                                        <td>
                                            @php
                                                $indicator_iden = $indicators->where('id','=',$indicator_detail->indicator_id)->pluck('identifier')->first();
                                            @endphp
                                            INDICATOR {{$indicator_iden}}
                                        </td>
                                        <td>{{$indicator_detail->created_at}}</td>
                                        <td>{{$indicator_detail->created_at}}</td>
                                        <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{route('report_submission.view_indicator_details',[$indicator_detail->id])}}" disabled class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="View Indicator Details"><i class="ft-eye"></i></a></a>
                                        {{--<a href="#" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="Indicator Status"><i class="ft-times"></i></a></a>--}}
                                        {{--<a class="dropdow-item btn {{($indicator->status == 0)?'btn-success' : 'btn-danger'}} btn-s" href="#"--}}
                                        {{--data-toggle="tooltip" data-placement="top" title="{{($indicator->status == 0)?'Activate Indicator' : 'Deactivate Indicator'}}"--}}
                                        {{--onclick="event.preventDefault(); document.getElementById('delete-indicator-{{$count}}').submit();">--}}
                                        {{--@if($indicator->status == 0)--}}
                                        {{--<i class="ft-check"></i>--}}
                                        {{--@else--}}
                                        {{--<i class="ft-x"></i>--}}
                                        {{--@endif--}}
                                        {{--</a>--}}
                                        </div>
                                        {{--<form id="delete-indicator-{{$count}}" action="{{ route('indicator.activate',[\Illuminate\Support\Facades\Crypt::encrypt($indicator->id)]) }}" method="POST" style="display: none;">--}}
                                        {{--@csrf {{method_field('DELETE')}}--}}
                                        {{--<input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($indicator->id)}}">--}}
                                        {{--</form>--}}
                                        {{--</td>--}}
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
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script>
        $('document').ready(function () {
            loadFields();
        });

        function loadFields() {
            var selected = $('#indicator').val();

            var path = "{{route('getIndicatorFields')}}"
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                url: path,
                type: 'GET',
                data: {id:selected},
                beforeSend: function(){
                    $('#action-loader').block({
                        message: '<div class="ft-loader icon-spin font-large-1"></div>',
                        // timeout: 2000, //unblock after 2 seconds
                        overlayCSS: {
                            backgroundColor: '#ccc',
                            opacity: 0.8,
                            cursor: 'wait'
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: 'transparent'
                        }
                    });
                    $('#action-card').empty();
                },
                success: function(data){
                    console.log(data)
                    $('#action-card').html(data.theView);
                },
                complete:function(){
                    $('#action-loader').unblock();
                    $.getScript("http://127.0.0.1:8000/vendors/js/forms/select/select2.full.min.js")
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }

        $('.select2').select2({
            placeholder: "Select Indicator",
            allowClear: true
        });
    </script>
@endpush