@extends('layouts.app')
@push('vendor-styles')
    @php  str_replace('_', '-', app()->getLocale()); @endphp
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
@endpush
@push('other-styles')
@endpush
@section('content')
{{--    @php dd(app()->isLocale('en')); @endphp--}}
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item active">Settings
                        </li>
                        <li class="breadcrumb-item active">Indicators Forms
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content-body">
        {{--<div class="row">--}}
            {{--<div class="float-left">--}}
               {{--<a style="font-size: 16px" class="dropdown-toggle nav-link" id="dropdown-flag" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                   {{--{{__('creator.language')}}  @if (app()->isLocale('en')) <i class="flag-icon flag-icon-gb"> @else <i class="flag-icon flag-icon-fr">@endif</i>--}}
                    {{--<span class="selected-language"></span>--}}
                {{--</a>--}}
                {{--<div class="dropdown-menu" aria-labelledby="dropdown-flag">--}}
                    {{--<a class="dropdown-item" href="{{url('locale/en')}}"><i class="flag-icon flag-icon-gb"></i> English</a>--}}
                    {{--<a class="dropdown-item" href="{{url('locale/fr')}}"><i class="flag-icon flag-icon-fr"></i> French</a>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}


        <p class="text-right">

            <a href="{{route('settings.indicator.generate_form.create')}}" class="btn btn-secondary square">Add Form</a>
        </p>

        <div class="row">
            @foreach($fields as $key=>$field)
            @php
                $field = (object)$field;
                $ind = \App\Indicator::find($field->indicator);
            @endphp
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                {{$ind->title}}
                            </h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li class="badge-circle"><i class="fa fa-language"> {{$field->language["Text"]}}</i></li>
                                    <li><a href="{{route('settings.indicator.generate_form.edit',[$field->_id])}}" class="btn btn-sm btn-outline-primary"><i class="ft-edit-2 mr-sm-1"></i>Edit</a></li>
                                    <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse">
                            <div class="card-body">
                                <p>{{$ind->title}}</p>
                                @foreach($field->fields as $label)
                                    <div class="badge badge-pill badge-square badge-secondary mb-sm-1">
                                        {{$label['label']}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script>
        $('.select2').select2({
            placeholder: "Select Indicator",
            allowClear: true
        });
    </script>
@endpush