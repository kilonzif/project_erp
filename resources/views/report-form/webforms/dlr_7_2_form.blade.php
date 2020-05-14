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
            <div class="form-group{{ $errors->has('personnel_file') ? ' form-control-warning' : '' }}">
                <label for="personnel_file">{{$lang['Personal File']}} 1<span class="required">*</span></label>
                <input type="file" class="form-control" id="personnel_file" name="personnel_file"
                       @if(isset($the_record))
                       value="{{ (old('personnel_file')) ? old('personnel_file') : $the_record->personnel_file }}"
                       @else
                       required
                       value="{{ (old('personnel_file')) ? old('personnel_file') :'' }}"
                        @endif>
                @if ($errors->has('personnel_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('personnel_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->personnel_file !="")
                        <strong>{{$the_record->personnel_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->personnel_file])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group{{ $errors->has('vacancy_url') ? ' form-control-warning' : '' }}">
                <label for="vacancy_url">{{$lang['Vacancy URL']}}<span class="required">*</span></label>
                <input type="text" class="form-control" id="vacancy_url" required name="vacancy_url"
                       @if(isset($the_record))
                       value="{{ (old('vacancy_url')) ? old('vacancy_url') : $the_record->vacancy_url }}"
                       @else
                       value="{{ (old('vacancy_url')) ? old('vacancy_url') : '' }}"
                        @endif>
                @if ($errors->has('vacancy_url'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('vacancy_url') }}</small>
                    </p>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group{{ $errors->has('report_scores_file') ? ' form-control-warning' : '' }}">
                <label for="upload_2">{{$lang['Report Scores File']}} 1<span class="required">*</span></label>
                <input type="file" class="form-control" id="report_scores_file" name="report_scores_file"
                       @if(isset($the_record))
                       value="{{ (old('report_scores_file')) ? old('report_scores_file') : $the_record->report_scores_file }}"
                       @else
                       required
                       value="{{ (old('report_scores_file')) ? old('report_scores_file') :'' }}"
                        @endif>
                @if ($errors->has('report_scores_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('report_scores_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->report_scores_file !="")
                        <strong>{{$the_record->report_scores_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->report_scores_file])}}"
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