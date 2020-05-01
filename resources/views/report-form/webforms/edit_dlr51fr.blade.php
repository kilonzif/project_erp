<form action="{{route('report_submission.web_form_update_record',[\Illuminate\Support\Facades\Crypt::encrypt($this_indicator->id),$record_id])}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <input type="hidden" name="report_id" value="{{$the_record['report_id']}}">
        <input type="hidden" name="indicator_id" value="{{$this_indicator->id}}">
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('amountindollars') ? ' form-control-warning' : '' }}">
                <label for="basicInputFile">Montant (Dollar US)<span class="required">*</span></label>
                <input type="number" class="form-control"  min="0"  step="0.01" required name="amountindollars"
                       value="{{ (old('amountindollars')) ? old('amountindollars') : $the_record['amountindollars'] }}">
                @if ($errors->has('amountindollars'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('amountindollars') }}</small>
                    </p>
                @endif
            </fieldset>

        </div>
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('originalamount') ? ' form-control-warning' : '' }}">
                <label for="basicInputFile">Montant original<span class="required">*</span></label>
                <input type="number" class="form-control"  min="0"  step="0.01" required name="originalamount"
                       value="{{ (old('originalamount')) ? old('originalamount') : $the_record['originalamount'] }}">
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
                <input type="text" class="form-control"  min="0" required name="source"
                       value="{{ (old('source')) ? old('source') : $the_record['source'] }}">
                @if ($errors->has('source'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('source') }}</small>
                    </p>
                @endif
            </fieldset>

        </div>


        <div class="col-md-4">
            <fieldset>
                <label for="basicInputFile">Date de réception<span class="required">*</span></label>
                <div class="input-group">
                    <input type="text" required name="datereceived" class="form-control form-control datepicker"
                           data-date-format="D-M-YYYY" value="{{ (old('datereceived')) ? old('datereceived') : $the_record['datereceived'] }}">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('bankdetails') ? ' form-control-warning' : '' }}">
                <label for="basicInputFile">Coordonnées bancaires<span class="required">*</span></label>
                <input type="text" class="form-control"  min="0" required name="bankdetails"
                       value="{{ (old('bankdetails')) ? old('bankdetails') : $the_record['bankdetails'] }}">
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
                <input type="text" class="form-control" required name="region"
                       value="{{ (old('region')) ? old('region') : $the_record['region'] }}">
                @if ($errors->has('region'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('region') }}</small>
                    </p>
                @endif
            </fieldset>

        </div>
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('fundingreason') ? ' form-control-warning' : '' }}">
                <label for="basicInputFile">Destination des fonds<span class="required">*</span></label>
                <input type="text" class="form-control"  required name="fundingreason"
                       value="{{ (old('fundingreason')) ? old('fundingreason') : $the_record['fundingreason'] }}">
                @if ($errors->has('fundingreason'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('fundingreason') }}</small>
                    </p>
                @endif
            </fieldset>

        </div>
        <div class="form-group col-12">
            <button type="submit" class="btn btn-secondary square" style="margin-top: 20px"><i class="fa fa-save">   Update </i>     Record</button>
        </div>

    </div>

</form>
