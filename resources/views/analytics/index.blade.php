@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">
@endpush
@push('other-styles')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
@endpush
@section('content')
    @php // dd(request()->query); @endphp
    {{--<div class="content-header row">--}}
    {{--</div>--}}
    <div class="content-body">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">CUMULATIVE PROJECT PDO RESULTS</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body load-area">
                            {{--<form action="#">--}}
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label for="ace_id">Select Ace <span class="required">*</span></label>
                                        <div class="input-group {{ $errors->has('ace_id') ? ' form-control-warning' : '' }}">
                                        <select  multiple="multiple" name="ace_id[]"  class="form-control select2" id="ace_id" required>
                                            @foreach($aces as $this_ace)
                                                <option {{request()->query->has('ace_id') && in_array($this_ace,request()->query->get('ace_id')) ? "selected": "" }}  value="{{$this_ace->id}}">{{$this_ace->acronym}}</option>
                                            @endforeach
                                        </select>
                                            @if ($errors->has('ace_id'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('ace_id') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                        <div class="col-md-12">
                                            <label for="this_period" style="margin-top: 1.4rem">Select Reporting Period (s)<span class="required">*</span></label><br>
                                        </div>
                                        @foreach($periods as $key => $this_period)
                                            @php
                                                $start_period = date('m-Y',strtotime($this_period->period_start));
                                                $monthNum1=date('m',strtotime($this_period->period_start));
                                                $monthName1 = date("M", mktime(0, 0, 0, $monthNum1, 10));
                                                $year1 = date('Y',strtotime($this_period->period_start));

                                                $start = $monthName1 .', '.$year1;
                                                $monthNum2=date('m',strtotime($this_period->period_end));
                                                $monthName2 = date("M", mktime(0, 0, 0, $monthNum2, 10));
                                                $year2 = date('Y',strtotime($this_period->period_end));
                                                $end =$monthName2 .', '.$year2;
                                                $full_period = $start . "   -    " . $end;
                                            @endphp
                                            <div class=" col-md-4 d-inline">
                                                <div class="d-inline custom-control custom-checkbox {{ $errors->has('this_period') ? ' form-control-warning' : ''}}">
                                                    <input type="checkbox" name="this_period[]" value="{{$this_period->id}}" id="this_period{{$key}}">
                                                    <label for="this_period{{$key}}">{{$full_period}}</label>
                                                    @if ($errors->has('this_period'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('this_period') }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    </div>
                                    <div class="col-md-4 offset-4">
                                        <button class="btn btn-primary block-custom-message" style="margin-top: 25px;"
                                                onclick="showCumulativePDO()">Generate Report</button>
                                    </div>
                                </div>
                            {{--</form>--}}
                            <div id="showPDOTable" class="mt-4">
                                <h1 class="text-center text-bold-400">Please select dates the generate results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--aggregate--}}

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">AGGREGATE STATISTICS</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                            </ul>
                        </div>
                        </div>
                    @php //dd(request()->query->get('topic_name')); @endphp
                        <div class="card-content collapse show">
                        <div class="card-body">
                            <form method="get" action="{{route('analytics.getGenderDistribution')}}">
                                <div class="form-group row">
                                    <div class="col-6 form-group {{ $errors->has('topic_name') ? ' form-control-warning' : ''}}">
                                        <label>Aggregate Topic <span class="required"> *</span> </label>
                                        <select onchange="changeonFields()" class="form-control select-lg" name="topic_name" id="topic_name">
                                            <option selected value="">Select aggregate topic</option>
                                            <option {{request()->query->get('topic_name') == "Aggregate Student" ? "selected": "" }}  value="Aggregate Student">Aggregate Student</option>
                                            <option {{request()->query->get('topic_name') == "Student Enrollment" ? "selected": "" }} value="Student Enrollment">Student Enrollment</option>
                                            <option {{request()->query->get('topic_name') == "Aggregate Internships" ? "selected": "" }} value="Aggregate Internships">Aggregate Internships</option>
                                            <option {{request()->query->get('topic_name') == "Gender Distribution" ? "selected": "" }} value="Gender Distribution">Gender Distribution</option>
                                            <option {{request()->query->get('topic_name') == "list of donors" ? "selected": "" }} value="list of donors">List of donors</option>
                                            <option {{request()->query->get('topic_name') == "Aggregate Programme Accreditation" ? "selected": "" }} value="Aggregate Programme Accreditation">Aggregate Programme Accreditation</option>
                                        </select>
                                        @if ($errors->has('topic_name'))
                                            <p class="text-right">
                                                <small class="warning text-muted">{{ $errors->first('topic_name') }}</small>
                                            </p>
                                        @endif
                                    </div>

                                    <div class="col-md-6 hidden" id="selected_target_field" >
                                        <div class="input-group">
                                            <label>Choose Target Year(s)</label>
                                            <select style="width: 100% !important;" multiple="multiple" name="selected_target[]"  class="form-control select2" id="selected_target" >
                                                <option>Year...</option>
                                                {{--@foreach($aces as $this_ace)--}}
                                                {{--<option  value="{{$this_ace->id}}">{{$this_ace->acronym}}</option>--}}
                                                {{--@endforeach--}}
                                            </select>
                                            @if ($errors->has('selected_target'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('selected_target') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="selected_period" >Select Reporting Period (s) <span class="required"> *</span> </label><br>
                                        <div class="input-group {{ $errors->has('selected_period') ? ' form-control-warning' : ''}}">
                                            <select  multiple="multiple" name="selected_period[]"  class="form-control select2" id="selected_period" required>
                                                <option disabled>Select Period</option>
                                                @foreach($periods as $period)
                                                    @php
                                                        $start_period = date('m-Y',strtotime($period->period_start));
                                                        $monthNum1=date('m',strtotime($period->period_start));
                                                        $monthName1 = date("M", mktime(0, 0, 0, $monthNum1, 10));
                                                        $year1 = date('Y',strtotime($period->period_start));

                                                        $start = $monthName1 .' - '.$year1;
                                                        $monthNum2=date('m',strtotime($period->period_end));
                                                        $monthName2 = date("M", mktime(0, 0, 0, $monthNum2, 10));
                                                        $year2 = date('Y',strtotime($period->period_end));
                                                        $end =$monthName2 .' - '.$year2;
                                                        $full_period = $start . "   to    " . $end;
                                                    @endphp
                                                    <option {{!empty(request()->query->get('selected_period')) && in_array($period->id,request()->query->get('selected_period')) ? "selected": "" }}  value="{{$period->id}}">{{$full_period}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('selected_period'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('selected_period') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">

                                        @php
                                           // dd(request()->query->get('filter_by'));  --}}
                                            $filtercount = 111 ;@endphp
                                        @if(request()->query->has('filter_by'))
                                        @foreach(request()->query->get('filter_by') as $mainkey => $filter_by)
                                        <div id="filter-{{$filtercount}}" class="row">
                                            <div class="col-3">
                                                <label for="filter-by" style="margin-top: 1.1rem">Filter By</label>
                                                <div class="input-group">
                                                    <select class="form-control select-lg filter_select_{{$filtercount}}" onchange="filterselect('filter_select_{{$filtercount}}','{{$filtercount}}')" name="filter_by[]" id="filter_by">
                                                        <option {{$filter_by == 'Countries' ? "selected":""}} selected value="Countries">Countries</option>
                                                        <option {{$filter_by == 'Type of Centre' ? "selected":""}} value="Type of Centre">Type of Centre</option>
                                                        <option {{$filter_by == 'Field of Study' ? "selected":""}} value="Field of Study">Field of Study</option>
                                                        <option {{$filter_by == 'ACE' ? "selected":""}} value="ACE">Ace</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="accordion-icon-rotate left" id="forField{{$filtercount}}" @if($filter_by == 'Field of Study') @else style="display:none" @endif>
                                                    <div id="byField_2" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
                                                        <a href="#byCountry" aria-expanded="false" aria-controls="byCountry"
                                                           class="card-title lead gray">Filter By Field of Study</a>
                                                    </div>
                                                    <div class="card-content">
                                                        <select  multiple="multiple" name="field[]"  class="form-control select2" id="field[]" style="width: 100% !important;">
                                                            @foreach($fields as $key=>$field)
                                                                <option {{!empty(request()->query->get('field')) && in_array($field,request()->query->get('field')) ? "selected":""}}  value="{{$field}}">{{$field}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="accordion-icon-rotate left" id="forCountry{{$filtercount}}" @if($filter_by == 'Countries') @else style="display:none" @endif>
                                                    <div id="byField_2" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
                                                        <a href="#byCountry" aria-expanded="false" aria-controls="byCountry"
                                                           class="card-title lead gray">Filter By Country</a>
                                                    </div>
                                                    <div id="byCountry" role="tabpanel" aria-labelledby="byCountry" aria-expanded="false">
                                                        <div class="card-content">
                                                            <select  multiple="multiple" name="country[]"  class="form-control select2 multiple_values"  style="width: 100% !important;">
                                                                @foreach($countries as $country)
                                                                    <option {{!empty(request()->query->get('country')) && in_array($country->id,request()->query->get('country')) ? "selected":""}} value="{{$country->id}}">{{$country->country}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-icon-rotate left" id="typeofcentre{{$filtercount}}" @if($filter_by == 'Type of Centre') @else style="display:none" @endif>
                                                    <div id="byField_3" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
                                                        <a href="#typeofcentre" aria-expanded="false" aria-controls="typeofcentre"
                                                           class="card-title lead gray">Filter By Type of Centre</a>
                                                    </div>
                                                    <div id="typeofcentre" role="tabpanel" aria-labelledby="typeofcentre" aria-expanded="false">
                                                        <div class="card-content">
                                                            <select  multiple="multiple" name="typeofcentre[]"  class="form-control select2 multiple_values" id="typeofcentre{{$key}}" style="width: 100% !important;">
                                                                @foreach($type_of_centres as $key=>$centre)
                                                                    <option {{!empty(request()->query->get('typeofcentre')) && in_array($centre,request()->query->get('typeofcentre')) ? "selected":""}}  value="{{$centre}}">{{$centre}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-icon-rotate left" role="tabpanel" id="filterbyace{{$filtercount}}"  @if($filter_by == 'ACE') @else style="display:none" @endif >
                                                    <div id="byField_3" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
                                                        <a href="#typeofcentre" aria-expanded="false" aria-controls="typeofcentre"
                                                           class="card-title lead gray">Filter By Ace</a>
                                                    </div>
                                                    <div role="tabpanel" aria-labelledby="filterbyace" aria-expanded="false">
                                                        <div class="card-content">
                                                            <select  multiple="multiple" name="selected_ace[]"  class="form-control select2 multiple_values" id="selected_ace" style="width: 100% !important;">
                                                                @foreach($aces as $this_ace)
                                                                    <option {{!empty(request()->query->get('selected_ace')) && in_array($this_ace->id,request()->query->get('selected_ace')) ? "selected":""}}  value="{{$this_ace->id}}">{{$this_ace->acronym}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            @if($mainkey == '0')
                                                <div class="col-md-1">
                                                    <span style="float: left!important;margin-top: 42px">
                                                        <button type="button" onclick="addfilter()" class="btn btn-md btn-success"><i class="fa fa-plus"></i> </button>
                                                    </span>
                                                </div>
                                            @else
                                                <div class="col-md-1">
                                                    <span style="float: left!important;margin-top: 42px">
                                                        <button type="button" onclick="removefilter('filter-{{$filtercount}}')" class="btn btn-md btn-danger"><i class="fa fa-close"></i> </button>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        @php $filtercount++; @endphp
                                        @endforeach

                                        @else
                                            <div class="row">
                                                <div class="col-3">
                                                    <label for="filter-by" style="margin-top: 1.1rem">Filter By</label>
                                                    <div class="input-group">
                                                        <select class="form-control select-lg filter_select" onchange="filterselect('filter_select')" name="filter_by[]" id="filter_by">
                                                            <option  selected value="Countries">Countries</option>
                                                            <option  value="Type of Centre">Type of Centre</option>
                                                            <option value="Field of Study">Field of Study</option>
                                                            <option value="ACE">Ace</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="accordion-icon-rotate left" id="forField" style="display:none">
                                                        <div id="byField_2" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
                                                            <a href="#byCountry" aria-expanded="false" aria-controls="byCountry"
                                                               class="card-title lead gray">Filter By Field of Study</a>
                                                        </div>
                                                        <div class="card-content">
                                                            <select  multiple="multiple" name="field[]"  class="form-control select2" id="field[]" style="width: 100% !important;">
                                                                @foreach($fields as $key=>$field)
                                                                    <option  value="{{$field}}">{{$field}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-icon-rotate left" id="forCountry">
                                                        <div id="byField_2" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
                                                            <a href="#byCountry" aria-expanded="false" aria-controls="byCountry"
                                                               class="card-title lead gray">Filter By Country</a>
                                                        </div>
                                                        <div id="byCountry" role="tabpanel" aria-labelledby="byCountry" aria-expanded="false">
                                                            <div class="card-content">
                                                                <select  multiple="multiple" name="country[]"  class="form-control select2 multiple_values"  style="width: 100% !important;">
                                                                    @foreach($countries as $country)
                                                                        <option  value="{{$country->id}}">{{$country->country}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-icon-rotate left" id="typeofcentre" style="display:none">
                                                        <div id="byField_3" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
                                                            <a href="#typeofcentre" aria-expanded="false" aria-controls="typeofcentre"
                                                               class="card-title lead gray">Filter By Type of Centre</a>
                                                        </div>
                                                        <div id="typeofcentre" role="tabpanel" aria-labelledby="typeofcentre" aria-expanded="false">
                                                            <div class="card-content">
                                                                <select  multiple="multiple" name="typeofcentre[]"  class="form-control select2 multiple_values" id="typeofcentre{{$key}}" style="width: 100% !important;">
                                                                    @foreach($type_of_centres as $key=>$centre)
                                                                        <option  value="{{$centre}}">{{$centre}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-icon-rotate left" role="tabpanel" id="filterbyace" style="display:none">
                                                        <div id="byField_3" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
                                                            <a href="#typeofcentre" aria-expanded="false" aria-controls="typeofcentre"
                                                               class="card-title lead gray">Filter By Ace</a>
                                                        </div>
                                                        <div role="tabpanel" aria-labelledby="filterbyace" aria-expanded="false">
                                                            <div class="card-content">
                                                                <select  multiple="multiple" name="selected_ace[]"  class="form-control select2 multiple_values" id="selected_ace" style="width: 100% !important;">
                                                                    @foreach($aces as $this_ace)
                                                                        <option  value="{{$this_ace->id}}">{{$this_ace->acronym}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-1">
                                        <span style="float: left!important;margin-top: 42px">
                                            <button type="button" onclick="addfilter()" class="btn btn-md btn-success"><i class="fa fa-plus"></i> </button>
                                        </span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="new-filter">

                                        </div>
                                    </div>


                                </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <button type="submit" name="generate_report" class="btn btn-primary block-custom-message" style="margin-top: 25px;"
                                            >Generate</button>
                                        </div>
                                    </div>
                            </form>
                            @if(isset($request) && $request->query->has('generate_report'))
                                    @include('analytics.table')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('vendor-script')
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
@endpush()
@push('end-script')
 <script>

     function changeonFields(){
         var topic_name = $('#topic_name').val();
         if(topic_name == 'Aggregate Student'){
             $('#selected_target_field').removeClass("hidden");
         }else{
             $('#selected_target_field').addClass("hidden");
         }

     }
        // Single Date Range Picker
        $('.singledate').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true
        });

        $('.select2').select2({
            placeholder: "",
            allowClear: true
        });

            function filterselect(target,counter) {
                if(typeof counter === "undefined" || counter === null){
                    counter = "";
                }

                if ($('.' + target).val() != '') {
                    var filter = $('.' + target).val();
                    if (filter == 'Field of Study') {
                        $("#forField"+counter).css('display', 'block');
                        $("#forCountry"+counter).css('display', 'none');
                        $("#typeofcentre"+counter).css('display', 'none');
                        $("#filterbyace"+counter).css('display', 'none');

                    }
                    if (filter == 'Countries') {
                        $("#forCountry"+counter).css('display', 'block');
                        $("#forField"+counter).css('display', 'none');
                        $("#typeofcentre"+counter).css('display', 'none');

                    }
                    if (filter == 'Type of Centre') {
                        $("#typeofcentre"+counter).css('display', 'block');
                        $("#forField"+counter).css('display', 'none');
                        $("#forCountry"+counter).css('display', 'none');
                    }
                    if (filter == "ACE") {
                        $("#filterbyace"+counter).css('display', 'block');
                        $("#forField"+counter).css('display', 'none');
                        $("#forCountry"+counter).css('display', 'none');
                        $("#typeofcentre"+counter).css('display', 'none');
                    }


                }
            }

     function addfilter() {
         if($(".multiple_values option:selected").length == 0){
             toastr['warning']('Filter first ooh', 'failed','{positionClass:toast-top-right, "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 8000}');
             return false;
         }
             $.ajax({
                url: "{{route('analytics.add_filter')}}",
                 type: 'post',
                 headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                 success: function (data) {
                     $('.new-filter').append(data);
                 }
             });
     }

     function removefilter(filterid) {
         $('#'+filterid).remove();
     }
        //Script for Cumulative PDO
        function showCumulativePDO() {
            if($("select[name='ace_id[]'] option:selected").length == 0){
                toastr['warning']('Input field for aces is required', 'failed','{positionClass:toast-top-right, "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 8000}');
                return false;
            };
            if($("input[name='this_period[]']:checkbox:checked").length == 0){
                toastr['warning']('Input field for Reporting Period is required', 'failed','{positionClass:toast-top-right, "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 8000}');
                return false;
            };

            let block_ele = $(this).closest('.load-area');
            let this_period = $("input[name='this_period[]']:checkbox:checked").map(function () {
                return $(this).val(); }).get();
            let this_ace=$('#ace_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "{{route('analytics.getCumulativePDO')}}",
                data: {this_period: this_period,this_ace: this_ace},

                beforeSend: function () {

                    $(block_ele).block({
                        message: '<span class="semibold"> Please wait...</span>',
                        overlayCSS: {
                            backgroundColor: '#fff',
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

                success: function (data) {

                    $("#showPDOTable").html(data.the_view);


                },
                complete: function () {
                    $(block_ele).unblock();
                },
                error: function (data) {
                    console.log(data);
                }
            })
        }

    </script>
@endpush