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
                        $masters = config('app.filters_fr.masters_text');
                        $bachelors = config('app.filters_fr.bachelors_text');
                        $phd = config('app.filters_fr.phd_text');
                            $sub_indicator = \App\Indicator::query()->where('identifier','like','%'.'PDO Indicator 2')->first();
                    @endphp

                    <h4 style="padding:10px">{{$sub_indicator->identifier}} : {{$sub_indicator->title}}</h4>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="form-card">
                                    <form action="{{route('report_submission.save_webform',[$indicators->id])}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="report_id" value="{{$d_report_id}}">
                                            <input type="hidden" name="indicator_id" value="{{$indicators->id}}">
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('programmetitle') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Titre du programme<span class="required">*</span></label>
                                                    <select name="programmetitle" id="programmetitle" required  class="form-control">
                                                        <option value="">Select</option>
                                                        @isset($ace_programmes)
                                                            @foreach($ace_programmes as $key=>$ace_programme)
                                                                @if($ace_programme != "")
                                                                    <option {{(old('programmetitle')==$ace_programme) ? "selected" :" "}} value="{{$ace_programme}}">{{$ace_programme}}</option>
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
                                                <fieldset class="form-group{{ $errors->has('level') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Niveau<span class="required">*</span></label>
                                                    <select name="level" required class="form-control" id="level">
                                                        <option value="">sélectionnez</option>
                                                        <option {{(old('level')==$masters) ? "selected" :" "}}  value="{{$masters}}">{{$masters}}</option>
                                                        <option {{(old('level')==$phd) ? "selected" :" "}}  value="{{$phd}}">{{$phd}}</option>
                                                        <option {{(old('level')==$bachelors) ? "selected" :" "}}  value="{{$bachelors}}">{{$bachelors}}</option>
{{--                                                        <option {{(old('level')=='professional_course') ? "selected" :" "}}  value="professional_course">Programme de courte durée</option>--}}
                                                    </select>
                                                    @if ($errors->has('level'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('level') }}</small>
                                                        </p>
                                                    @endif
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('typeofaccreditation') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Type d'accreditation <span class="required">*</span></label>
                                                    <select name="typeofaccreditation" required class="form-control" id="language">
                                                        <option value="">sélectionnez</option>
                                                        <option {{(old('typeofaccreditation')=='National') ? "selected" :" "}}  value="National">Nationale</option>
                                                        <option {{(old('typeofaccreditation')=='Regional') ? "selected" :" "}}  value="Regional">Régionale</option>
                                                        <option {{(old('typeofaccreditation')=='International') ? "selected" :" "}} value="International">Internationale</option>
                                                        <option {{(old('typeofaccreditation')=='Gap Assessment"') ? "selected" :" "}}  value="Gap Assessment">
                                                            Évaluation des lacunes</option>
                                                        <option {{(old('typeofaccreditation')=='Self-Evaluation') ? "selected" :" "}} value="Self-Evaluation">
                                                            Auto-évaluation</option>
                                                    </select>
                                                    @if ($errors->has('typeofaccreditation'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('typeofaccreditation') }}</small>
                                                        </p>
                                                    @endif

                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('accreditationreference') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Référence de l'accréditation</label>
                                                    <input type="text" name="accreditationreference" class="form-control"
                                                           value="{{ (old('accreditationreference')) ? old('accreditationreference') : '' }}">
                                                    @if ($errors->has('accreditationreference'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('accreditationreference') }}</small>
                                                        </p>
                                                    @endif
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('accreditationagency') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Agence d'accréditation <span class="required">*</span></label>
                                                    <input type="text" class="form-control" name="accreditationagency"
                                                           value="{{ (old('accreditationagency')) ? old('accreditationagency') : '' }}">
                                                    @if ($errors->has('accreditationagency'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('accreditationagency') }}</small>
                                                        </p>
                                                    @endif
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('agencyname') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Personne contact de l'agence<span class="required">*</span> </label>
                                                    <input class="form-control" required type="text" name="agencyname"
                                                           value="{{ (old('agencyname')) ? old('agencyname') : '' }}">
                                                    @if ($errors->has('agencyname'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('agencyname') }}</small>
                                                        </p>
                                                    @endif

                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('agencyemail') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Couriel du personne contact <span class="required">*</span></label>
                                                    <input type="email" class="form-control" required name="agencyemail"
                                                           value="{{ (old('agencyemail')) ? old('agencyemail') : '' }}">
                                                    @if ($errors->has('agencyemail'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('agencyemail') }}</small>
                                                        </p>
                                                    @endif
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('agencycontact') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Numéro de téléphone du personne contact <span class="required">*</span></label>
                                                    <input type="text" min="10" name="agencycontact" required class="form-control"
                                                           value="{{ (old('agencycontact')) ? old('agencycontact') : '' }}">
                                                    @if ($errors->has('agencycontact'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('agencycontact') }}</small>
                                                        </p>
                                                    @endif
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('dateofaccreditation') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Date d'accréditation <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" required name="dateofaccreditation" class="form-control form-control datepicker"
                                                               data-date-format="D-M-YYYY"
                                                               value="{{ (old('dateofaccreditation')) ? old('dateofaccreditation') : '' }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('dateofaccreditation'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('dateofaccreditation') }}</small>
                                                        </p>
                                                    @endif
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('exp_accreditationdate') ? ' form-control-warning' : '' }}">
                                                    <label for="basicInputFile">Date d'expiration de l'accréditation<span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" required name="exp_accreditationdate" class="form-control form-control datepicker"
                                                               data-date-format="D-M-YYYY"
                                                               value="{{ (old('exp_accreditationdate')) ? old('exp_accreditationdate') : '' }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('exp_accreditationdate'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('exp_accreditationdate') }}</small>
                                                        </p>
                                                    @endif
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group{{ $errors->has('newly_accredited_programme') ? ' form-control-warning' : '' }}">
                                                    <label for="newly_accredited_programme">Programme nouvellement accrédité?<span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <select name="newly_accredited_programme" class="form-control" required id="newly_accredited_programme">
                                                            <option value="">Sélectionner</option>
                                                            <option {{(old('newly_accredited_programme')=='Non') ? "selected" :" "}} value="Non">Non</option>
                                                            <option {{(old('newly_accredited_programme')=='Oui') ? "selected" :" "}} value="Oui">Oui</option>
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('newly_accredited_programme'))
                                                        <p class="text-right mb-0">
                                                            <small class="warning text-muted">{{ $errors->first('newly_accredited_programme') }}</small>
                                                        </p>
                                                    @endif
                                                </fieldset>
                                            </div>

                                            <div class="form-group col-12">
                                                <button type="submit" class="btn btn-secondary square" style="margin-top: 20px"><i class="fa fa-save"></i> Sauver</button>
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

                                <table class="table table-scrollable table-striped table-bordered">
                                    <tr>
                                        <th>Titre du programme</th>
                                        <th>Niveau</th>
                                        <th>Type d'accreditation</th>
                                        <th>Référence de l'accréditation</th>
                                        <th>Agence d'accréditation</th>
                                        <th>Personne contact de l'agence</th>
                                        <th>Couriel du personne contact</th>
                                        <th>Numéro de téléphone du personne contact</th>
                                        <th>Date d'accréditation</th>
                                        <th>Date d'expiration de l'accréditation</th>
                                        <th>Nouvellement accrédité</th>
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
                                                @endisset</td>
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