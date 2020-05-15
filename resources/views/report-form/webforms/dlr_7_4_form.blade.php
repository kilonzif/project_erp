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
            <div class="form-group{{ $errors->has('participated_paset') ? ' form-control-warning' : '' }}">
                <label for="budget_report_url">{{$lang['Participated Paset']}}<span class="required">*</span></label>
                @php
                    if(isset($the_record)){
                        $value =$the_record->participated_paset;
                    } else {
                        $value = old('participated_paset') ? old('participated_paset'):'' ;
                    }
                @endphp
                <select class="form-control" id="participated_paset" required name="participated_paset">
                    <option value="">{{$lang['Select One']}}</option>
                    <option value="1"  {{($value == 1)?'selected':''}}>
                        YES</option>
                    <option value="0" {{($value == 0)?'selected':''}}>
                        NO</option>
                </select>
                @if ($errors->has('participated_paset'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('participated_paset') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('participated_initiatives') ? ' form-control-warning' : '' }}">
                <label for="participated_initiatives">{{$lang['Participated Initiatives']}}<span class="required">*</span></label>
                @php
                    if(isset($the_record)){
                        $value =$the_record->participated_initiatives;
                    } else {
                        $value = old('participated_initiatives') ? old('participated_initiatives'):'' ;
                    }
                @endphp
                <select class="form-control" id="participated_initiatives" required name="participated_initiatives">
                    <option value="">{{$lang['Select One']}}</option>
                    <option value="1"  {{($value == 1)?'selected':''}}>
                        YES</option>
                    <option value="0" {{($value == 0)?'selected':''}}>
                        NO</option>
                </select>
                @if ($errors->has('participated_initiatives'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('participated_initiatives') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('benchmarking_year') ? ' form-control-warning' : '' }}">
                <label for="benchmarking_year">{{$lang['Benchmarking Year']}}<span class="required">*</span></label>
                <input type="number" placeholder="YYYY" min="1900" max="2100" class="form-control" id="benchmarking_year" required name="benchmarking_year"
                       @if(isset($the_record))
                       value="{{ (old('benchmarking_year')) ? old('benchmarking_year') : $the_record->benchmarking_year }}"
                       @else
                       value="{{ (old('benchmarking_year')) ? old('benchmarking_year') : '' }}"
                        @endif>
                @if ($errors->has('benchmarking_year'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('benchmarking_year') }}</small>
                    </p>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group{{ $errors->has('self_assessment_file') ? ' form-control-warning' : '' }}">
                <label for="vacancy_url">{{$lang['Self Assessment File']}}<span class="required">*</span></label>
                <input type="file" class="form-control" id="self_assessment_file" name="self_assessment_file"
                       @if(isset($the_record))
                       value="{{ (old('self_assessment_file')) ? old('self_assessment_file') : $the_record->self_assessment_file }}"
                       @else
                       required
                       value="{{ (old('self_assessment_file')) ? old('self_assessment_file') :'' }}"
                        @endif>
                @if ($errors->has('self_assessment_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('self_assessment_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->self_assessment_file !="")
                        <strong>{{$the_record->self_assessment_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->self_assessment_file])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('intervention_plan_file') ? ' form-control-warning' : '' }}">
                <label for="personnel_file">{{$lang['Intervention Plan File']}} 1<span class="required">*</span></label>
                <input type="file" class="form-control" id="intervention_plan_file" name="intervention_plan_file"
                       @if(isset($the_record))
                       value="{{ (old('intervention_plan_file')) ? old('intervention_plan_file') : $the_record->intervention_plan_file }}"
                       @else
                       required
                       value="{{ (old('intervention_plan_file')) ? old('intervention_plan_file') :'' }}"
                        @endif>
                @if ($errors->has('intervention_plan_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('intervention_plan_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->intervention_plan_file !="")
                        <strong>{{$the_record->intervention_plan_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->intervention_plan_file])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
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