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
                                        <div class="input-group">
                                        <select  multiple="multiple" name="ace_id"  class="form-control select2" id="ace_id" required>
                                            <option  value="{{$all_ace_ids}}">Select All Aces</option>
                                            @foreach($aces as $this_ace)
                                                <option  value="{{$this_ace->id}}">{{$this_ace->acronym}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <label for="start_date">Start</label>
                                        <div class="input-group">
                                            <input type="text" name="start" id="start_date" class="form-control singledate" value="">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                  <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <label for="end_date">End</label>
                                        <div class="input-group">
                                            <input type="text" name="end" id="end_date" class="form-control singledate" value="">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                  <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary block-custom-message" style="margin-top: 25px;"
                                                onclick="showCumulativePDO()">Generate</button>
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
                            <div id="filter">
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label>Aggregate Topic: </label>
                                        <select class="form-control select-lg" name="topic_name" id="topic_name">
                                            <option value="">Select aggregate topic</option>
                                            <option value="Aggregate Student">Aggregate Student</option>
                                            <option value="Gender Distribution">Gender Distribution</option>
                                            <option VALUE="AGGREGATE PROGRAMME ACCREDITATION">AGGREGATE PROGRAMME ACCREDITATION</option>
                                            <option VALUE="INTERNATIONAL ACCREDITATION">INTERNATIONAL ACCREDITATION</option>
                                            <option value="AGGREGATE EXTERNAL REVENUE">AGGREGATE EXTERNAL REVENUE</option>
                                            <option VALUE="ACE Publications in 2017">ACE Publications by Year</option>
                                            <option value="QUALITY EDUCATION & RESEARCH">QUALITY EDUCATION & RESEARCH</option>
                                            <option value="STUDENT ENROLLMENT">STUDENT ENROLLMENT</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label for="ace_id">Select Ace <span class="required">*</span></label>
                                        <div class="input-group">
                                            <select  multiple="multiple" name="selected_ace"  class="form-control select2" id="selected_ace" required>
                                                <option  value="{{$all_ace_ids}}">Select All Aces</option>
                                                @foreach($aces as $this_ace)
                                                    <option  value="{{$this_ace->id}}">{{$this_ace->acronym}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="start_year">Start</label>
                                        <div class="input-group">
                                            <input type="text" name="start_year" id="start_year" class="form-control singledate" value="">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                  <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <label for="end_date">End</label>
                                        <div class="input-group">
                                            <input type="text" name="end_year" id="end_year" class="form-control singledate" value="">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                  <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary block-custom-message" style="margin-top: 25px;"
                                                onclick="calculateAggregate()">Calculate</button>
                                    </div>
                                </div>

                            </div>
                            <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('vendor-script')
{{--    <script src="{{asset('vendors/js/charts/echarts/echarts.js')}}" type="text/javascript"></script>--}}
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
            // genderDistribution(40,60)
        });
        $('.select2').select2({
            placeholder: "Select Ace",
            allowClear: true
        });

        //Script for Cumulative PDO
        function showCumulativePDO() {
            let block_ele = $(this).closest('.load-area');
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();
            let this_ace=$('#ace_id').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "{{route('analytics.getCumulativePDO')}}",
                data: {start_date: start_date,end_date: end_date,this_ace: this_ace},

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

            let topic_name = $("#topic_name").val();
            let start_year = $("#start_year").val();
            let end_year = $("#end_year").val();
            let selected_ace=$('#selected_ace').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url:"{{route('analytics.calculateAggregate')}}",
                data: {start_year: start_year,end_year: end_year,selected_ace: selected_ace,topic_name:topic_name},
                beforeSend: function () {

                },
                success: function (data) {
                    console.log(data);
                    if (topic_name == "Gender Distribution") {
                        showGenderDistribution();
                    }
                    if (topic_name == "ACE Publications in 2017") {
                        showPublications(data.research_publication, data.publication_year);
                    }
                    if (topic_name == "AGGREGATE EXTERNAL REVENUE") {
                        showAggregateExternalRevenue(data.years, data.target_external_revenue, data.actual_external_revenue);
                    }
                    if (topic_name == "AGGREGATE PROGRAMME ACCREDITATION") {
                        showAccreditationType(data.years, data.international_accreditation, data.national_accreditation);
                    }
                    if (topic_name == "Aggregate Student") {
                        graphAggregateStudents(data.years, data.total_students, data.regional_students, data.national_students, data.target_students);
                    }
                    if (topic_name === "STUDENT ENROLLMENT") {
                        getStudentEnrolment(data.years,data.total_students, data.phd_students, data.masters_students, data.prof_students);
                    }
                },
                complete: function () {

                },
                error: function () {
                    console.log("error");
                }

            });
        }




    function getStudentEnrolment(years,total_students,phd_students,masters_students,prof_students){
        Highcharts.chart('container', {
            title: {
                text: 'Combination chart'
            },
            xAxis: {
                categories: years
            },
            labels: {
                items: [{
                    html: 'Total fruit consumption',
                    style: {
                        left: '50px',
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
                data: total_students,
            }]
        });
    }



        // function showPublications(research_publication,publication_year){
        //     // Build the chart
        //     Highcharts.chart('container', {
        //         chart: {
        //             plotBackgroundColor: null,
        //             plotBorderWidth: null,
        //             plotShadow: false,
        //             type: 'pie'
        //         },
        //         title: {
        //             text: 'ACE Publications'
        //         },
        //         tooltip: {
        //             pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        //         },
        //         plotOptions: {
        //             pie: {
        //                 allowPointSelect: true,
        //                 cursor: 'pointer',
        //                 dataLabels: {
        //                     enabled: false
        //                 },
        //                 showInLegend: true
        //             }
        //         },
        //         series: [{
        //             name: 'publications',
        //             colorByPoint: true,
        //             data: [
        //                 {
        //                     name: 'publication_year',
        //                     y: [publication_year],
        //                 }
        //             ]
        //         }]
        //     });
        // }

        function graphAggregateStudents(years,total_students,regional_students,national_students,target_students) {

            Highcharts.chart('container', {
                title: {
                    text: 'AGGREGATE STUDENT'
                },
                xAxis: {
                    categories: years
                },
                labels: {
                    items: [{
                        html: years[0] + "-" +years[years.length-1],
                        style: {
                            left: '50px',
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
                    name: 'Total Students',
                    data: total_students,
                }, {
                    type: 'column',
                    name: 'regional_students',
                    data: regional_students,
                }, {
                    type: 'column',
                    name: 'national_students',
                    data: national_students,
                }, {
                    type: 'spline',
                    name: 'Target',
                    data: target_students,
                    marker: {
                        lineWidth: 2,
                        lineColor: Highcharts.getOptions().colors[3],
                        fillColor: 'white'
                    }
                }]


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
                        html: years[0] + "-" +years[years.length-1],
                        style: {
                            left: '50px',
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
                            left: '50px',
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
                error: function () {
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
                    text: "Age Distribution",
                    style: {"display": "none"}
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