<form action="{{route('report_submission.web_form_update_record',[\Illuminate\Support\Facades\Crypt::encrypt($this_indicator->id),$record_id])}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <input type="hidden" name="report_id" value="{{$the_record['report_id']}}">
        <input type="hidden" name="indicator_id" value="{{$this_indicator->id}}">
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('institutionname') ? ' form-control-warning' : '' }}">
                <label for="basicInputFile">Nom de l'institution <span class="required">*</span></label>
                <input type="text" class="form-control"  required name="institutionname"
                       value="{{ (old('institutionname')) ? old('institutionname') : $the_record['institutionname'] }}">
                @if ($errors->has('institutionname'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('institutionname') }}</small>
                    </p>
                @endif
            </fieldset>

        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Type d'accréditation <span class="required">*</span></label>
                <select name="typeofaccreditation" required class="form-control" id="language">
                    <option value="">sélectionnez</option>
                    <option {{($the_record['typeofaccreditation'] == 'National')  ? "selected":""}} value="National">National</option>
                    <option {{($the_record['typeofaccreditation'] == 'International')  ? "selected":""}} value="International">International</option>
                    <option {{($the_record['typeofaccreditation'] == 'Gap Assessment')  ? "selected":""}} value="Gap Assessment">Gap Assessment</option>
                </select>

            </fieldset>
        </div>

        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Accreditation Agency <span class="required">*</span></label>
                <input type="text" class="form-control" name="accreditationagency"
                       value="{{ (old('accreditationagency')) ? old('accreditationagency') : $the_record['accreditationagency'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile"> Référence d'accréditation </label>
                <input type="text" name="accreditationreference" class="form-control"
                       value="{{ (old('accreditationreference')) ? old('accreditationreference') : $the_record['accreditationreference'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Nom de la personne de contact
                    <span class="required">*</span> </label>
                <input class="form-control" required type="text" name="contactname"
                       value="{{ (old('contactname')) ? old('contactname') : $the_record['contactname'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Courriel du personne de contact<span class="required">*</span></label>
                <input type="email" class="form-control" required name="contactemail"
                       value="{{ (old('contactemail')) ? old('contactemail') : $the_record['contactemail'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">
                    Téléphone du contact<span class="required">*</span></label>
                <input type="text" min="10" name="contactphone" required class="form-control"
                       value="{{ (old('contactphone')) ? old('contactphone') : $the_record['contactphone'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset>
                <label for="basicInputFile">Date d'accréditation<span class="required">*</span></label>
                <div class="input-group">
                    <input type="text" required name="dateofaccreditation" class="form-control form-control datepicker"
                           data-date-format="D-M-YYYY" value="{{ (old('dateofaccreditation')) ? old('dateofaccreditation') : $the_record['dateofaccreditation'] }}">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset>
                <label for="basicInputFile">Date d'expiration de l'accréditation<span class="required">*</span></label>
                <div class="input-group">
                    <input type="text" required name="exp_accreditationdate" class="form-control form-control datepicker"
                           data-date-format="D-M-YYYY" value="{{ (old('exp_accreditationdate')) ? old('exp_accreditationdate') : $the_record['exp_accreditationdate'] }}">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="form-group col-12">
            <button type="submit" class="btn btn-secondary square" style="margin-top: 20px"><i class="fa fa-save"></i> Update Records</button>
        </div>

    </div>

</form>
