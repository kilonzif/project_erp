@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
@endpush
@push('other-styles')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
@endpush
@section('content')
    {{--<div class="content-header row">--}}
    {{--</div>--}}
    <div class="content-body">
        <div class="row">
            <div class="col-xl-6 col-lg-12">
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
            <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gender Distribution</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                {{--<li><a data-action="collapse"><i class="ft-minus"></i></a></li>--}}
                                {{--<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>--}}
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                {{--<li><a data-action="close"><i class="ft-x"></i></a></li>--}}
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
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

        //Script for Cumulative PDO
        function showCumulativePDO() {
            let block_ele = $(this).closest('.load-area');
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "{{route('analytics.getCumulativePDO')}}",
                data: {start_date: start_date,end_date: end_date},
                beforeSend: function () {

                    // $(block_ele).block({
                    //     message: '<span class="semibold"> Please wait...</span>',
                    //     overlayCSS: {
                    //         backgroundColor: '#fff',
                    //         opacity: 0.8,
                    //         cursor: 'wait'
                    //     },
                    //     css: {
                    //         border: 0,
                    //         padding: 0,
                    //         backgroundColor: 'transparent'
                    //     }
                    // });
                },
                success: function (data) {
                    $("#showPDOTable").html(data.the_view);
                },
                complete: function () {
                    $(block_ele).unblock();
                },
                error: function (data) {
                    console.log(data)
                }
            })
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
                    console.log(data)
                    genderDistribution(data.male,data.female)
                },
                complete: function () {

                },
                error: function () {
                    console.log(data)
                }
            })
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