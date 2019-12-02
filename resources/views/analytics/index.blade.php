@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
@endpush
@push('other-styles')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
@endpush
@section('content')
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
                                                <option  value="{{$this_ace->id}}">{{$this_ace->acronym}}</option>
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
                                        <label for="this_period" style="margin-top: 1.4rem">Select Reporting Period (s)<span class="required">*</span></label><br>
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
                        <div class="card-content collapse show">
                        <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-6 form-group {{ $errors->has('topic_name') ? ' form-control-warning' : ''}}">
                                        <label>Aggregate Topic <span class="required"> *</span> </label>
                                        <select class="form-control select-lg" name="topic_name" id="topic_name">
                                            <option selected value="">Select aggregate topic</option>
                                            <option value="Aggregate Student">Aggregate Student</option>
                                            <option value="Student Enrollment">Student Enrollment</option>
                                            <option value="Aggregate Internships/Outreach">Aggregate Internships/Outreach</option>
                                            <option selected value="Gender Distribution">Gender Distribution</option>
                                            <option value="AGGREGATE EXTERNAL REVENUE">Aggregate External Revenue</option>
                                            <option VALUE="AGGREGATE PROGRAMME ACCREDITATION">Aggregate Programme Accreditation</option>
                                        </select>
                                        @if ($errors->has('topic_name'))
                                            <p class="text-right">
                                                <small class="warning text-muted">{{ $errors->first('topic_name') }}</small>
                                            </p>
                                        @endif

                                    </div>
                                    <div class="col-6">
                                        <label for="ace_id">Select Ace <span class="required">*</span></label>
                                        <div class="input-group {{ $errors->has('selected_ace') ? ' form-control-warning' : ''}}">
                                            <select  multiple="multiple" name="selected_ace[]"  class="form-control select2" id="selected_ace" required>
                                                @foreach($aces as $this_ace)
                                                    <option  value="{{$this_ace->id}}">{{$this_ace->acronym}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('selected_ace'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('selected_ace') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="selected_period" style="margin-top: 1.4rem">Select Reporting Period (s) <span class="required"> *</span> </label><br>
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
                                                    <option  value="{{$period->id}}">{{$full_period}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('selected_period'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('selected_period') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="filter-by" style="margin-top: 1.1rem">Filter By</label>
                                        <div class="input-group">
                                            <select class="form-control select-lg filter_select" name="filter_by" id="filter_by">
                                                <option value=""  selected disabled>Add Filter...</option>
                                                <option value="Countries">Countries</option>
                                                <option value="Type of Centre">Type of Centre</option>
                                                <option value="Field of Study">Field of Study</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="accordion-icon-rotate left" id="forField" style="display:none">
                                            <div id="byField_1" class="card-header sm-white bg-gradient-4">
                                                <a href="#" class="card-title lead gray">Filter By Field of Study</a>
                                            </div>
                                                <div class="card-content">
                                                        @foreach($fields as $key=>$field)
                                                            <div class="d-inline-block custom-control custom-checkbox mr-1">
                                                                <input type="checkbox" class="custom-control-input forField" value="{{$field}}" name="field[]" id="field{{$key}}">
                                                                <label class="custom-control-label" for="field{{$key}}">{{$field}}</label>
                                                            </div>
                                                        @endforeach
                                                </div>
                                        </div>
                                        <div class="accordion-icon-rotate left" id="forCountry" style="display:none">
                                            <div id="byField_2" class="card-header sm-white bg-gradient-4" style="padding: 0.7rem 1.5rem;">
                                                <a href="#byCountry" aria-expanded="false" aria-controls="byCountry"
                                                   class="card-title lead gray">Filter By Country</a>
                                            </div>
                                            <div id="byCountry" role="tabpanel" aria-labelledby="byCountry" aria-expanded="false">
                                                <div class="card-content">
                                                        @foreach($countries as $country)
                                                            <div class="d-inline-block custom-control custom-checkbox mr-1">
                                                                <input type="checkbox" class="custom-control-input" value="{{$country->id}}" name="country[]" id="country{{$country->id}}">
                                                                <label class="custom-control-label" for="country{{$country->id}}">{{$country->country}}</label>
                                                            </div>
                                                        @endforeach
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
                                                        @foreach($type_of_centres as $key=>$centre)
                                                            <div class="d-inline-block custom-control custom-checkbox mr-1">
                                                                <input type="checkbox" class="custom-control-input typeofcentre" value="{{$centre}}" name="typeofcentre[]" id="typeofcentre{{$key}}">
                                                                <label class="custom-control-label" for="typeofcentre{{$key}}">{{$centre}}</label>
                                                            </div>
                                                        @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary block-custom-message" style="margin-top: 25px;"
                                                onclick="calculateAggregate()">Generate</button>
                                    </div>
                                </div>


                            <div class="row" id="container" style="min-width: 800px; height: 400px; max-width: 600px; margin: 0 auto">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('vendor-script')
{{--    <script src="{{asset('vendors/js/charts/echarts/echarts.js')}}" type="text/javascript"></script>--}}
<script src="{{ asset('vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
@endpush()
@push('end-script')
{{--    <script src="{{asset('js/scripts/pickers/dateTime/bootstrap-datetime.js')}}" type="text/javascript"></script>--}}
{{--    <script src="{{asset('js/scripts/pickers/dateTime/pick-a-datetime.js')}}" type="text/javascript"></script>--}}
    <script>
        // Single Date Range Picker
        $('.singledate').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true
        });
        $("document").ready(function () {
            showGenderDistribution();
        });
        $('.select2').select2({
            placeholder: "",
            allowClear: true
        });

        $(document).ready(function(){
            $('.filter_select').change(function(){
                if($(this).val() != ''){
                    var filter = $('.filter_select').val();
                    if (filter == 'Field of Study'){
                        $("#forField").css('display','block');
                        $("#forCountry").css('display','none');
                        $("#typeofcentre").css('display','none');

                    }
                    if (filter == 'Countries'){
                        $("#forCountry").css('display','block');
                        $("#forField").css('display','none');
                        $("#typeofcentre").css('display','none');

                    }
                    if (filter == 'Type of Centre'){
                        $("#typeofcentre").css('display','block');
                        $("#forField").css('display','none');
                        $("#forCountry").css('display','none');
                    }



                }

                });
        });

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


        function  calculateAggregate() {
            if($("input[name='selected_period[]']:checkbox:checked").length == 0){
                toastr['warning']('Input field for Reporting Period is required', 'failed','{positionClass:toast-top-right, "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 8000}');
                return false;
            };

            if($("#topic_name").val() ==""){
                toastr['warning']('Input field for aces is required', 'failed','{positionClass:toast-top-right, "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 8000}');
                return false;
            }

            let topic_name = $("#topic_name").val();
            let selected_period = $("#selected_period").val();
            let selected_ace=$('#selected_ace').val();
            let filter =  $('.filter_select').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url:"{{route('analytics.calculateAggregate')}}",
                data: {selected_period: selected_period,selected_ace: selected_ace,topic_name:topic_name,filter:filter},
                beforeSend: function () {

                },
                success: function (data) {
                    console.log(data);
                    if (topic_name == "Gender Distribution") {
                        showGenderDistribution();
                    }

                    if (topic_name == "AGGREGATE EXTERNAL REVENUE") {
                        showAggregateExternalRevenue(data.years, data.target_external_revenue, data.actual_external_revenue);
                    }
                    if (topic_name == "AGGREGATE PROGRAMME ACCREDITATION") {
                        showAccreditationType(data.years,data.international_accreditation,data.national_accreditation);
                    }
                    if (topic_name == "Aggregate Student") {
                        graphAggregateStudents(data.years, data.total_students, data.regional_students, data.national_students, data.target_students);
                    }
                    if (topic_name === "Student Enrollment") {
                        getStudentEnrolment(data.years,data.total_enrolled, data.phd_students, data.masters_students, data.prof_students);
                    }
                    if (topic_name === "Aggregate Internships/Outreach") {
                        getAggregateInternships(data.years,data.student_internship, data.faculty_internship);
                    }
                },
                complete: function () {

                },
                error: function () {
                    console.log("error");
                }

            });
        }


        function  getAggregateInternships(years,student_internship,faculty_internship){
            Highcharts.chart('container', {
                title: {
                    text: 'AGGREGATE INTERNSHIPS'
                },
                xAxis: {
                    categories: years
                },
                labels: {
                    items: [{
                        html: years[0] + "-" +years[years.length-1],
                        style: {
                            left: '5px',
                            top: '18px',
                            color: ( // theme
                                Highcharts.defaultOptions.title.style &&
                                Highcharts.defaultOptions.title.style.color
                            ) || 'black'
                        }
                    }]
                },
                series: [{
                    type: 'column',
                    name: 'Student Internships',
                    data: student_internship,
                }, {
                    type: 'column',
                    name: 'Faculty Internships',
                    data: faculty_internship,
                }]


            });
        }




    function getStudentEnrolment(years,total_enrolled,phd_students,masters_students,prof_students){
        Highcharts.chart('container', {
            title: {
                text: 'Student Enrolment'
            },
            xAxis: {
                categories: years
            },
            labels: {
                items: [{
                    html: '',
                    style: {
                        left: '5px',
                        top: '18px',
                        color: ( // theme
                            Highcharts.defaultOptions.title.style &&
                            Highcharts.defaultOptions.title.style.color
                        ) || 'black'
                    }
                }]
            },
            series: [{
                type: 'column',
                name: 'PhD',
                data: phd_students
            }, {
                type: 'column',
                name: 'Masters',
                data: masters_students
            }, {
                type: 'column',
                name: 'STC',
                data: prof_students
            }, {
                type: 'column',
                name: 'Total Students',
                data: total_enrolled,
            }]
        });
    }

        function graphAggregateStudents(years,total_students,regional_students,national_students,target_students) {

            var charts = new Highcharts.chart('container', {
                title: {
                    text: 'AGGREGATE STUDENT'
                },
                xAxis: {
                    categories: years,
                },
                labels: {
                    items: [{
                        html: '',
                        style: {
                            left: '5px',
                            top: '18px',
                            color: ( // theme
                                Highcharts.defaultOptions.title.style &&
                                Highcharts.defaultOptions.title.style.color
                            ) || 'black'
                        }
                    }]
                },


                series: [
                    {
                        type: 'column',
                    name: 'Total Students',
                   data: total_students,

                },
                    {
                        type: 'column',
                        name: 'National Students',
                        data: national_students,

                    },
                    {
                        type: 'column',
                        name: 'Regional Students',
                        data: regional_students,

                    },
                    {
                    type: 'spline',
                    name: 'Target',
                    data: target_students,
                    marker: {
                        lineWidth: 2,
                        lineColor: Highcharts.getOptions().colors[3],
                        fillColor: 'white'
                    }
                }
                ],



            });
        }

        function showAccreditationType(years,international_accreditation,national_accreditation) {
            Highcharts.chart('container', {
                title: {
                    text: 'AGGREGATE PROGRAMME ACCREDITATION'
                },
                xAxis: {
                    categories: years
                },
                labels: {
                    items: [{
                        html: '',
                        style: {
                            left: '5px',
                            top: '18px',
                            color: ( // theme
                                Highcharts.defaultOptions.title.style &&
                                Highcharts.defaultOptions.title.style.color
                            ) || 'black'
                        }
                    }]
                },
                series: [{
                    type: 'column',
                    name: 'National Accreditation',
                    data: national_accreditation,
                }, {
                    type: 'column',
                    name: 'International Accreditation',
                    data: international_accreditation,
                }]


            });

        }
        // AGGREGATE EXTERNAL REVENUE
        function showAggregateExternalRevenue(years,target_external_revenue,actual_external_revenue) {
            Highcharts.chart('container', {
                title: {
                    text: 'AGGREGATE EXTERNAL REVENUE'
                },
                xAxis: {
                    categories: years
                },
                labels: {
                    items: [{
                        html: years[0] + "-" +years[years.length-1],
                        style: {
                            left: '5px',
                            top: '18px',
                            color: ( // theme
                                Highcharts.defaultOptions.title.style &&
                                Highcharts.defaultOptions.title.style.color
                            ) || 'black'
                        }
                    }]
                },
                series: [{
                    type: 'column',
                    name: 'target_external_revenue',
                    data: target_external_revenue,
                }, {
                    type: 'column',
                    name: 'actual_external_revenue',
                    data: actual_external_revenue,
                }]


            });

        }



        //Script for the Age Distribution
        function showGenderDistribution() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "{{route('analytics.getGenderDistribution')}}",
                // data: {the_date: the_date},
                beforeSend: function () {
                    $("#preloader").fadeIn();
                },
                success: function (data) {
                    console.log(data);
                    genderDistribution(data.male,data.female);
                },
                complete: function () {

                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
        function genderDistribution(male,female){
            Highcharts.chart('container', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: "Gender Distribution",

                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: true
                    }
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Gender',
                    colorByPoint: true,
                    data: [{
                        name: 'Female',
                        y: female,
                        // sliced: true,
                        // selected: true
                    },{
                        name: 'Male',
                        y: male,
                        // sliced: true,
                        // selected: true
                    }]
                }]
            });
        }
    </script>
@endpush