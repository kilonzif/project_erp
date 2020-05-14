@extends('report-form.webforms.webform')
@section('web-form')
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <h5 class="card-header p-1 card-head-inverse bg-teal">
                    {{$indicator_info->identifier}} : {{$indicator_info->title}}
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" >
                            <div id="form-card">
                                <form action="{{route('report_submission.save_webform',[$indicator_info->id])}}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" name="report_id" value="{{$d_report_id}}">
                                        <input type="hidden" name="indicator_id" value="{{$indicator_info->id}}">
                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('guideline_file') ? ' form-control-warning' : '' }}">
                                                <label for="file_name_2">{{$lang['Guideline File']}}<span class="required">*</span></label>
                                                <input type="file" class="form-control" id="guideline_file" require name="guideline_file">
                                                @if ($errors->has('guideline_file'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('guideline_file') }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('members_file') ? ' form-control-warning' : '' }}">
                                                <label for="file_name_2">{{$lang['Members File']}}<span class="required">*</span></label>
                                                <input type="file" class="form-control" id="members_file" require name="members_file">
                                                @if ($errors->has('members_file'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('members_file') }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('report_file') ? ' form-control-warning' : '' }}">
                                                <label for="file_name_2">{{$lang['Report File']}}<span class="required">*</span></label>
                                                <input type="file" class="form-control" id="report_file" require name="report_file">
                                                @if ($errors->has('report_file'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('report_file') }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('report_file') ? ' form-control-warning' : '' }}">
                                                <label for="file_name_2">{{$lang['Audited Account File']}}<span class="required">*</span></label>
                                                <input type="file" class="form-control" id="report_file" require name="audited_account_file">
                                                @if ($errors->has('audited_account_file'))
                                                    <p class="text-right mb-0">
                                                        <small class="warning text-muted">{{ $errors->first('audited_account_file') }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-12">
                                            <button type="submit" class="btn btn-secondary square" style="margin-top: 20px"><i class="fa fa-save"></i> {{$lang['Save']}} </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h6 class="card-header p-1 card-head-inverse bg-primary">
                    Saved Records
                </h6>
                <div class="card-content">
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th style="min-width: 280px">{{$lang['Guideline File']}}</th>
                                <th style="min-width: 280px">{{$lang['Members File']}}</th>
                                <th style="min-width: 280px">{{$lang['Report File']}}</th>
                                <th style="min-width: 280px">{{$lang['Audited Account File']}}</th>
                                <th style="min-width: 250px">{{$lang['Action']}}</th>
                            </tr>
                            @foreach($data as $datum)
                                <tr>
                                    <td>
                                        @if($datum->guideline_file !="")
                                            <a href="{{asset($directory.'/'.$datum->guideline_file)}}" target="_blank">
                                                <span class="fa fa-file"></span>{{$datum->guideline_file}}
                                            </a>
                                            <br>
                                        @endif
                                    <td>
                                        @if($datum->members_file !="")
                                            <a href="{{asset($directory.'/'.$datum->members_file)}}" target="_blank">
                                                <span class="fa fa-file"></span>{{$datum->members_file}}
                                            </a>
                                            <br>
                                        @endif
                                    </td>
                                    <td>
                                        @if($datum->report_file !="")
                                            <a href="{{asset($directory.'/'.$datum->report_file)}}" target="_blank">
                                                <span class="fa fa-file"></span>{{$datum->report_file}}
                                            </a>
                                            <br>
                                        @endif
                                    <td>
                                        @if($datum->audited_account_file !="")
                                            <a href="{{asset($directory.'/'.$datum->audited_account_file)}}" target="_blank">
                                                <span class="fa fa-file"></span>{{$datum->audited_account_file}}
                                            </a>
                                            <br>
                                        @endif
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#form-card" onclick="editRecord('{{$indicator_info->id}}','{{$datum->id}}')" class="btn btn-s btn-secondary">
                                                {{$lang['Edit']}}</a>
                                            <a href="{{route('report_submission.web_form_remove_record',[\Illuminate\Support\Facades\Crypt::encrypt($indicator_info->id),$datum->id])}}"
                                               class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this record?');"
                                               title="Delete Record"><i class="ft-trash-2"></i></a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection