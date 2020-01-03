@extends('layouts.app')
@push('vendor-styles')
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">--}}

    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
@endpush
@push('other-styles')
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
@endpush
@section('content')
    {{--@php dd(old('indicator.3')) @endphp--}}
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">General Reports</a>

                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card mb-1">
                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                        Generated Report
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </h6>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="submission_period">Range (Start Date)</label>
                                        <input type="text" readonly value="{{date('M d, Y',strtotime($start))}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="submission_period">Range (End Date)</label>
                                    <input type="text" readonly value="{{date('M d, Y',strtotime($end))}}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $indicators = $project->indicators->where('parent_id','=',0)->where('status','=',1);
                @endphp
                @foreach($indicators as $indicator)
                    <div class="card mb-1">
                        <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                            {{--<h6 class="card-title"></h6>--}}
                            <strong>{{"Indicator ".$indicator->identifier}}:</strong> {{$indicator->title}}
                            {{--<br>--}}
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                </ul>
                            </div>
                        </h6>
                        <div class="card-content collapse show">
                            <div class="card-body table-responsive">
                                <h5>
                                    <small>
                                        <span class="text-secondary text-bold-500">Unit of Measure:</span>
                                        {{$indicator->unit_measure}}
                                    </small>
                                </h5>
                                <table class="table table-bordered table-striped">
                                    @if($indicator->indicators->count() > 0)
                                        @php
                                            $sub_indicators = $indicator->indicators->where('status','=',1);
                                        @endphp
                                        @foreach($sub_indicators as $sub_indicator)
                                            @php
                                                try{
                                                    $value = $report_values->where('indicator_id','=',$sub_indicator->id)->pluck('ind_values');
                                                }
                                                catch(Exception $exception){
                                                    $value[0] = "N/A";
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{$sub_indicator->title}}</label>
                                                    @if($sub_indicator->unit_measure)
                                                        <br><small><strong>Unit of Measure: </strong>{{$sub_indicator->unit_measure->title}}</small>
                                                    @endif
                                                </td>
                                                <td style="width: 100px;text-align: right;">
                                                    <strong>{{$value[0]}}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @php
                                            try{
                                                $value = $report_values->where('indicator_id','=',$indicator->id)->pluck('ind_values');
                                            }
                                            catch(Exception $exception){
                                                $value[0] = "N/A";
                                            }
                                        @endphp
                                        <tr>
                                            <td colspan="2" style="text-align: right;">
                                                <strong>{{$value[0]}}</strong>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('vendor-script')
{{--    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>--}}
@endpush
@push('end-script')

@endpush