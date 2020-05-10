@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">

@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/pickers/daterange/daterange.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/chat-application.css')}}">
@endpush


@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Reports</a>
                        </li>
                        <li class="breadcrumb-item active">View report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3 row pull-right">
                    @ability('webmaster|super-admin','set-report-mode')
                    <div class="col-md-4 text-right">
                        @if(!$report->editable)
                            <button type="button" class="btn btn-secondary dropdown-toggle square" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"><span id="note">In Review Mode</span></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-toggle="tooltip" data-placement="top" title="Set to Edit Mode"
                                   href="{{route('report_submission.report_edit_mode', [\Illuminate\Support\Facades\Crypt::encrypt($report->id)])}}">
                                    <i class="fa fa-unlock-alt"></i>
                                    {{__('Set to Edit Mode')}}
                                </a>
                            </div>
                        @else
                            <button type="button" class="btn btn-primary dropdown-toggle square" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"><span id="note">In Edit Mode </span></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-toggle="tooltip" data-placement="top" title="Set to Review Mode"
                                   href="" onclick="setReviewMode()">
                                    <i class="fa fa-eye"></i>
                                    {{__('Set to Review Mode')}}
                                </a>
                            </div>
                        @endif
                    </div>
                    @endability
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($project->indicators->count() > 0)
                    <div class="card mb-1">
                        <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                            Report Information
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
                                    @if (\Auth::user()->hasRole('webmaster|super-admin'))
                                        <div class="col-md-3">
                                            <h6>
                                                ACE Name
                                            </h6>
                                            <p><strong>{{$report->ace->name." (".$report->ace->acronym.")"}}</strong></p>
                                        </div>
                                        <div class="col-md-3">
                                            <h6>
                                                ACE Officer
                                            </h6>
                                            <p><strong>{{$report->user->name}}</strong></p>
                                        </div>
                                    @endif
                                    @php
                                        $full_period = \App\Http\Controllers\ReportFormController::getReportingName($reporting_period->id);
                                    @endphp
                                    <div class="col-md-3">
                                        <h6>Reporting Period </h6>
                                        <p><strong>{{$full_period}}</strong></p>
                                    </div>

                                    <div class="col-md-3">
                                        <h6>Date Submitted</h6>
                                        <p><strong>{{date('d M, Y', strtotime($report->submission_date))}}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--indicators3--}}
                    <div class="card mb-1">
                        <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                            {{$indicators->title}}
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
                                        <span class="text-secondary text-bold-500">Unit of Measure: {{$indicators->unit_measure}}</span>
                                    </small>
                                </h5>
                                @if($indicators->indicators->count() > 0)
                                    @php
                                        $sub_indicators = $indicators->indicators->where('parent_id','=',$indicators->id);
                                        $pdo_indicator_1 = config('app.indicator_3');
                                        $pdo_indicator_2 = config('app.indicator_2');
                                        $pdo_indicator_41 = config('app.indicator_41');
                                        $pdo_indicator_52 = config('app.indicator_52');
                                        $pdo_indicator_42 = config('app.indicator_42');
                                        $pdo_indicator_51 = config('app.indicator_51');
                                        //dd($sub_indicators);
                                        $counter_one = 0;
                                    @endphp
                                    @foreach($sub_indicators as $sub_indicator)
                                        @php
                                            $indicator_identifier = (string)$sub_indicator->identifier;
                                            $pdo_indicator = str_replace('-','_',\Illuminate\Support\Str::slug(strtolower($indicator_identifier)));
                                           $child_dlr = \App\Indicator::where('parent_id',$sub_indicator->id)->get();
                                        @endphp

                                        @if($child_dlr->isNotEmpty())
                                            <table class="table table-bordered table-striped">

                                                @if($sub_indicator->status == 0) @continue @endif

                                                <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                                                    <strong>{{$sub_indicator->identifier}}:</strong> {{$sub_indicator->title}}
                                                </h6>
                                                @php $counter = 0; @endphp

                                                @foreach($child_dlr as $child)


                                                    <tr>
                                                        <td>{{$child->title}} <span class="required">*</span>
                                                        </td>
                                                        <td style="width: 200px">

                                                            @if($sub_indicator->parent_id == 1)

                                                                <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}"
                                                                     style="margin-bottom: 0;">
                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$child->id}}]"
                                                                           value="{{!empty($pdo_1) ?$pdo_1[$pdo_indicator][$pdo_indicator_1[$pdo_indicator][$counter]]:0}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">

                                                                </div>

                                                            @elseif($sub_indicator->parent_id == 2)
                                                                {{--programme accreditation--}}
                                                                <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}" style="margin-bottom: 0;">
                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                           value="{{!empty($pdo_41) ?$pdo_41[$pdo_indicator][$pdo_indicator_41[$pdo_indicator][$counter]]:0}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}"> </div>

                                                            @elseif($sub_indicator->parent_id == 3)
                                                                {{--4.2 publications--}}
                                                                <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}" style="margin-bottom: 0;">
                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                           value="{{!empty($pdo_42) ?$pdo_42[$pdo_indicator][$pdo_indicator_42[$pdo_indicator][$counter]]:0}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}"> </div>

                                                            @elseif($sub_indicator->parent_id == 5)
                                                                {{--5.1 external revenue--}}

                                                                <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}" style="margin-bottom: 0;">
                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                           value="{{!empty($pdo_51) ?$pdo_51[$pdo_indicator][$pdo_indicator_51[$pdo_indicator][$counter]]:0}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}"> </div>


                                                            @elseif($sub_indicator->parent_id == 6)

                                                                {{--internships 5.2--}}
                                                                <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}"
                                                                     style="margin-bottom: 0;">
                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$child->id}}]"
                                                                           value="{{!empty($pdo_52) ?$pdo_52[$pdo_indicator][$pdo_indicator_52[$pdo_indicator][$counter]]:0}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                                </div>

                                                            @elseif($sub_indicator->parent_id == 14)
                                                                {{--accreditation 7.3 --}}
                                                                <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}"
                                                                     style="margin-bottom: 0;">
                                                                    <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$child->id}}]"
                                                                           value="{{!empty($pdo_2) ?$pdo_2[$pdo_indicator][$pdo_indicator_2[$pdo_indicator][$counter]]:0}}"
                                                                           class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                                </div>



                                                            @endif

                                                        </td>
                                                    </tr>
                                                    @php
                                                        $counter += 1;
                                                    @endphp
                                                @endforeach


                                            </table>
                                        @else
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <td>{{$sub_indicator->title}} <span class="required">*</span>
                                                    </td>
                                                    <td style="width: 200px">
                                                        @if($sub_indicator->parent_id == 1)
                                                            {{--programme accreditation--}}
                                                            <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}" style="margin-bottom: 0;">
                                                                <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                       value="{{!empty($pdo_1) ?$pdo_1[$pdo_indicator]:0}}"
                                                                       class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                            </div>
                                                        @elseif($sub_indicator->parent_id == 2)
                                                            {{--programme accreditation--}}
                                                            <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}" style="margin-bottom: 0;">
                                                                <input type="number" readonly min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                       value="{{$pdo_41['pdo_indicator_41'][$pdo_indicator_41['pdo_indicator_41'][$counter_one]]}}"
                                                                       class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                            </div>
                                                        @elseif($sub_indicator->aau_only || $sub_indicator->parent_id == 84)
                                                            <div class="form-group{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}" style="margin-bottom: 0;">
                                                                <input type="number" min="0" id="indicator_{{$sub_indicator->id}}" name="indicators[{{$sub_indicator->id}}]"
                                                                       value="{{(array_key_exists($sub_indicator->id,$values))?$values[$sub_indicator->id]:''}}"
                                                                       class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}">
                                                            </div>
                                                        @endif
                                                    </td>
                                            </table>
                                        @endif

                                        @php
                                            $counter_one += 1;
                                        @endphp



                                    @endforeach

                                @endif


                            </div>
                        </div>
                    </div>

                @else
                    <h2 class="center">No Indicators available</h2>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-1">
                    <h6 class="card-header p-1 card-head-inverse bg-grey-blue" style="border-radius:0">
                        <strong>Challenges faced / Additional Comments</strong>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                            </ul>
                        </div>
                    </h6>
                    <div class="card-content collapse">
                        <div class="card-body">
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Comment" name="report_comment">@isset($comment){{$comment->comments}}@endisset</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('vendor-script')


    <script type="text/javascript" src="{{ asset("js/scripts/customizer.js") }}"></script>
    <script src="{{ asset("js/scripts/pages/chat-application.js")}}" type="text/javascript"></script>
    <script type="text/javascript" >
        function setReviewMode() {
            // console.log(id);
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            var path = "{{route('report_submission.report_review_mode',[$report->id])}}";
            $.ajax({
                url: path,
                type: 'GET',
                // data: {id:id},
                beforeSend: function(){
                    $('#reviewMode > i').css('display','inline-block')
                },
                success: function(data){
                    if(data.status == 0){
                        $('#reviewMode').removeClass('btn-primary')
                        $('#editable').css('display','none')
                    }
                    else{
                        $('#reviewMode').removeClass('btn-secondary')
                        $('#editable').css('display','inline-block')
                    }
                    $('#reviewMode').addClass(data.btnclass)
                    $('#note').text(data.note)
                },
                complete:function(){
                    $('#reviewMode > i').css('display','none')
                    // $.getScript(check1);
                    // $.getScript(check2);
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }
        $('.customizer-toggle').on('click',function(){
            $('.customizer').toggleClass('open');
        });
    </script>






@endpush
@push('end-script')

@endpush