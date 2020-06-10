@php
    $masters = config('app.filters.masters_text');
    $bachelors = config('app.filters.bachelors_text');
    $phd = config('app.filters.phd_text');
    $national = config('app.filters.national');
    $regional = config('app.filters.regional');
    $international = config('app.filters.international');
    $gap_assessment = config('app.filters.gap_assessment');
    $self_evaluation = config('app.filters.self_evaluation');
    $no = config('app.filters.no');
    $yes = config('app.filters.yes');

    if ($lang=="french") {
        $masters = config('app.filters_fr.masters_text');
        $bachelors = config('app.filters_fr.bachelors_text');
        $phd = config('app.filters_fr.phd_text');
        $national = config('app.filters_fr.national');
        $regional = config('app.filters_fr.regional');
        $international = config('app.filters_fr.international');
        $gap_assessment = config('app.filters_fr.gap_assessment');
        $self_evaluation = config('app.filters_fr.self_evaluation');
        $no = config('app.filters_fr.no');
        $yes = config('app.filters_fr.yes');
    }
@endphp
<form
        @if(isset($the_record))
        action="{{route('report_submission.web_form_update_record',[\Illuminate\Support\Facades\Crypt::encrypt($indicator_info->id),$record_id])}}"
        @else
        action="{{route('report_submission.save_webform',[$indicator_info->id])}}"
        @endif
        method="post" enctype="multipart/form-data">

    @csrf
    <div class="row">
        <input type="hidden" name="report_id" value="{{$report->id}}">
        <input type="hidden" name="indicator_id" value="{{$indicator_info->id}}">
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('institutionname') ? ' form-control-warning' : '' }}">
                <label for="institutionname">{{lang('Institution Name',$lang)}} <span class="required">*</span></label>
                <input type="text" class="form-control" required name="institutionname" id="institutionname"
                       placeholder="{{lang('Institution Name',$lang)}}"
                       @if(isset($the_record))
                       value="{{ (old('institutionname')) ? old('institutionname') : $the_record->institutionname }}"
                       @else
                       value="{{ (old('institutionname')) ? old('institutionname') : '' }}"
                        @endif>
                @if ($errors->has('institutionname'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('institutionname') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('participated_initiatives') ? ' form-control-warning' : '' }}">
                @php
                    if(isset($the_record)){
                        $value =$the_record->typeofaccreditation;
                    } else {
                        $value = old('typeofaccreditation') ? old('typeofaccreditation'):'' ;
                    }
                @endphp
                <label for="typeofaccreditation">{{lang('Type of Accreditation',$lang)}} <span class="required">*</span></label>
                <select name="typeofaccreditation" required class="form-control" id="typeofaccreditation">
                    <option value="">select one</option>
                    <option value="{{$national}}" {{($value == $national)?'selected':''}}>{{lang('National',$lang)}}</option>
                    <option value="{{$international}}" {{($value == $international)?'selected':''}}>{{lang('International',$lang)}}</option>
                    <option value="{{$gap_assessment}}" {{($value == $gap_assessment)?'selected':''}}>{{lang('Gap Assessment',$lang)}}</option>
                </select>
                @if ($errors->has('typeofaccreditation'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('typeofaccreditation') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('accreditationagency') ? ' form-control-warning' : '' }}">
                <label for="accreditationagency">{{lang('Accreditation Agency',$lang)}} <span class="required">*</span></label>
                <input type="text" class="form-control" name="accreditationagency" id="accreditationagency"
                       placeholder="{{lang('Accreditation Agency',$lang)}}"
                       @if(isset($the_record))
                value="{{ (old('accreditationagency')) ? old('accreditationagency') : $the_record->accreditationagency }}"
                       @else
                       value="{{ (old('accreditationagency')) ? old('accreditationagency') : '' }}"
                        @endif>
                @if ($errors->has('accreditationagency'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('accreditationagency') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('accreditationreference') ? ' form-control-warning' : '' }}">
                <label for="accreditationreference">{{lang('Accreditation Reference',$lang)}}</label>
                <input type="text" name="accreditationreference" id="accreditationreference" class="form-control"
                       placeholder="{{lang('Accreditation Reference',$lang)}}"
                       @if(isset($the_record))
                       value="{{ (old('accreditationreference')) ? old('accreditationreference') : $the_record->self_assessment_file }}"
                       @else
                       required
                       value="{{ (old('accreditationreference')) ? old('accreditationreference') :'' }}"
                        @endif>
                @if ($errors->has('accreditationreference'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('accreditationreference') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('contactname') ? ' form-control-warning' : '' }}">
                <label for="contactname">{{lang('Contact Person In the Accreditation Agency',$lang)}}<span class="required">*</span> </label>
                <input class="form-control" required type="text" name="contactname" id="contactname"
                       placeholder="{{lang('Name of Contact Person',$lang)}}"
                       @if(isset($the_record))
                       value="{{ (old('contactname')) ? old('contactname') : $the_record->contactname }}"
                       @else
                       required
                       value="{{ (old('contactname')) ? old('contactname') :'' }}"
                        @endif>
                @if ($errors->has('contactname'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('contactname') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('contactemail') ? ' form-control-warning' : '' }}">
                <label for="contactemail">{{lang('Email of Contact Person',$lang)}}<span class="required">*</span> </label>
                <input type="email" class="form-control" required name="contactemail" id="contactemail"
                       placeholder="{{lang('Email of Contact Person',$lang)}}"
                       @if(isset($the_record))
                       value="{{ (old('contactemail')) ? old('contactemail') : $the_record->contactemail }}"
                       @else
                       required
                       value="{{ (old('contactemail')) ? old('contactemail') :'' }}"
                        @endif>
                @if ($errors->has('contactemail'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('contactemail') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('contactphone') ? ' form-control-warning' : '' }}">
                <label for="contactphone">{{lang('Phone Number of Contact Person',$lang)}} <span class="required">*</span></label>
                <input type="text" min="10" name="contactphone" required class="form-control" id="contactphone"
                       placeholder="{{lang('Phone Number of Contact Person',$lang)}}"
                       @if(isset($the_record))
                       value="{{ (old('contactphone')) ? old('contactphone') : $the_record->contactphone }}"
                       @else
                       required
                       value="{{ (old('contactphone')) ? old('contactphone') :'' }}"
                        @endif>
                @if ($errors->has('contactphone'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('contactphone') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('contactphone') ? ' form-control-warning' : '' }}">
                <label for="dateofaccreditation">{{lang('Date of Accreditation',$lang)}} <span class="required">*</span></label>
                <div class="input-group">
                    <input type="text" required name="dateofaccreditation" class="form-control form-control datepicker"
                           data-date-format="D-M-YYYY" id="dateofaccreditation"
                           @if(isset($the_record))
                           value="{{ (old('dateofaccreditation')) ? old('dateofaccreditation') : $the_record->dateofaccreditation }}"
                           @else
                           required
                           value="{{ (old('dateofaccreditation')) ? old('dateofaccreditation') :'' }}"
                            @endif>   <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                @if ($errors->has('dateofaccreditation'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('dateofaccreditation') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('exp_accreditationdate') ? ' form-control-warning' : '' }}">
                <label for="exp_accreditationdate">{{lang('Expiry Date of Accreditation',$lang)}} <span class="required">*</span></label>
                <div class="input-group">
                    <input type="text" required name="exp_accreditationdate" class="form-control form-control datepicker"
                           data-date-format="D-M-YYYY" id="exp_accreditationdate"
                           @if(isset($the_record))
                           value="{{ (old('exp_accreditationdate')) ? old('exp_accreditationdate') : $the_record->exp_accreditationdate }}"
                           @else
                           required
                           value="{{ (old('exp_accreditationdate')) ? old('exp_accreditationdate') :'' }}"
                            @endif>   <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon4"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                @if ($errors->has('exp_accreditationdate'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('exp_accreditationdate') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="form-group col-12">
            <button type="submit" class="btn btn-secondary square" style="margin-top: 20px"><i class="fa fa-save"></i>
                @if(isset($the_record))
                    {{$lang['Update']}}
                @else
                    {{$lang['Save']}}
                @endif
            </button>
        </div>

    </div>
</form>