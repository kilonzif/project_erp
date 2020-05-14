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
            <div class="form-group{{ $errors->has('upload_1') ? ' form-control-warning' : '' }}">
                <label for="upload_1">{{$lang['Document']}} 1<span class="required">*</span></label>
                <input type="file" class="form-control" id="upload_1" name="upload_1"
                       @if(isset($the_record))
                       value="{{ (old('upload_1')) ? old('upload_1') : $the_record->upload_1 }}"
                       @else
                       required
                       value="{{ (old('upload_1')) ? old('upload_1') :'' }}"
                        @endif>
                @if ($errors->has('upload_1'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('upload_1') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->upload_1 !="")
                        <strong>{{$the_record->upload_1}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->upload_1])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group{{ $errors->has('upload_1_description') ? ' form-control-warning' : '' }}">
                <label for="upload_1_description">{{$lang['Document 1 Description']}}<span class="required">*</span></label>
                <input type="text" class="form-control" id="upload_1_description" required name="upload_1_description"
                       @if(isset($the_record))
                       value="{{ (old('upload_1_description')) ? old('upload_1_description') : $the_record->upload_1_description }}"
                       @else
                       value="{{ (old('upload_1_description')) ? old('upload_1_description') : '' }}"
                        @endif>
                @if ($errors->has('upload_1_description'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('upload_1_description') }}</small>
                    </p>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group{{ $errors->has('upload_2') ? ' form-control-warning' : '' }}">
                <label for="upload_2">{{$lang['Document']}} 1<span class="required">*</span></label>
                <input type="file" class="form-control" id="upload_2" name="upload_2"
                       @if(isset($the_record))
                       value="{{ (old('upload_2')) ? old('upload_2') : $the_record->upload_2 }}"
                       @else
                       required
                       value="{{ (old('upload_2')) ? old('upload_2') :'' }}"
                        @endif>
                @if ($errors->has('upload_2'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('upload_2') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->upload_2 !="")
                        <strong>{{$the_record->upload_2}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->upload_2])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group{{ $errors->has('upload_2_description') ? ' form-control-warning' : '' }}">
                <label for="upload_2_description">{{$lang['Document 2 Description']}}<span class="required">*</span></label>
                <input type="text" class="form-control" id="upload_2_description" required name="upload_2_description"
                       @if(isset($the_record))
                       value="{{ (old('upload_2_description')) ? old('upload_2_description') : $the_record->upload_2_description }}"
                       @else
                       value="{{ (old('upload_2_description')) ? old('upload_2_description') : '' }}"
                        @endif>
                @if ($errors->has('upload_2_description'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('upload_2_description') }}</small>
                    </p>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group{{ $errors->has('upload_3') ? ' form-control-warning' : '' }}">
                <label for="upload_3">{{$lang['Document']}} 3<span class="required">*</span></label>
                <input type="file" class="form-control" id="upload_3" name="upload_3"
                       @if(isset($the_record))
                       value="{{ (old('upload_3')) ? old('upload_3') : $the_record->upload_3 }}"
                       @else
                       required
                       value="{{ (old('upload_3')) ? old('upload_3') :'' }}"
                        @endif>
                @if ($errors->has('upload_3'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('upload_3') }}</small>
                    </p>
                @endif
                @if(isset($the_record))
                    @if($the_record->upload_3 !="")
                        <strong>{{$the_record->upload_3}}</strong>
                        <a href="{{route('report_submission.report.download_dlr_file',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$the_record->upload_3])}}"
                           target="_blank">
                            <span class="fa fa-file"></span> {{$lang['Download']}}
                        </a>
                        <br>
                    @endif
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group{{ $errors->has('upload_3_description') ? ' form-control-warning' : '' }}">
                <label for="upload_3_description">{{$lang['Document 3 Description']}}<span class="required">*</span></label>
                <input type="text" class="form-control" id="upload_3_description" required name="upload_3_description"
                       @if(isset($the_record))
                       value="{{ (old('upload_3_description')) ? old('upload_3_description') : $the_record->upload_3_description }}"
                       @else
                       value="{{ (old('upload_3_description')) ? old('upload_3_description') : '' }}"
                        @endif>
                @if ($errors->has('upload_3_description'))
                    <p class="text-right mb-0">
                        <small class="warning text-muted">{{ $errors->first('upload_3_description') }}</small>
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