<form action="{{route('report_submission.web_form_update_record',[\Illuminate\Support\Facades\Crypt::encrypt($this_indicator->id),$record_id])}}" method="post" enctype="multipart/form-data">

    @csrf
    <div class="row">
        <input type="hidden" name="report_id" value="{{$the_record->report_id}}">
        <input type="hidden" name="indicator_id" value="{{$this_indicator->id}}">
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('ifr_period') ? ' form-control-warning' : '' }}">
                <label for="ifr_period">{{$lang['Period covered by IFR']}}<span class="required">*</span></label>
                <input type="text" class="form-control" id="ifr_period" required name="ifr_period"
                       value="{{ (old('ifr_period')) ? old('ifr_period') : $the_record->ifr_period }}">
                @if ($errors->has('ifr_period'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('ifr_period') }}</small>
                    </p>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group{{ $errors->has('file_name_1') ? ' form-control-warning' : '' }}">
                <label for="file_name_1">{{$lang['File Upload']}}<span class="required">*</span></label>
                <input type="file" class="form-control" id="file_name_1" require name="file_name_1"
                       value="{{ (old('file_name_2')) ? old('file_name_2') : $the_record->file_name_2 }}">
                @if ($errors->has('file_name_1'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('file_name_1') }}</small>
                    </p>
                @endif
                @if($the_record->file_name_1 !="")
                    <strong>{{$the_record->file_name_1}}</strong>
                    <a href="{{asset('indicator_6_1/'.$the_record->file_name_1)}}" target="_blank">
                        <span class="fa fa-file"></span>   Download file
                    </a>
                    <br>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group{{ $errors->has('file_name_1_submission') ? ' form-control-warning' : '' }}">
                <label for="file_name_1_submission">{{$lang['Date of Submission']}}<span class="required">*</span></label>
                <input type="date" class="form-control" id="file_name_1_submission"
                       required name="file_name_1_submission"
                       data-date-format="D-M-YYYY"
                       value="{{ (old('file_name_1_submission')) ? old('file_name_1_submission') : $the_record->file_name_1_submission }}">
                @if ($errors->has('file_name_1_submission'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('file_name_1_submission') }}</small>
                    </p>
                @endif

            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group{{ $errors->has('efa_period') ? ' form-control-warning' : '' }}">
                <label for="efa_period">{{$lang['Period covered by EFA']}}<span class="required">*</span></label>
                <input type="text" class="form-control" id="efa_period" required name="efa_period"
                       value="{{ (old('efa_period')) ? old('efa_period') : $the_record->efa_period }}">
                @if ($errors->has('efa_period'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('efa_period') }}</small>
                    </p>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group{{ $errors->has('file_name_2') ? ' form-control-warning' : '' }}">
                <label for="file_name_2">{{$lang['File Upload']}}<span class="required">*</span></label>
                <input type="file" class="form-control" id="file_name_2" require name="file_name_2"
                       value="{{ (old('file_name_2')) ? old('file_name_2') : $the_record->file_name_2 }}">
                @if ($errors->has('file_name_2'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('file_name_2') }}</small>
                    </p>
                @endif
                @if($the_record->file_name_2 !="")
                    <strong>{{$the_record->file_name_2}}</strong>
                    <a href="{{asset('indicator_6_1/'.$the_record->file_name_2)}}" target="_blank">
                        <span class="fa fa-file"></span>   Download file
                    </a>
                    <br>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group{{ $errors->has('file_name_2_submission') ? ' form-control-warning' : '' }}">
                <label for="file_name_2_submission">{{$lang['Date of Submission']}}<span class="required">*</span></label>
                <input type="date" class="form-control" id="file_name_2_submission"
                       required name="file_name_2_submission"
                       data-date-format="D-M-YYYY"
                       value="{{ (old('file_name_2_submission')) ? old('file_name_2_submission') : $the_record->file_name_2_submission }}">
                @if ($errors->has('file_name_2_submission'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('file_name_2_submission') }}</small>
                    </p>
                @endif
            </div>
        </div>

        <div class="form-group col-12">
            <button type="submit" class="btn btn-secondary square" style="margin-top: 20px"><i class="fa fa-save"></i> {{$lang['Save']}} </button>
        </div>

    </div>
</form>