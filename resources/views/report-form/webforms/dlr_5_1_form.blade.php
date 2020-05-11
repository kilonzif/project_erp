@extends('report-form.webforms.webform')
@section('web-form')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header p-1 card-head-inverse bg-teal">
                {{$indicator_info->identifier}} : {{$indicator_info->title}}
            </h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div id="form-card">
                            <form action="{{route('report_submission.save_webform',[$indicator_info->id])}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="report_id" value="{{$d_report_id}}">
                                    <input type="hidden" name="indicator_id" value="{{$indicator_info->id}}">
                                    <div class="col-md-4">
                                        <fieldset class="form-group{{ $errors->has('amountindollars') ? ' form-control-warning' : '' }}">
                                            <label for="amountindollars">{{$lang['Amount (USD)']}}<span class="required">*</span></label>
                                            <input type="number" class="form-control text-right"  min="0" step="0.01"
                                                   required name="amountindollars" id="amountindollars">
                                            @if ($errors->has('amountindollars'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('amountindollars') }}</small>
                                                </p>
                                            @endif
                                        </fieldset>

                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="form-group{{ $errors->has('originalamount') ? ' form-control-warning' : '' }}">
                                            <label for="originalamount">{{$lang['Original Amount']}}<span class="required">*</span></label>
                                            <input type="number" class="form-control text-right"  min="0" step="0.01"
                                                   required name="originalamount" id="originalamount">
                                            @if ($errors->has('originalamount'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('originalamount') }}</small>
                                                </p>
                                            @endif
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="form-group{{ $errors->has('currency') ? ' form-control-warning' : '' }}">
                                            <label for="currency">{{$lang['Original Amount Currency']}}<span class="required">*</span></label>
                                            <select class="form-control" required name="currency" id="currency">
                                                <option value="">{{$lang['Select One']}}</option>
                                                <option value="USD">US Dollar</option>
                                                <option value="EURO">Euro</option>
                                                <option value="SDR">SDR</option>
                                            </select>
                                            @if ($errors->has('currency'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('currency') }}</small>
                                                </p>
                                            @endif
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="form-group{{ $errors->has('source') ? ' form-control-warning' : '' }}">
                                            <label for="source">{{$lang['Source']}}<span class="required">*</span></label>
                                            <input type="text" class="form-control" id="source" min="0" required name="source">
                                            @if ($errors->has('source'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('source') }}</small>
                                                </p>
                                            @endif
                                        </fieldset>

                                    </div>
                                    <div class="col-md-4">
                                        <fieldset>
                                            <label for="datereceived">{{$lang['Date of Receipt']}}<span class="required">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="datereceived" class="form-control form-control datepicker"
                                                       data-date-format="D-M-YYYY" id="datereceived">
                                                {{--<span class="input-group-append">--}}
                                                    {{--<span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>--}}
                                                {{--</span>--}}
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="form-group{{ $errors->has('region') ? ' form-control-warning' : '' }}">
                                            <label for="region">{{$lang['Region']}}<span class="required">*</span></label>
                                            <input type="text" class="form-control" required name="region" id="region">
                                            @if ($errors->has('region'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('region') }}</small>
                                                </p>
                                            @endif
                                        </fieldset>

                                    </div>
                                    <div class="col-md-12">
                                        <fieldset class="form-group{{ $errors->has('bankdetails') ? ' form-control-warning' : '' }}">
                                            <label for="bankdetails">{{$lang['Account Details']}}<span class="required">*</span></label>
                                            <input type="text" class="form-control"  min="0" required name="bankdetails"
                                            id="bankdetails">
                                            @if ($errors->has('bankdetails'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('bankdetails') }}</small>
                                                </p>
                                            @endif
                                        </fieldset>

                                    </div>
                                    <div class="col-md-12">
                                        <fieldset class="form-group{{ $errors->has('fundingreason') ? ' form-control-warning' : '' }}">
                                            <label for="fundingreason">{{$lang['Purpose of Funds']}}<span class="required">*</span></label>
                                            <input type="text" class="form-control"  required name="fundingreason" id="fundingreason">
                                            @if ($errors->has('fundingreason'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('fundingreason') }}</small>
                                                </p>
                                            @endif
                                        </fieldset>

                                    </div>
                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-secondary square" style="margin-top: 20px">
                                            <i class="fa fa-save"></i> {{$lang['Save']}} </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
                <h6 class="card-header p-1 card-head-inverse bg-primary">
                    Saved Records
                </h6>
                <div class="card-content">
                    <div class="card-body table-responsive">
                        <table class="table table-scrollable table-striped table-bordered">
                            <tr>
                                <th style="min-width: 30px">#</th>
                                <th style="min-width: 120px">{{$lang['Amount (USD)']}}</th>
                                <th style="min-width: 120px">{{$lang['Original Amount']}}</th>
                                <th style="min-width: 120px">{{$lang['Original Amount Currency']}}</th>
                                <th style="min-width: 250px">{{$lang['Source']}}</th>
                                <th style="min-width: 120px">{{$lang['Date of Receipt']}}</th>
                                <th style="min-width: 250px">{{$lang['Account Details']}}</th>
                                <th style="min-width: 120px">{{$lang['Region']}}</th>
                                <th style="min-width: 250px">{{$lang['Purpose of Funds']}}</th>
                                <th style="min-width: 180px">{{$lang['Action']}}</th>
                            </tr>
                            @php $counter=0; @endphp
                            @foreach($data as $datum)
                                @php $counter++; @endphp
                                <tr>
                                    <td>{{$counter}}</td>
                                    <td>{{number_format($datum->amountindollars,2)}}</td>
                                    <td>{{number_format($datum->originalamount,2)}}</td>
                                    <td>{{$datum->currency}}</td>
                                    <td>{{$datum->source}}</td>
                                    <td>{{date("d/m/Y", strtotime($datum->datereceived))}}</td>
                                    <td>{{$datum->bankdetails}}</td>
                                    <td>{{$datum->region}}</td>
                                    <td>{{$datum->fundingreason}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                        <a href="#form-card" onclick="editRecord('{{$indicator_info->id}}','{{$datum->id}}')" class="btn btn-s btn-secondary">
                                            {{__('Edit')}}</a>
                                        <a href="{{route('report_submission.web_form_remove_record',[\Illuminate\Support\Facades\Crypt::encrypt($indicator_info->id),$datum->id])}}"
                                           class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this record?');"
                                           title="Delete Record"><i class="ft-trash-2"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>

@endsection