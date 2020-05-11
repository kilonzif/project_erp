@extends('layouts.app')
@push('vendor-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/datetime/bootstrap-datetimepicker.css') }}">

    <style>
        table{
            border-collapse: collapse;
            width: 300px;
            overflow-x: scroll;
            display: block;
            font-size: 11pt;
        }
    </style>
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
                        <li class="breadcrumb-item active">Web-form  Upload for DLR
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="mb-1 row ">
            <div class="col-lg-12 text-right">
                <a class="btn btn-dark square" href="{{route('report_submission.edit',[\Illuminate\Support\Facades\Crypt::encrypt($d_report_id)])}}">
                    <i class="ft-arrow-right mr-md-2"></i>Preview and Submit Report
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <h5 class="card-header p-1 card-head-inverse bg-teal">
                        {{$ace->name}} ({{$ace->acronym}}) - {{$indicators->title}}
                    </h5>

                    @php
                       $sub_indicator = \App\Indicator::query()->where('identifier','like','%'.'PDO Indicator 2')->first();
                    @endphp

                        <h4 style="padding:10px">{{$sub_indicator->identifier}} : {{$sub_indicator->title}}</h4>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12" >
                                <div id="form-card">
                                    <form action="{{route('report_submission.save_webform',[$indicators->id])}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="report_id" value="{{$d_report_id}}">
                                            <input type="hidden" name="indicator_id" value="{{$indicators->id}}">
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('programmetitle') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Programme title <span class="required">*</span></label>
                                                    <select name="programmetitle" id="programmetitle" required  class="form-control">
                                                        <option value="">Select</option>
                                                        @isset($ace_programmes)
                                                            @foreach($ace_programmes as $key=>$ace_programme)
                                                                @if($ace_programme != "")
                                                                    <option value="{{$ace_programme}}">{{$ace_programme}}</option>
                                                                @endif
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                    @if ($errors->has('programmetitle'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('programmetitle') }}</small>
                                                        </p>
                                                    @endif
                                                </fieldset>

                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">Level<span class="required">*</span></label>
                                                    <select name="level" required class="form-control" id="language">
                                                        <option value="">select one</option>
                                                        <option value="MASTERS">Masters</option>
                                                        <option value="PHD">PhD</option>
                                                        <option value="bachelors">Bachelors</option>
                                                        <option value="professional_course">Professional Short Course</option>

                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">Type of Accreditation <span class="required">*</span></label>
                                                    <select name="typeofaccreditation" required class="form-control" id="language">
                                                        <option value="">select one</option>
                                                        <option value="National">National</option>
                                                        <option value="Regional">Regional</option>
                                                        <option value="International">International</option>
                                                        <option value="Gap Assessment">Gap Assessment</option>
                                                        <option value="Self-Evaluation">Self-Evaluation</option>
                                                    </select>

                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">Accreditation Reference</label>
                                                    <input type="text" name="accreditationreference" class="form-control">
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">Accreditation Agency <span class="required">*</span></label>
                                                    <input type="text" class="form-control" name="accreditationagency">
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">Agency Contact Name<span class="required">*</span> </label>
                                                    <input class="form-control" required type="text" name="agencyname">
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">Agency Contact Email <span class="required">*</span></label>
                                                    <input type="email" class="form-control" required name="agencyemail">
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">Agency Contact Phone Number <span class="required">*</span></label>
                                                    <input type="text" min="10" name="agencycontact" required class="form-control">
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <label for="basicInputFile">Date of Accreditation <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" required name="dateofaccreditation" class="form-control form-control datepicker"
                                                               data-date-format="D-M-YYYY" >
                                                        {{--<div class="input-group-append">--}}
                                                            {{--<span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>--}}
                                                        {{--</div>--}}
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <label for="basicInputFile">Expiry Date of Accreditation<span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" required name="exp_accreditationdate" class="form-control form-control datepicker"
                                                               data-date-format="D-M-YYYY">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <label for="newly_accredited_programme">Newly Accredited Programme?<span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <select name="newly_accredited_programme" class="form-control" required id="newly_accredited_programme">
                                                            <option value="">Select</option>
                                                            <option value="No">No</option>
                                                            <option value="Yes">Yes</option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="form-group col-12">
                                                <button type="submit" class="btn btn-secondary square" style="margin-top: 20px"><i class="fa fa-save"></i> Save</button>
                                            </div>

                                        </div>

                                    </form>

                                </div>
                                </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h5 class="card-header p-1 card-head-inverse bg-primary">
                        Program Accreditation
                    </h5>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="col-md-12 table-responsive">

                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th style="width: 200px">Program Title</th>
                                        <th>Level</th>
                                        <th>Type</th>
                                        <th>Reference</th>
                                        <th style="width: 200px">Agency</th>
                                        <th style="width: 150px">Contact Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Accreditation Date</th>
                                        <th>Accreditation Expiry Date</th>
                                        <th>Newly Accredited</th>
                                        <th style="min-width: 180px">Action</th>
                                    </tr>
                                    @foreach($data as $key=>$d)
                                        @php
                                            $d=(object)$d;
                                        @endphp

                                        <tr>
                                            <td>{{$d->programmetitle}}</td>
                                            <td>{{$d->level}}</td>
                                            <td>{{$d->typeofaccreditation}}</td>
                                            <td>{{$d->accreditationreference}}</td>
                                            <td>{{$d->accreditationagency}}</td>
                                            <td>{{$d->agencyname}}</td>
                                            <td>{{$d->agencyemail}}</td>
                                            <td>{{$d->agencycontact}}</td>
                                            <td>{{date("d/m/Y", strtotime($d->dateofaccreditation))}}</td>
                                            <td>{{date("d/m/Y", strtotime($d->exp_accreditationdate))}}</td>
                                            <td>
                                                @isset($d->newly_accredited_programme)
                                                {{$d->newly_accredited_programme}}
                                                @endisset
                                            </td>
                                            <td>
                                                <a href="#form-card" onclick="editRecord('{{$indicators->id}}','{{$d->_id}}')" class="btn btn-s btn-secondary">
                                                    {{__('Edit')}}</a>
                                                <a href="{{route('report_submission.web_form_remove_record',[\Illuminate\Support\Facades\Crypt::encrypt($indicators->id),$d->_id])}}"
                                                   class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this record?');"
                                                   title="Delete Record"><i class="ft-trash-2"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>






@endsection

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
{{--<script src="../../../app-assets/js/scripts/forms/input-groups.min.js"></script>--}}



@push('vendor-script')

    <script src="{{ asset('vendors/js/pickers/dateTime/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

@endpush


{{--@push('end-script')--}}

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>--}}

<script>


    $(function () {
        $('.datepicker').datetimepicker();
    });

        function editRecord(indicator,record){
            var path = "{{route('report_submission.web_form_edit_record')}}";
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                url: path,
                type: 'GET',
                data: {indicator_id:indicator,record_id:record},
                beforeSend: function(){
                    $('#form-card').block({
                        message: '<div class="ft-loader icon-spin font-large-1"></div>',
                        overlayCSS: {
                            backgroundColor: '#ccc',
                            opacity: 0.8,
                            cursor: 'wait'
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: 'transparent'
                        }
                    });;
                },
                success: function(data){
                    $('#form-card').empty();
                    $('#form-card').html(data.theView);
                    // console.log(data)
                },
                complete:function(){
                    $('#form-card').unblock();
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });

        }



    </script>

