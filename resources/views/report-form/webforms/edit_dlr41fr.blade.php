<form action="{{route('report_submission.web_form_update_record',[\Illuminate\Support\Facades\Crypt::encrypt($this_indicator->id),$record_id])}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <input type="hidden" name="report_id" value="{{$the_record['report_id']}}">
        <input type="hidden" name="indicator_id" value="{{$this_indicator->id}}">
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('programmetitle') ? ' form-control-warning' : '' }}">
                <label for="basicInputFile">Titre du programme<span class="required">*</span></label>
                <input type="text" class="form-control"  required name="programmetitle"
                       value="{{ (old('programmetitle')) ? old('programmetitle') : $the_record['programmetitle'] }}">
                @if ($errors->has('programmetitle'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('programmetitle') }}</small>
                    </p>
                @endif
            </fieldset>

        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Niveau<span class="required">*</span></label>
                <select name="level" required class="form-control" id="language">
                    <option value="">select LEVEL</option>
                    <option  {{($the_record['level'] == 'MASTERS')  ? "selected":""}} value="MASTERS">Masters</option>
                    <option  {{($the_record['level'] == 'PHD')  ? "selected":""}} value="PHD">PhD</option>
                </select>
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Type d'accreditation <span class="required">*</span></label>
                <select name="typeofaccreditation" required class="form-control" id="language">
                    <option value="">select one</option>
                    <option {{($the_record['typeofaccreditation'] == 'National')  ? "selected":""}} value="National">National</option>
                    <option {{($the_record['typeofaccreditation'] == 'Regional')  ? "selected":""}} value="Regional">Regional</option>
                    <option {{($the_record['typeofaccreditation'] == 'International')  ? "selected":""}} value="International">International</option>
                    <option {{($the_record['typeofaccreditation'] == 'Gap Assessment')  ? "selected":""}} value="Gap Assessment">Gap Assessment</option>
                    <option {{($the_record['typeofaccreditation'] == 'Self-Evaluation')  ? "selected":""}} value="Self-Evaluation">Self-Evaluation</option>
                </select>

            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Référence de l'accréditation <span class="required">*</span></label>
                <input type="text" name="accreditationreference"  required class="form-control"
                       value="{{ (old('accreditationreference')) ? old('accreditationreference') : $the_record['accreditationreference'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Agence d'accréditation <span class="required">*</span></label>
                <input type="text" class="form-control" name="accreditationagency"
                       value="{{ (old('accreditationagency')) ? old('accreditationagency') : $the_record['accreditationagency'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Personne contact de l'agence<span class="required">*</span> </label>
                <input class="form-control" required type="text" name="agencyname"
                       value="{{ (old('agencyname')) ? old('agencyname') : $the_record['agencyname'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Couriel du personne contact <span class="required">*</span></label>
                <input type="email" class="form-control" required name="agencyemail"
                       value="{{ (old('agencyemail')) ? old('agencyemail') : $the_record['agencyemail'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Numéro de téléphone du personne contact <span class="required">*</span></label>
                <input type="text" min="10" name="agencycontact" required class="form-control"
                       value="{{ (old('agencycontact')) ? old('agencycontact') : $the_record['agencycontact'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Date d'accréditation <span class="required">*</span></label>
                <input type="date" class="form-control" required name="dateofaccreditation"
                       value="{{ (old('dateofaccreditation')) ? old('dateofaccreditation') : $the_record['dateofaccreditation'] }}">
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label for="basicInputFile">Date d'expiration de l'accréditation<span class="required">*</span></label>
                <input type="date" name="exp_accreditationdate"  required class="form-control"
                       value="{{ (old('exp_accreditationdate')) ? old('exp_accreditationdate') : $the_record['exp_accreditationdate'] }}">
            </fieldset>
        </div>
        <div class="form-group col-12">
            <button type="submit" class="btn btn-secondary square"><i class="fa fa-save">   Update </i>     Records</button>
        </div>

    </div>

</form>
