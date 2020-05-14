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
            <div class="form-group{{ $errors->has('submission_date') ? ' form-control-warning' : '' }}">
                <label for="submission_date">{{$lang['Date of Submission']}}<span class="required">*</span></label>
                <input type="date" class="form-control" id="file_name_2_submission"
                       required name="submission_date" data-date-format="D-M-YYYY"
                       @if(isset($the_record))
                       value="{{ (old('submission_date')) ? old('submission_date') : $the_record->submission_date }}"
                       @else
                       value="{{ (old('submission_date')) ? old('submission_date') : '' }}"
                        @endif>
                @if ($errors->has('submission_date'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('submission_date') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('financial_report_url') ? ' form-control-warning' : '' }}">
                <label for="financial_report_url">{{$lang['Financial Report URL']}}<span class="required">*</span></label>
                <input type="text" class="form-control" id="financial_report_url" required name="financial_report_url"
                       @if(isset($the_record))
                       value="{{ (old('financial_report_url')) ? old('financial_report_url') : $the_record->financial_report_url }}"
                       @else
                       value="{{ (old('financial_report_url')) ? old('financial_report_url') : '' }}"
                        @endif>
                @if ($errors->has('financial_report_url'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('financial_report_url') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('budget_report_url') ? ' form-control-warning' : '' }}">
                <label for="budget_report_url">{{$lang['Budget Report URL']}}<span class="required">*</span></label>
                <input type="text" class="form-control" id="budget_report_url" required name="budget_report_url"
                       @if(isset($the_record))
                       value="{{ (old('budget_report_url')) ? old('budget_report_url') : $the_record->budget_report_url }}"
                       @else
                       value="{{ (old('budget_report_url')) ? old('budget_report_url') : '' }}"
                        @endif>
                @if ($errors->has('budget_report_url'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('budget_report_url') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('work_plan_url') ? ' form-control-warning' : '' }}">
                <label for="work_plan_url">{{$lang['Work Plan URL']}}<span class="required">*</span></label>
                <input type="text" class="form-control" id="work_plan_url" required name="work_plan_url"
                       @if(isset($the_record))
                       value="{{ (old('work_plan_url')) ? old('work_plan_url') : $the_record->work_plan_url }}"
                       @else
                       value="{{ (old('work_plan_url')) ? old('work_plan_url') : '' }}"
                        @endif>
                @if ($errors->has('work_plan_url'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('work_plan_url') }}</small>
                    </p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('other_files_url') ? ' form-control-warning' : '' }}">
                <label for="other_files_url">{{$lang['Other Files URL']}}<span class="required">*</span></label>
                <input type="text" class="form-control" id="other_files_url" required name="other_files_url"
                       @if(isset($the_record))
                       value="{{ (old('other_files_url')) ? old('other_files_url') : $the_record->other_files_url }}"
                       @else
                       value="{{ (old('other_files_url')) ? old('other_files_url') : '' }}"
                        @endif>
                @if ($errors->has('other_files_url'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('other_files_url') }}</small>
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
