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
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('guideline_file') ? ' form-control-warning' : '' }}">
            <label for="file_name_2">{{$lang['Guideline File']}}<span class="required">*</span></label>
            <input type="file" class="form-control" id="guideline_file" require name="guideline_file"
                   @if(isset($the_record))
                   value="{{ (old('guideline_file')) ? old('guideline_file') : $the_record->guideline_file }}">
                   @else
                   value="{{ (old('guideline_file')) ? old('guideline_file') :'' }}">
                    @endif
            @if ($errors->has('guideline_file'))
                <p class="text-right mb-0">
                    <small class="warning text-muted">{{ $errors->first('guideline_file') }}</small>
                </p>
            @endif
            @if(isset($the_record))
                @if($the_record->guideline_file !="")
                    <strong>{{$the_record->guideline_file}}</strong>
                    <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->guideline_file])}}"
                       target="_blank">
                        <span class="fa fa-file"></span> {{$lang['Download']}}
                    </a>
                    <br>
                @endif
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('members_file') ? ' form-control-warning' : '' }}">
            <label for="file_name_2">{{$lang['Members File']}}<span class="required">*</span></label>
            <input type="file" class="form-control" id="members_file" require name="members_file"
                   @if(isset($the_record))
                   value="{{ (old('members_file')) ? old('members_file') : $the_record->members_file }}">
            @else
                value="{{ (old('members_file')) ? old('members_file') :'' }}">
            @endif
            @if ($errors->has('members_file'))
                <p class="text-right mb-0">
                    <small class="warning text-muted">{{ $errors->first('members_file') }}</small>
                </p>
            @endif
            @if(isset($the_record))
                @if($the_record->members_file !="")
                    <strong>{{$the_record->members_file}}</strong>
                    <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->members_file])}}"
                       target="_blank">
                        <span class="fa fa-file"></span> {{$lang['Download']}}
                    </a>
                    <br>
                @endif
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('report_file') ? ' form-control-warning' : '' }}">
            <label for="file_name_2">{{$lang['Report File']}}<span class="required">*</span></label>
            <input type="file" class="form-control" id="report_file" require name="report_file"
                   @if(isset($the_record))
                   value="{{ (old('report_file')) ? old('report_file') : $the_record->report_file }}">
            @else
                value="{{ (old('report_file')) ? old('report_file') :'' }}">
            @endif
            @if ($errors->has('report_file'))
                <p class="text-right mb-0">
                    <small class="warning text-muted">{{ $errors->first('report_file') }}</small>
                </p>
            @endif
            @if(isset($the_record))
                @if($the_record->report_file !="")
                    <strong>{{$the_record->report_file}}</strong>
                    <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->report_file])}}"
                       target="_blank">
                        <span class="fa fa-file"></span> {{$lang['Download']}}
                    </a>
                    <br>
                @endif
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('report_file') ? ' form-control-warning' : '' }}">
            <label for="file_name_2">{{$lang['Audited Account File']}}<span class="required">*</span></label>
            <input type="file" class="form-control" id="audited_account_file" require name="audited_account_file"
                   @if(isset($the_record))
                   value="{{ (old('audited_account_file')) ? old('audited_account_file') : $the_record->audited_account_file }}">
            @else
                value="{{ (old('audited_account_file')) ? old('audited_account_file') :'' }}">
            @endif
            @if ($errors->has('audited_account_file'))
                <p class="text-right mb-0">
                    <small class="warning text-muted">{{ $errors->first('audited_account_file') }}</small>
                </p>
            @endif
            @if(isset($the_record))
                @if($the_record->audited_account_file !="")
                    <strong>{{$the_record->audited_account_file}}</strong>
                    <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->audited_account_file])}}"
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

