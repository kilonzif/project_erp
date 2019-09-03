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
            <h3 class="content-header-title mb-0">Indicators Templates</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Download Indicators Templates
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="mb-1">
            <a class="btn btn-dark square text-left mr-3" href="{{\Illuminate\Support\Facades\URL::previous()}}">
                <i class="ft-arrow-left mr-sm-1"></i>{{__('Back to Report')}}
            </a>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header p-1 card-head-inverse bg-teal">
                        Select and Download Template
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            {{--<h5>Select Indicator</h5>--}}
                            <form action="{{route('report_submission.save_excel_upload')}}" enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <fieldset class="form-group">
                                            <label for="basicInputFile">Select Indicator</label>
                                            <select name="indicator" required class="select form-control" id="indicator" onchange=" loadFields()">
                                                @foreach($indicators as $indicator)
                                                    @if($indicator->IsUploadable($indicator->id))
                                                    <option value="{{$indicator->id}}">Indicator {{$indicator->identifier}}</option>
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