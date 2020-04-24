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
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">DLR Report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <h5>Generate Report</h5>

        <form action="#" method="GET">
            @csrf
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="ace">Select ACE<span class="required">*</span></label>
                                    <select name="ace" id="ace" class="form-control" required>
                                        <option value="">Choose ACE</option>
                                        @foreach($aces as $ace)
                                            <option value="{{$ace->id}}">{{$ace->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('ace'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('ace') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="reporting_year">Reporting Year<span class="required">*</span></label>
                                    <select name="reporting_year" id="reporting_year" class="form-control" required>
                                        <option value="">Choose Year</option>
                                        <option value="">2019</option>
                                        <option value="">2020</option>
                                        <option value="">2021</option>
                                        <option value="">2022</option>
                                        <option value="">2023</option>
                                        <option value="">2024</option>
                                    </select>
                                    @if ($errors->has('reporting_year'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('reporting_year') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dlr">DLR<span class="required">*</span></label>
                                    <select name="dlr" id="dlr" class="form-control" required>
                                        <option value="">Choose DLR</option>
                                        @foreach($options as $key => $name)
                                            <option value="{{$key}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('dlr'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('dlr') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-secondary square">
                                    <i class="ft-list mr-sm-1"></i>{{__('Generate')}}</button>
                            </div>
                        </div>
                    </div>
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

        // $('input[name=filter]').on('change', function() {
        //     var filter = $('input[name=filter]:checked').val();
        //
        //     if (filter == 'aces'){
        //         $("#forFieldCountry").css('display','none');
        //         $("#forACE").css('display','block');
        //     }
        //
        //     if (filter == 'field_country'){
        //         $("#forFieldCountry").css('display','block');
        //         $("#forACE").css('display','none');
        //     }
        //
        // });
        {{--function loadFields() {--}}
            {{--var selected = $('#indicator').val();--}}
            {{--// alert($selected);--}}
            {{--var path = "{{route('getIndicatorFields')}}"--}}
            {{--$.ajaxSetup(    {--}}
                {{--headers: {--}}
                    {{--'X-CSRF-Token': $('meta[name=_token]').attr('content')--}}
                {{--}--}}
            {{--});--}}
            {{--$.ajax({--}}
                {{--url: path,--}}
                {{--type: 'GET',--}}
                {{--data: {id:selected},--}}
                {{--beforeSend: function(){--}}
                    {{--$('#action-loader').block({--}}
                        {{--message: '<div class="ft-loader icon-spin font-large-1"></div>',--}}
                        {{--// timeout: 2000, //unblock after 2 seconds--}}
                        {{--overlayCSS: {--}}
                            {{--backgroundColor: '#ccc',--}}
                            {{--opacity: 0.8,--}}
                            {{--cursor: 'wait'--}}
                        {{--},--}}
                        {{--css: {--}}
                            {{--border: 0,--}}
                            {{--padding: 0,--}}
                            {{--backgroundColor: 'transparent'--}}
                        {{--}--}}
                    {{--});--}}
                    {{--$('#action-card').empty();--}}
                {{--},--}}
                {{--success: function(data){--}}
                    {{--console.log(data)--}}
                    {{--$('#action-card').html(data.theView);--}}
                {{--},--}}
                {{--complete:function(){--}}
                    {{--$('#action-loader').unblock();--}}
                    {{--$.getScript("http://127.0.0.1:8000/vendors/js/forms/select/select2.full.min.js")--}}
                {{--}--}}
                {{--,--}}
                {{--error: function (data) {--}}
                    {{--console.log(data)--}}
                {{--}--}}
            {{--});--}}
        {{--}--}}

        {{--$('.select2').select2({--}}
            {{--placeholder: "Select Indicator",--}}
            {{--allowClear: true--}}
        {{--});--}}

        {{--$("#checkAllACEs").change(function(){--}}
            {{--$('input[type=checkbox].forACES').not(this).prop('checked', this.checked);--}}
        {{--});--}}

        {{--//Enable or disable the bulk sms button when more than 1 checkbox is selected--}}
        {{--$("input[type=checkbox].forACES").change(function() {--}}
            {{--var NotChecked = $('input[type=checkbox].forACES:not(":checked")').length;--}}
            {{--if (NotChecked > 0){--}}
                {{--$("#checkAllACEs").prop('checked', false);--}}
            {{--}else{--}}
                {{--$("#checkAllACEs").prop('checked', true);--}}
            {{--}--}}
        {{--});--}}
    </script>
@endpush