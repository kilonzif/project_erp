@extends('layouts.app')
@push('vendor-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/datetime/bootstrap-datetimepicker.css') }}">

    <style>
        table{
            border-collapse: collapse;
            width: 300px;
            overflow-x: scroll;
            display: block;
            font-size: 11pt;
        }
    </style>
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Reports</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.milestone', [\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}">Milestones</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{$indicator_info->title}} - {{lang('Milestone',$lang)}} {{$milestone->milestone_no}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="mb-1 row ">
            <div class="col-md-8">
                <h4>{{$ace->name}} ({{$ace->acronym}})</h4>
            </div>
        </div>
        <div class="card no-shadow">
                <h6 class="card-header p-1 card-head-inverse bg-primary">
                    {{$indicator_info->title}}
                </h6>
                <div class="card-content">
                    <div class="card-body">
                        <p><strong>Milestone {{$milestone->milestone_no}} </strong></p>
                        <p><strong>Description</strong></p>
                        <p>{{$milestone->description}}</p>
                        <p><strong>{{lang("Milestone Targets", $lang)}}</strong></p>
                        <ul>
                        @foreach($milestone->targets as $target)
                            <li>{{$target->target_indicator}}</li>
                        {{--<div class="row">--}}
                            {{--<div class="col-md-9">--}}
                                {{--<h4>--}}
                                    {{--Milestone {{$milestone->milestone_no}}--}}
                                    {{--<hr>--}}
                                {{--</h4>--}}
                                {{--<h5>Description</h5>--}}
                                {{--<p>{{$milestone->description}}</p>--}}
                                {{--{!! milestone_status($milestone->status) !!}--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3">--}}
                                {{--<a href="{{route('report_submission.milestone_details',--}}
                                {{--[\Illuminate\Support\Facades\Crypt::encrypt($report->id),$milestone->id])}}">--}}
                                    {{--Provide Documents</a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        <div class="card no-shadow mt-2">
            <h6 class="card-header p-1">
                {{$indicator_info->title}}
            </h6>
            <div class="card-content">
                <div class="card-body">
                    <form @if(isset($the_record))
                          action="{{route('report_submission.web_form_update_record',
                          [\Illuminate\Support\Facades\Crypt::encrypt($indicator_info->id),$the_record->id])}}"
                          @else
                          action="{{route('report_submission.save_webform',[$indicator_info->id])}}"
                          @endif
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="report_id" value="{{$report->id}}">
                        <input type="hidden" name="indicator_id" value="{{$indicator_info->id}}">
                        <input type="hidden" name="milestones_dlr_id" value="{{$milestone->id}}">
                        @if(isset($the_record))
                        <input type="hidden" name="record_id" value="{{$the_record->id}}">
                        @endif
                        <div class="row">
                            @for($a=1; $a<=4; $a++)
                                @php
                                    $required = "";
                                        if($a==1) {$required = "required";}
                                        $document = "document_$a";
                                @endphp
                                <div class="col-md-6">
                                    <div class="form-group{{ $errors->has($document) ? ' form-control-warning' : '' }}">
                                        <label for="{{$document}}">
                                            {{lang('Document Proof',$lang)}} {{$a}} @if($a==1)<span class="required">*</span>@endif
                                        </label>
                                        <input type="file" class="form-control" id="{{$document}}" name="{{$document}}"
                                               @if(isset($the_record))
                                               value="{{ (old($document)) ? old($document) : $the_record->$document }}"
                                               @else
                                               {{$required}}
                                               value="{{ (old($document)) ? old($document) :'' }}"
                                                @endif>
                                        @if ($errors->has($document))
                                            <p class="text-right mb-0">
                                                <small class="warning text-muted">{{ $errors->first($document) }}</small>
                                            </p>
                                        @endif
                                        @if(isset($the_record))
                                            @if($the_record->$document !="")
                                                {{--<strong>{{$the_record->$document}}</strong>--}}
                                                <a href="{{route('report_submission.report.download_dlr_file',
                            [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->$document])}}"
                                                   target="_blank" class="btn btn-link">
                                                    <span class="fa fa-file"></span> {{lang('Download',$lang)}} -
                                                    {{$the_record->$document}}
                                                </a>
                                                <br>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endfor
                            @for($a=1; $a<=3; $a++)
                                @php
                                    $required = "";
                                        if($a==1) {$required = "required";}
                                        $url = "url_$a";
                                @endphp
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has($url) ? ' form-control-warning' : '' }}">
                                        <label for="{{$url}}">{{lang('URL Proof',$lang)}} {{$a}}
                                            @if($a==1)<span class="required">*</span>@endif
                                        </label>
                                        <input type="text" class="form-control" {{$required}} id="{{$url}}" name="{{$url}}"
                                               @if(isset($the_record))
                                               value="{{ (old($url)) ? old($url) : $the_record->$url }}"
                                               @else
                                               value="{{ (old($url)) ? old($url) : '' }}"
                                                @endif>
                                        @if ($errors->has($url))
                                            <p class="text-right mb-0">
                                                <small class="warning text-muted">{{ $errors->first($url) }}</small>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endfor
                            <div class="col-md-12 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="status"
                                            @if($milestone->status > 1) checked @endif value="2" id="status">
                                    <label class="custom-control-label text-danger" for="status">
                                        {{lang('I have achieved all the Milestone Targets above and requesting for verification.
                                        No further changes should be done.',
                                        $lang)}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-secondary">
                                    {{lang('Submit for Verification',$lang)}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
@push('vendor-script')

    <script src="{{ asset('vendors/js/pickers/dateTime/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
@endpush
<script>


    $(function () {
        $('.datepicker').datetimepicker();
    });

    function editRecord(indicator,record){
        var path = "{{route('report_submission.web_form_edit_record')}}";
        $.ajaxSetup(    {
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            }
        });
        $.ajax({
            url: path,
            type: 'GET',
            data: {indicator_id:indicator,record_id:record},
            beforeSend: function(){
                $('#form-card').block({
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
            success: function(data){
                $('#form-card').empty();
                $('#form-card').html(data.theView);
            },
            complete:function(){
                $('#form-card').unblock();
            }
            ,
            error: function (data) {
                console.log(data)
            }
        });

    }
</script>

