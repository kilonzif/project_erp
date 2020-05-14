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
            <div class="form-group{{ $errors->has('connectivity_file') ? ' form-control-warning' : '' }}">
                <label for="vacancy_url">{{$lang['Connectivity File']}}<span class="required">*</span></label>
                <input type="file" class="form-control" id="connectivity_file"  name="connectivity_file"
                       @if(isset($the_record))
                       value="{{ (old('connectivity_file')) ? old('connectivity_file') : $the_record->connectivity_file }}"
                       @else
                       required
                       value="{{ (old('connectivity_file')) ? old('connectivity_file') : '' }}"
                        @endif>
                @if ($errors->has('connectivity_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('connectivity_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->connectivity_file !="")
                        <strong>{{$the_record->connectivity_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->connectivity_file])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('infrastructure_upgrade_file') ? ' form-control-warning' : '' }}">
                <label for="infrastructure_upgrade_file">{{$lang['Infrastructure Upgrade File']}}<span class="required">*</span></label>
                <input type="file" class="form-control" id="infrastructure_upgrade_file" name="infrastructure_upgrade_file"
                       @if(isset($the_record))
                       value="{{ (old('infrastructure_upgrade_file')) ? old('infrastructure_upgrade_file') : $the_record->infrastructure_upgrade_file }}"
                       @else
                       required
                       value="{{ (old('infrastructure_upgrade_file')) ? old('infrastructure_upgrade_file') : '' }}"
                        @endif>
                @if ($errors->has('infrastructure_upgrade_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('infrastructure_upgrade_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->infrastructure_upgrade_file !="")
                        <strong>{{$the_record->infrastructure_upgrade_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->infrastructure_upgrade_file])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('satisfactory_survey_file') ? ' form-control-warning' : '' }}">
                <label for="personnel_file">{{$lang['Satisfactory Survey File']}} 1<span class="required">*</span></label>
                <input type="file" class="form-control" id="satisfactory_survey_file" name="satisfactory_survey_file"
                       @if(isset($the_record))
                       value="{{ (old('satisfactory_survey_file')) ? old('satisfactory_survey_file') : $the_record->satisfactory_survey_file }}"
                       @else
                       required
                       value="{{ (old('satisfactory_survey_file')) ? old('satisfactory_survey_file') :'' }}"
                        @endif>
                @if ($errors->has('satisfactory_survey_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('satisfactory_survey_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->satisfactory_survey_file !="")
                        <strong>{{$the_record->satisfactory_survey_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->satisfactory_survey_file])}}"
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