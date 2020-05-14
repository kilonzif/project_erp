<form action="{{route('report_submission.web_form_update_record',
[\Illuminate\Support\Facades\Crypt::encrypt($this_indicator->id),$record_id])}}"
      method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <input type="hidden" name="report_id" value="{{$the_record->report_id}}">
        <input type="hidden" name="indicator_id" value="{{$this_indicator->id}}">
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('amountindollars') ? ' form-control-warning' : '' }}">
                <label for="amountindollars">{{$lang['Amount (USD)']}}<span class="required">*</span></label>
                <input type="number" class="form-control text-right"  min="0" step="0.01"
                       required name="amountindollars" id="amountindollars"
                       value="{{ (old('amountindollars')) ? old('amountindollars') : $the_record->amountindollars }}">
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
                       required name="originalamount" id="originalamount"
                       value="{{ (old('originalamount')) ? old('originalamount') : $the_record->originalamount }}">
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
                    @foreach($currency_list as $currency)
                        <option {{($the_record->currency == $currency->id)  ? "selected":""}} value="{{$currency->id}}">{{$currency->value}}</option>
                    @endforeach
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
                <input type="text" class="form-control" id="source" min="0" required name="source"
                       value="{{ (old('source')) ? old('source') : $the_record->source }}">
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
                           data-date-format="D-M-YYYY" id="datereceived"
                           value="{{ (old('datereceived')) ? old('datereceived') : $the_record->datereceived }}">
                </div>
            </fieldset>
        </div>

        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('region') ? ' form-control-warning' : '' }}">
                <label for="region">{{$lang['Region']}}<span class="required">*</span></label>
                <select name="region" id="region" class="form-control" required>
                    <option value="">{{$lang['Select One']}}</option>
                    <option value="{{$lang['National']}}" {{ ($the_record->region == $lang['National']) ? "selected" : "" }}>
                        {{$lang['National']}}
                    </option>
                    <option value="{{$lang['Regional']}}" {{ ($the_record->region == $lang['Regional']) ? "selected" : "" }}>
                        {{$lang['Regional']}}
                    </option>
                </select>
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
                       id="bankdetails" value="{{ (old('bankdetails')) ? old('bankdetails') : $the_record->bankdetails }}">
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
                <input type="text" class="form-control"  required name="fundingreason" id="fundingreason"
                       value="{{ (old('fundingreason')) ? old('fundingreason') : $the_record->fundingreason }}">
                @if ($errors->has('fundingreason'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('fundingreason') }}</small>
                    </p>
                @endif
            </fieldset>
        </div>
        <div class="form-group col-12">
            <button type="submit" class="btn btn-secondary square" style="margin-top: 20px"><i class="fa fa-save"> </i> {{$lang['Save']}}</button>
        </div>

    </div>

</form>
