@extends('layouts.app')
@push('vendor-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
@endpush
@push('other-styles')
    <style>
        table{
            border-collapse: collapse;
            width: 300px;
            overflow-x: scroll;
            display: block;
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
                                            <fieldset class="form-group{{ $errors->has('amountindollars') ? ' form-control-warning' : '' }}">
                                                <label for="basicInputFile">Amount (USD)<span class="required">*</span></label>
                                                <input type="number" class="form-control"  min="0" required name="amountindollars">
                                                @if ($errors->has('amountindollars'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('amountindollars') }}</small>
                                                    </p>
                                                @endif
                                            </fieldset>

                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group{{ $errors->has('originalamount') ? ' form-control-warning' : '' }}">
                                                <label for="basicInputFile">Original Amount<span class="required">*</span></label>
                                                <input type="number" class="form-control"  min="0" required name="originalamount">
                                                @if ($errors->has('originalamount'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('originalamount') }}</small>
                                                    </p>
                                                @endif
                                            </fieldset>

                                        </div>

                                        <div class="col-md-4">
                                            <fieldset class="form-group{{ $errors->has('source') ? ' form-control-warning' : '' }}">
                                                <label for="basicInputFile">Source<span class="required">*</span></label>
                                                <input type="text" class="form-control"  min="0" required name="source">
                                                @if ($errors->has('source'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('source') }}</small>
                                                    </p>
                                                @endif
                                            </fieldset>

                                        </div>

                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label for="basicInputFile">Date of Receipt (dd/mm/yyyy)<span class="required">*</span></label>
                                                <input type="date" class="form-control" required name="datereceived">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group{{ $errors->has('bankdetails') ? ' form-control-warning' : '' }}">
                                                <label for="basicInputFile">Account Details<span class="required">*</span></label>
                                                <input type="text" class="form-control"  min="0" required name="bankdetails">
                                                @if ($errors->has('bankdetails'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('bankdetails') }}</small>
                                                    </p>
                                                @endif
                                            </fieldset>

                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group{{ $errors->has('region') ? ' form-control-warning' : '' }}">
                                                <label for="basicInputFile">Region<span class="required">*</span></label>
                                                <input type="text" class="form-control" required name="region">
                                                @if ($errors->has('region'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('region') }}</small>
                                                    </p>
                                                @endif
                                            </fieldset>

                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group{{ $errors->has('fundingreason') ? ' form-control-warning' : '' }}">
                                                <label for="basicInputFile">Purpose of Funds<span class="required">*</span></label>
                                                <input type="text" class="form-control"  required name="fundingreason">
                                                @if ($errors->has('fundingreason'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('fundingreason') }}</small>
                                                    </p>
                                                @endif
                                            </fieldset>

                                        </div>
                                        <div class="form-group col-12">
                                            <button type="submit" class="btn btn-secondary square"><i class="fa fa-save">   SAVE </i>     RECORDS</button>
                                        </div>

                                    </div>

                                </form>
                            </div>
                        </div>



                    </div>

                </div>

                <div class="card">
                    <div class="card-header p-1 card-head-inverse bg-primary">
                        <h2>External Revenue</h2>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="col-md-12 table-responsive">

                                <table class="table table-scrollable table-striped table-bordered">
                                    <tr>
                                        <th>Amount (USD)</th>
                                        <th>Original Amount</th>
                                        <th>Source</th>
                                        <th>Date of Receipt</th>
                                        <th>Bank Details</th>
                                        <th>Region</th>
                                        <th>Purpose of Funds</th>
                                        <th style="min-width: 180px">Action</th>
                                    </tr>
                                    @foreach($data as $key=>$d)
                                        @php
                                            $d=(object)$d;
                                        @endphp
                                         <tr>
                                            <td>{{$d->amountindollars}}</td>
                                            <td>{{$d->originalamount}}</td>
                                            <td>{{$d->source}}</td>
                                            <td>{{date("d/m/Y", strtotime($d->datereceived))}}</td>
                                            <td>{{$d->bankdetails}}</td>
                                            <td>{{$d->region}}</td>
                                            <td>{{$d->fundingreason}}</td>
                                             <td>
                                                 {{--<a href="#form-card" onclick="editRecord('{{$d->_id}}')" class="btn btn-s btn-secondary">--}}
                                                     {{--{{__('Edit')}}</a>--}}
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
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>
@endpush