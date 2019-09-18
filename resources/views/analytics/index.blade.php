@extends('layouts.app')
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
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/charts/echarts/echarts.js')}}" type="text/javascript"></script>
@endpush()
@push('end-script')
    <script>
        $("document").ready(function () {
            showGenderDistribution();
            // genderDistribution(40,60)
        });

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