@extends('layouts.app')
@push('vendor-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">

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
{{--                            <form action="{{route('report_submission.save_excel_upload')}}" enctype="multipart/form-data" method="post">--}}
                                <form enctype="multipart/form-data" id="upload-form">
                                @csrf
                                <input type="hidden" name="report_id" value="{{$report_id}}" id="report_id">
                                <div class="row">
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label>Language</label>
                                            <select class="form-control"  disabled name="language" id="language">
                                                <option @if($report->language == "english") selected @endif value="english">English</option>
                                                <option @if($report->language == "french") selected @endif value="french">French</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="form-group">
                                            <label for="basicInputFile">Select Indicator</label>
                                            <select name="indicator" disabled required class="select form-control" id="indicator" onchange=" loadFields()">
                                                @foreach($indicators as $indicator)
                                                    @if($indicator->IsUploadable($indicator->id))
                                                    <option @if($report->indicator_id == $indicator->id) selected @endif value="{{$indicator->id}}">
                                                        {{$indicator->title}}

                                                    </option>

                                                    @endif
                                                @endforeach
                                            </select>
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
                                    <div class="col-md-3 offset-4">
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

        <div id="dlr-default">
            <div class="row" id="dlrs-div">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <table class="table table-striped table-bordered indicators-details" id="indicators_table">
                                    <thead>
                                    <tr>
                                        <th>Indicator</th>
                                        <th style="width: 200px;">Created Date</th>
                                        <th style="width: 50px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{$report->indicator->title}}
                                            </td>
                                            <td>{{date('d/m/Y', strtotime($report->created_at))}}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{route('report_submission.view_indicator_details',
                                                    [$report->id])}}" disabled class="btn btn-s btn-secondary"
                                                       data-toggle="tooltip" data-placement="top"
                                                       title="View Indicator Details"><i class="ft-eye"></i></a>
                                                </div>
                                            </td>
                                        </tr>
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
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>

@endpush
@push('end-script')
    <script>
        $('document').ready(function () {
            loadFields();
        });

        $('#upload-form').submit(function(event) {
            console.log("start");
            event.preventDefault();
            var formData = new FormData($(this)[0]);
            let report_id = $('#report_id').val();
            let indicator = $('#indicator').val();
            let language = $('#language').val();
            let upload_file = $('#upload_file').val();


            formData.append('report_id', $("#report_id").val());
            formData.append('indicator', $("#indicator").val());
            formData.append('language',language);
            formData.append('upload_file', $("#upload_file").val());
            $.ajax({
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                url: "{{route('report_submission.save_excel_upload')}}",
                enctype: 'multipart/form-data',
                beforeSend: function(){
                        $('#upload-form').block({
                        message: '<div class="ft-loader icon-spin font-large-1"></div>',
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
                        },
                success: function(result)
                {

                    toastr['success']('Indicator Uploaded Successfully', 'success','{positionClass:toast-top-right, "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 8000}');
                    $('#upload-form').unblock();
                    $('#dlrs-div').remove();
                    $('#dlr-default').html(result.view);
                    return true;

                },
                error: function(data)
                {
                    let message = data.responseText;

                    toastr['error']('Indicator failed to upload', 'error','{positionClass:toast-top-right, "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 8000}');
                    $('#upload-form').unblock();
                    console.log(data);
                    return false;
                }
            });

        });

        function loadFields() {
            let selected = $('#indicator').val();
            let language = $('#language').val();
            let path = "{{route('getIndicatorFields')}}";
            let css_path = "{{asset('vendors/js/forms/select/select2.full.min.js')}}";
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                url: path,
                type: 'GET',
                data: {id:selected,language:language},
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
                    $('#action-card').html(data.theView);
                },
                complete:function(){
                    $('#action-loader').unblock();
                    $.getScript(css_path)
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