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
            <div class="form-group{{ $errors->has('approved_procurement_file') ? ' form-control-warning' : '' }}">
                <label for="file_name_1">{{$lang['Approved Procurement']}}<span class="required">*</span></label>
                <input type="file" class="form-control" id="file_name_1" name="approved_procurement_file"
                       @if(isset($the_record))
                       value="{{ (old('approved_procurement_file')) ? old('approved_procurement_file') : $the_record->approved_procurement_file }}"
                       @else
                       required
                       value="{{ (old('approved_procurement_file')) ? old('approved_procurement_file') :'' }}"
                        @endif>
                @if ($errors->has('approved_procurement_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('approved_procurement_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->approved_procurement_file !="")
                        <strong>{{$the_record->approved_procurement_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->approved_procurement_file])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group{{ $errors->has('officer_file') ? ' form-control-warning' : '' }}">
                <label for="officer_file">{{$lang['ACE Procurement Officer']}}<span class="required">*</span></label>
                <input type="file" class="form-control" id="officer_file" name="officer_file"
                       @if(isset($the_record))
                       value="{{ (old('officer_file')) ? old('officer_file') : $the_record->officer_file }}"
                       @else
                       required
                       value="{{ (old('officer_file')) ? old('officer_file') : '' }}"
                        @endif>
                @if ($errors->has('officer_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('officer_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->officer_file !="")
                        <strong>{{$the_record->officer_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->officer_file])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group{{ $errors->has('procurement_progress_report_file') ? ' form-control-warning' : '' }}">
                <label for="procurement_progress_report_file">{{$lang['Procurement Progress Report']}}<span class="required">*</span></label>
                <input type="file" class="form-control" id="procurement_progress_report_file" name="procurement_progress_report_file"
                       @if(isset($the_record))
                       value="{{ (old('procurement_progress_report_file')) ? old('procurement_progress_report_file') : $the_record->procurement_progress_report_file }}"
                       @else
                       required
                       value="{{ (old('procurement_progress_report_file')) ? old('procurement_progress_report_file') : '' }}"
                        @endif>
                @if ($errors->has('procurement_progress_report_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('procurement_progress_report_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->procurement_progress_report_file !="")
                        <strong>{{$the_record->procurement_progress_report_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->procurement_progress_report_file])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group{{ $errors->has('contracts_signed_file') ? ' form-control-warning' : '' }}">
                <label for="contracts_signed_file">{{$lang['Contracts Signed']}}<span class="required">*</span></label>
                <input type="file" class="form-control" id="contracts_signed_file" name="contracts_signed_file"
                       @if(isset($the_record))
                       value="{{ (old('contracts_signed_file')) ? old('contracts_signed_file') : $the_record->contracts_signed_file }}"
                       @else
                       required
                       value="{{ (old('contracts_signed_file')) ? old('contracts_signed_file') : '' }}"
                        @endif>
                @if ($errors->has('contracts_signed_file'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('contracts_signed_file') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->contracts_signed_file !="")
                        <strong>{{$the_record->contracts_signed_file}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->contracts_signed_file])}}"
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