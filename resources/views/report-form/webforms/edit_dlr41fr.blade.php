<form action="{{route('report_submission.web_form_update_record',[\Illuminate\Support\Facades\Crypt::encrypt($this_indicator->id),$record_id])}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <input type="hidden" name="report_id" value="{{$the_record['report_id']}}">
        <input type="hidden" name="indicator_id" value="{{$this_indicator->id}}">
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('programmetitle') ? ' form-control-warning' : '' }}">
                <label for="basicInputFile">Titre du programme<span class="required">*</span></label>
                <select name="programmetitle" id="programmetitle" required  class="form-control">
                    <option value="">Select</option>
                    @foreach($ace_programmes as $key=>$ace_programme)
                        @if($ace_programme != "")
                            <option {{($the_record['programmetitle'] == $ace_programme)  ? "selected":""}}
                                    value="{{$ace_programme}}">{{$ace_programme}}</option>
                        @endif
                    @endforeach
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
                <select name="level" required class="form-control" id="language">
                    <option value="">sélectionnez</option>
                    <option  {{($the_record['level'] == 'MASTERS')  ? "selected":""}} value="MASTERS">Masters</option>
                    <option  {{($the_record['level'] == 'PHD')  ? "selected":""}} value="PHD">Doctorat</option>
                    <option  {{($the_record['level'] == 'bachelors')  ? "selected":""}} value="bachelors">Premier Cycle</option>
                    <option  {{($the_record['level'] == 'professional_course')  ? "selected":""}} value="professional_course">Programme de courte durée</option>
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
                    <option {{($the_record['typeofaccreditation'] == 'National')  ? "selected":""}} value="National">Nationale</option>
                    <option {{($the_record['typeofaccreditation'] == 'Regional')  ? "selected":""}} value="Regional">Régionale</option>
                    <option {{($the_record['typeofaccreditation'] == 'International')  ? "selected":""}} value="International">Internationale</option>
                    <option {{($the_record['typeofaccreditation'] == 'Gap Assessment')  ? "selected":""}} value="Gap Assessment">
                        Évaluation des lacunes</option>
                    <option {{($the_record['typeofaccreditation'] == 'Self-Evaluation')  ? "selected":""}} value="Self-Evaluation">
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
                       value="{{ (old('accreditationreference')) ? old('accreditationreference') : $the_record['accreditationreference'] }}">
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
                       value="{{ (old('accreditationagency')) ? old('accreditationagency') : $the_record['accreditationagency'] }}">
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
                       value="{{ (old('agencyname')) ? old('agencyname') : $the_record['agencyname'] }}">
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
                       value="{{ (old('agencyemail')) ? old('agencyemail') : $the_record['agencyemail'] }}">
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
                       value="{{ (old('agencycontact')) ? old('agencycontact') : $the_record['agencycontact'] }}">
                @if ($errors->has('agencycontact'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('agencycontact') }}</small>
                    </p>
                @endif
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('dateofaccreditation') ? ' form-control-warning' : '' }}">
                <label for="basicInputFile">Date d'accréditation<span class="required">*</span></label>
                <div class="input-group">
                    <input type="text" required name="dateofaccreditation" class="form-control form-control datepicker"
                           data-date-format="D-M-YYYY" value="{{ (old('dateofaccreditation')) ? old('dateofaccreditation') : $the_record['dateofaccreditation'] }}">
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
                           data-date-format="D-M-YYYY"  value="{{ (old('exp_accreditationdate')) ? old('exp_accreditationdate') : $the_record['exp_accreditationdate'] }}">
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
                    @php
                        $newly_accredited_programme = "";
                        if (isset($the_record->newly_accredited_programme)) {
                            $newly_accredited_programme = $the_record->newly_accredited_programme;
                        }
                    @endphp
                    <select name="newly_accredited_programme" class="form-control" required id="newly_accredited_programme">
                        <option value="">Sélectionner</option>
                        <option {{($newly_accredited_programme == 'Non')  ? "selected":""}} value="Non">Non</option>
                        <option {{($newly_accredited_programme == 'Oui')  ? "selected":""}} value="Oui">Oui</option>
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
            <button type="submit" class="btn btn-secondary square" style="margin-top: 20px"><i class="fa fa-save">   Update </i>     Records</button>
        </div>

    </div>

</form>
