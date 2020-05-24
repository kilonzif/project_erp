<form
    @if(isset($the_record))
    action="{{route('report_submission.web_form_update_record',
    [\Illuminate\Support\Facades\Crypt::encrypt($indicator_info->id),$record_id])}}"
    @else
    action="{{route('report_submission.save_webform',[$indicator_info->id])}}"
    @endif
    method="post"
      enctype="multipart/form-data">
    @csrf
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

        if ($report->language=="french") {
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
    <div class="row">
        <input type="hidden" name="report_id" value="{{$report->id}}">
        <input type="hidden" name="indicator_id" value="{{$indicator_info->id}}">
        <div class="col-md-4">
            @php
                if(isset($the_record)){
                    $value =$the_record->programmetitle;
                } else {
                    $value = old('programmetitle') ? old('programmetitle'):'' ;
                }
            @endphp
            <fieldset class="form-group{{ $errors->has('programmetitle') ? ' form-control-warning' : '' }}">
                <label for="programmetitle">{{lang('Program Title',$report->language)}} <span class="required">*</span></label>
                <select name="programmetitle" id="programmetitle" required  class="form-control">
                    <option value="">Select</option>
                    @isset($ace_programmes)
                        @foreach($ace_programmes as $key=>$ace_programme)
                            @if($ace_programme != "")
                                <option {{($value == $ace_programme) ? "selected" :" "}}
                                        value="{{$ace_programme}}">{{$ace_programme}}</option>
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
            @php
                if(isset($the_record)){
                    $value =$the_record->level;
                } else {
                    $value = old('level') ? old('level'):'' ;
                }
            @endphp
            <fieldset class="form-group{{ $errors->has('level') ? ' form-control-warning' : '' }}">
                <label for="level">{{lang('Accreditation Level',$report->language)}}<span class="required">*</span></label>
                <select name="level" required class="form-control" id="level">
                    <option value="">select one</option>
                    <option {{($value ==$masters) ? "selected" :" "}} value="{{$masters}}">{{$masters}}</option>
                    <option {{($value ==$phd) ? "selected" :" "}} value="{{$phd}}">{{$phd}}</option>
                    <option {{($value ==$bachelors) ? "selected" :" "}} value="{{$bachelors}}">{{$bachelors}}</option>
                </select>
                @if ($errors->has('level'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('level') }}</small>
                    </p>
                @endif
            </fieldset>
        </div>
        <div class="col-md-4">
            @php
                if(isset($the_record)){
                    $value =$the_record->typeofaccreditation;
                } else {
                    $value = old('typeofaccreditation') ? old('typeofaccreditation'):'' ;
                }
            @endphp
            <fieldset class="form-group{{ $errors->has('typeofaccreditation') ? ' form-control-warning' : '' }}">
                <label for="basicInputFile">{{lang('Accreditation Type',$report->language)}} <span class="required">*</span></label>
                <select name="typeofaccreditation" required class="form-control" id="language">
                    <option value="">select one</option>
                    <option {{($value == "$national") ? "selected" :" "}} value="National">{{$national}}</option>
                    <option {{($value == "$regional") ? "selected" :" "}} value="{{$regional}}">{{$regional}}</option>
                    <option {{($value == "$international") ? "selected" :" "}} value="{{$international}}">{{$international}}</option>
                    <option {{($value == "$gap_assessment") ? "selected" :" "}} value="{{$gap_assessment}}">{{$gap_assessment}}</option>
                    <option {{($value == "$self_evaluation") ? "selected" :" "}} value="{{$self_evaluation}}">{{$self_evaluation}}</option>
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
                <label for="accreditationreference">{{lang('Accreditation Reference',$report->language)}}</label>
                <input type="text" name="accreditationreference" class="form-control" id="accreditationreference"
                       @if(isset($the_record))
                       value="{{ (old('accreditationreference')) ? old('accreditationreference') : $the_record->accreditationreference }}"
                       @else
                       {{--required--}}
                       value="{{ (old('accreditationreference')) ? old('accreditationreference') :'' }}"
                        @endif>
                @if ($errors->has('accreditationreference'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('accreditationreference') }}</small>
                    </p>
                @endif
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('accreditationagency') ? ' form-control-warning' : '' }}">
                <label for="accreditationagency">{{lang('Accreditation Agency',$report->language)}} <span class="required">*</span></label>
                <input type="text" class="form-control" name="accreditationagency" id="accreditationagency" required
                       @if(isset($the_record))
                       value="{{ (old('accreditationagency')) ? old('accreditationagency') : $the_record->accreditationagency }}"
                       @else
                       value="{{ (old('accreditationagency')) ? old('accreditationagency') :'' }}"
                        @endif>
                @if ($errors->has('accreditationagency'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('accreditationagency') }}</small>
                    </p>
                @endif
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('agencyname') ? ' form-control-warning' : '' }}">
                <label for="agencyname">{{lang('Agency Contact Name',$report->language)}}<span class="required">*</span> </label>
                <input class="form-control" required type="text" name="agencyname" id="agencyname"
                       @if(isset($the_record))
                       value="{{ (old('agencyname')) ? old('agencyname') : $the_record->agencyname }}"
                       @else
                       value="{{ (old('agencyname')) ? old('agencyname') :'' }}"
                        @endif>
                @if ($errors->has('agencyname'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('agencyname') }}</small>
                    </p>
                @endif
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('agencyemail') ? ' form-control-warning' : '' }}">
                <label for="agencyemail">{{lang('Agency Contact Email',$report->language)}} <span class="required">*</span></label>
                <input type="email" class="form-control" required name="agencyemail" id="agencyemail"
                       @if(isset($the_record))
                       value="{{ (old('agencyemail')) ? old('agencyemail') : $the_record->agencyemail }}"
                       @else
                       value="{{ (old('agencyemail')) ? old('agencyemail') :'' }}"
                        @endif>
                @if ($errors->has('agencyemail'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('agencyemail') }}</small>
                    </p>
                @endif
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('agencycontact') ? ' form-control-warning' : '' }}">
                <label for="agencycontact">{{lang('Agency Contact Phone Number',$report->language)}} <span class="required">*</span></label>
                <input type="text" min="10" name="agencycontact" required class="form-control" id="agencycontact"
                       @if(isset($the_record))
                       value="{{ (old('agencycontact')) ? old('agencycontact') : $the_record->agencycontact }}"
                       @else
                       value="{{ (old('agencycontact')) ? old('agencycontact') :'' }}"
                        @endif>
                @if ($errors->has('agencycontact'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('agencycontact') }}</small>
                    </p>
                @endif
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group{{ $errors->has('dateofaccreditation') ? ' form-control-warning' : '' }}">
                <label for="dateofaccreditation">{{lang('Date of Accreditation',$report->language)}} <span class="required">*</span></label>
                <div class="input-group">
                    <input type="text" required name="dateofaccreditation" class="form-control form-control datepicker"
                           data-date-format="D-M-YYYY" id="dateofaccreditation"
                           @if(isset($the_record))
                           value="{{ (old('dateofaccreditation')) ? old('dateofaccreditation') : $the_record->dateofaccreditation }}"
                           @else
                           value="{{ (old('dateofaccreditation')) ? old('dateofaccreditation') :'' }}"
                            @endif>
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
                <label for="exp_accreditationdate">{{lang('Expiry Date of Accreditation',$report->language)}}<span class="required">*</span></label>
                <div class="input-group">
                    <input type="text" required name="exp_accreditationdate" class="form-control form-control datepicker"
                           data-date-format="D-M-YYYY"  id="exp_accreditationdate"
                           @if(isset($the_record))
                           value="{{ (old('exp_accreditationdate')) ? old('exp_accreditationdate') : $the_record->exp_accreditationdate }}"
                           @else
                           value="{{ (old('exp_accreditationdate')) ? old('exp_accreditationdate') :'' }}"
                            @endif>
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
            @php
                if(isset($the_record)){
                    $value =$the_record->newly_accredited_programme;
                } else {
                    $value = old('newly_accredited_programme') ? old('newly_accredited_programme'):'' ;
                }
            @endphp
            <fieldset class="form-group{{ $errors->has('newly_accredited_programme') ? ' form-control-warning' : '' }}">
                <label for="newly_accredited_programme">{{lang('Newly Accredited Programme?',$report->language)}}<span class="required">*</span></label>
                <div class="input-group">
                    <select name="newly_accredited_programme" class="form-control" required id="newly_accredited_programme">
                        <option value="">Select</option>
                        <option {{($value == "$no") ? "selected" :" "}} value="{{$no}}">{{$no}}</option>
                        <option {{($value == $yes) ? "selected" :" "}} value="{{$yes}}">{{$yes}}</option>
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
            <button type="submit" class="btn btn-secondary square" style="margin-top: 20px"><i class="fa fa-save"></i> {{lang('Save', $report->language)}}</button>
        </div>

    </div>
</form>