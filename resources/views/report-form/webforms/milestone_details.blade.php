@extends('layouts.app')
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
            <div class="col-md-12">
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
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @php $editable = true; $disabled = "";
            if ($milestone->status > 1) {$editable = false; $disabled = "disabled";}
        @endphp
        <div class="card no-shadow mt-2">
            <h6 class="card-header p-1">
                {{$indicator_info->title}}
            </h6>
            <div class="card-content">
                <div class="card-body">
                    @if($editable)
                        {{--@php $editable = false; @endphp--}}
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
                    @endif
                        <div class="row">
                            @for($a=1; $a<=4; $a++)
                                @php
                                    $required = "";
                                        if($a==1) {$required = "required";}
                                        $document = "document_$a";
                                        $document_description = "document_$a"."_description";
                                @endphp
                                <div class="col-md-6">
                                    <div class="form-group{{ $errors->has($document_description) ? ' form-control-warning' : '' }}">
                                        <label for="{{$document_description}}">
                                            {{lang('Document Description',$lang)}} {{$a}} @if($a==1)<span class="required">*</span>@endif
                                        </label>
                                        @if($editable)
                                            <input type="text" class="form-control" {{$disabled}} id="{{$document_description}}" name="{{$document_description}}"
                                                   @if(isset($the_record))
                                                   value="{{ (old($document_description)) ? old($document_description) : $the_record->$document_description }}"
                                                   @else
                                                   {{$required}}
                                                   value="{{ (old($document_description)) ? old($document_description) :'' }}"
                                                    @endif>
                                            @if ($errors->has($document_description))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first($document_description) }}</small>
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group{{ $errors->has($document) ? ' form-control-warning' : '' }}">
                                        <label for="{{$document}}">
                                            {{lang('Document Proof',$lang)}} {{$a}} @if($a==1)<span class="required">*</span>@endif
                                        </label>
                                        @if($editable)
                                        <input type="file" class="form-control" {{$disabled}} id="{{$document}}" name="{{$document}}"
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
                                        @endif
                                        @if(isset($the_record))
                                            @if($the_record->$document !="")
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
                                        <input type="text" class="form-control" {{$disabled}} {{$required}} id="{{$url}}" name="{{$url}}"
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
                                            @if($milestone->status > 1) checked @endif {{$disabled}} value="2" id="status">
                                    <label class="custom-control-label text-danger" for="status">
                                        {{lang('I have achieved all the Milestone Targets above and requesting for verification. No further changes shall be done.',
                                        $lang)}}
                                    </label>
                                </div>
                            </div>
                            @if($editable)
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-secondary">
                                    {{lang('Submit for Verification',$lang)}}</button>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection