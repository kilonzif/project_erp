@extends('layouts.app')
@push('vendor-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
@endpush
@push('other-styles')
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
                    <div class="card-header p-1 card-head-inverse bg-teal">
                        <h6>{{$ace->name}} - ({{$ace->acronym}})</h6>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <div class="col-md-7">
                                <h2>{{$indicators->title}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{route('report_submission.save_webform',[$indicators->id])}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" name="report_id" value="{{$d_report_id}}">
                                        <input type="hidden" name="indicator_id" value="{{$indicators->id}}">
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label for="basicInputFile">PROGRAMME TITLE <span class="required">*</span></label>
                                                <input type="text" class="form-control"  required name="programmetitle">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label for="basicInputFile">LEVEL<span class="required">*</span></label>
                                                <select name="level" required class="form-control" id="language">
                                                    <option value="">select LEVEL</option>
                                                    <option value="MASTERS">MASTERS</option>
                                                    <option value="PHD">PhD</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label for="basicInputFile">TYPE OF ACCREDITATION <span class="required">*</span></label>
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
                                                <label for="basicInputFile">ACCREDITATION REFERENCE <span class="required">*</span></label>
                                                <input type="text" name="accreditationreference"  required class="form-control">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label for="basicInputFile">ACCREDITATION AGENCY <span class="required">*</span></label>
                                                <input type="text" class="form-control" name="accreditationagency">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label for="basicInputFile">AGENCY CONTACT NAME<span class="required">*</span> </label>
                                                <input class="form-control" required type="text" name="agencyname">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                                <label for="basicInputFile">AGENCY CONTACT EMAIL <span class="required">*</span></label>
                                                <input type="email" class="form-control" required name="agencyemail">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                                <label for="basicInputFile">AGENCY CONTACT PHONE NUMBER <span class="required">*</span></label>
                                                <input type="text" min="10" name="agencycontact" required class="form-control">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                                <label for="basicInputFile">DATE OF ACCREDITATION <span class="required">*</span></label>
                                                <input type="date" class="form-control" required name="dateofaccreditation">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                                <label for="basicInputFile">EXPIRY DATE OF ACCREDITATION <span class="required">*</span></label>
                                                <input type="date" name="exp_accreditationdate"  required class="form-control">
                                            </fieldset>
                                        </div>

                                        <div class="col-md-6 offset-5">
                                            <button type="submit" class="btn btn-secondary square"><i class="fa fa-save">   SAVE </i>     RECORDS</button>
                                        </div>

                                    </div>

                                </form>
                            </div>
                        </div>



                    </div>

                </div>

                <div class="card">
                    <div class="card-header">
                        <h2>Program Accreditation</h2>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="col-md-12">

                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th>Program Title</th>
                                        <th>Level</th>
                                        <th>Type</th>
                                        <th>Reference</th>
                                        <th>Agency</th>
                                        <th>Contact Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>PAccreditation Date</th>
                                        <th>Accreditation Expiry Date</th>
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
                                        <td>{{$d->dateofaccreditation}}</td>
                                        <td>{{$d->exp_accreditationdate}}</td>
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
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>
@endpush