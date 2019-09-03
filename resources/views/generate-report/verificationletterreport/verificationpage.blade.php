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
    {{--@php dd(old('indicator.3')) @endphp--}}
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Generation</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Status
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <h5> Report</h5>

        <form action="{{route('report_generation.verificationletter.verificationpagereport')}}" method="get">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">

                                    <label for="start">start date<span class="required">*</span></label>
                                    <input type="date" required value="{{ old('start')? old('start') : '' }}"
                                           name="start" class="form-control" id="start">
                                    @if ($errors->has('start'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('start') }}</small>
                                        </p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="end">end date<span class="required">*</span></label>
                                    <input type="date" required value="{{ old('end')? old('end') : 'end' }}"
                                           name="end" class="form-control" id="end">
                                    @if ($errors->has('end'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('end') }}</small>
                                        </p>
                                    @endif
                                </div>

                                {{-- <div class="form-group">
                                    <label for="filters"> <i class="ft-filter"></i> Select Filters Type</label>
                                    <hr style="margin-top: 5px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div clas="skin skin-square">
                                                <input type="radio" name="filter" checked value="students" id="is_students">
                                                <label for="is_students" class="">By Students</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div clas="skin skin-square">
                                                <input type="radio" name="filter" value="revenue" id="is_revenue">
                                                <label for="is_revenue" class="">By Revenue</label>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-secondary square">
                                            <i class="ft-pie-chart mr-sm-1"></i>{{__('Generate')}}</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">

                    <div class="card collapse-icon accordion-icon-rotate left" id="forACE" style="display:block">
                        <div id="byAces_1" class="card-header bg-amber bg-darken-4" style="padding: 0.7rem 1.5rem;">
                            <a data-toggle="collapse" href="#byAces" aria-expanded="false" aria-controls="byAces"
                               class="card-title lead white">Filter By ACEs</a>
                        </div>
                        <div id="byAces" role="tabpanel" aria-labelledby="byAces_1" class="collapse">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="custom-control custom-checkbox mr-1">
                                        <input type="checkbox" checked class="custom-control-input" id="checkAllACEs">
                                        <label class="custom-control-label" for="checkAllACEs">All ACEs</label>
                                    </div>
                                    <hr>
                                    @foreach($aces as $ace)
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" checked class="custom-control-input forACES" value="{{$ace->id}}" name="aces[]" id="ace{{$ace->id}}">
                                            <label class="custom-control-label" for="ace{{$ace->id}}">{{$ace->name}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--<div class="card collapse-icon accordion-icon-rotate left" id="forStudents" style="display:block">--}}
                        {{--<div id="byStudents_1" class="card-header bg-amber bg-accent-4" style="padding: 0.7rem 1.5rem;">--}}
                            {{--<a data-toggle="collapse" href="#byStudents" aria-expanded="true" aria-controls="byField"--}}
                               {{--class="card-title lead white">Filter By Students</a>--}}
                        {{--</div>--}}
                        {{--<div id="byStudents" role="tabpanel" aria-labelledby="byStudents_1" class="collapse show"--}}
                             {{--aria-expanded="false">--}}
                            {{--<div class="card-content">--}}
                                {{--<div class="card-body">--}}
                                    {{--@foreach($fields as $key=>$field)--}}
                                        {{--<div class="d-inline-block custom-control custom-checkbox mr-1">--}}
                                            {{--<input type="checkbox" class="custom-control-input forField" value="{{$field}}" name="field[]" id="field{{$key}}">--}}
                                            {{--<label class="custom-control-label" for="field{{$key}}">{{$field}}</label>--}}
                                        {{--</div>--}}
                                    {{--@endforeach--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="card collapse-icon accordion-icon-rotate left" id="forRevenue" style="display:none">--}}
                        {{--<div id="byRevenue_1" class="card-header bg-amber bg-accent-4" style="padding: 0.7rem 1.5rem;">--}}
                            {{--<a data-toggle="collapse" href="#byRevenue" aria-expanded="true" aria-controls="byField"--}}
                               {{--class="card-title lead white">Filter By Revenue</a>--}}
                        {{--</div>--}}
                        {{--<div id="byRevenue" role="tabpanel" aria-labelledby="byRevenue_1" class="collapse show"--}}
                             {{--aria-expanded="false">--}}
                            {{--<div class="card-content">--}}
                                {{--<div class="card-body">--}}
                                    {{--@foreach($fields as $key=>$field)--}}
                                        {{--<div class="d-inline-block custom-control custom-checkbox mr-1">--}}
                                            {{--<input type="checkbox" class="custom-control-input forField" value="{{$field}}" name="field[]" id="field{{$key}}">--}}
                                            {{--<label class="custom-control-label" for="field{{$key}}">{{$field}}</label>--}}
                                        {{--</div>--}}
                                    {{--@endforeach--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
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

        $('input[name=filter]').on('change', function() {
            var filter = $('input[name=filter]:checked').val();

            if (filter == 'revenue'){
                $("#forStudents").css('display','none');
                $("#forRevenue").css('display','block');
            }

            if (filter == 'students'){
                $("#forStudents").css('display','block');
                $("#forRevenue").css('display','none');
            }

        });

        $('.select2').select2({
            placeholder: "Select Indicator",
            allowClear: true
        });

        $("#checkAllACEs").change(function(){
            $('input[type=checkbox].forACES').not(this).prop('checked', this.checked);
        });

        //Enable or disable the bulk sms button when more than 1 checkbox is selected
        $("input[type=checkbox].forACES").change(function() {
            var NotChecked = $('input[type=checkbox].forACES:not(":checked")').length;
            if (NotChecked > 0){
                $("#checkAllACEs").prop('checked', false);
            }else{
                $("#checkAllACEs").prop('checked', true);
            }
        });
    </script>
@endpush