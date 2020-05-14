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

                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('ifr_period') ? ' form-control-warning' : '' }}">
                                            <label for="ifr_period">{{$lang['Period covered by IFR']}}<span class="required">*</span></label>
                                            <input type="text" class="form-control" id="ifr_period" required name="ifr_period">
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
                                            <input type="file" class="form-control" id="file_name_1" require name="file_name_1">
                                            @if ($errors->has('file_name_1'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('file_name_1') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('file_name_1_submission') ? ' form-control-warning' : '' }}">
                                            <label for="file_name_1_submission">{{$lang['Date of Submission']}}<span class="required">*</span></label>
                                            <input type="date" class="form-control" id="file_name_1_submission"
                                                   required name="file_name_1_submission" value="{{date('Y-m-d', strtotime(now()))}}">
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
                                            <input type="text" class="form-control" id="efa_period" required name="efa_period">
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
                                            <input type="file" class="form-control" id="file_name_2" require name="file_name_2">
                                            @if ($errors->has('file_name_2'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('file_name_2') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('file_name_2_submission') ? ' form-control-warning' : '' }}">
                                            <label for="file_name_2_submission">{{$lang['Date of Submission']}}<span class="required">*</span></label>
                                            <input type="date" class="form-control" id="file_name_2_submission"
                                                   required name="file_name_2_submission" value="{{date('Y-m-d', strtotime(now()))}}">
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
                            <th style="min-width: 250px">{{$lang['Period covered by IFR']}}</th>
                            <th style="min-width: 250px">IFR {{$lang['File']}}</th>
                            <th>{{$lang['Submission Date']}}</th>
                            <th style="min-width: 250px">{{$lang['Period covered by EFA']}}</th>
                            <th style="min-width: 250px">EFA {{$lang['File']}}</th>
                            <th>{{$lang['Submission Date']}}</th>
                            <th style="min-width: 100px">{{$lang['Action']}}</th>
                        </tr>
                        @foreach($data as $datum)
                            <tr>
                                <td>{{$datum->ifr_period}}</td>
                                <td>
                                    @if($datum->file_name_1 !="")
                                        <a href="{{asset($directory/$datum->file_name_1)}}" target="_blank">
                                            <span class="fa fa-file"></span>{{$datum->file_name_1}}
                                        </a>
                                        <br>
                                    @endif
                                </td>
                                <td>
                                    {{!empty($datum->file_name_1_submission)?date("d/m/Y", strtotime($datum->file_name_1_submission)):"N/A"}}
                                </td>
                                <td>{{$datum->efa_period}}</td>
                                <td>
                                    @if($datum->file_name_2 !="")
                                        <a href="{{asset($directory/$datum->file_name_2)}}" target="_blank">
                                            <span class="fa fa-file"></span>{{$datum->file_name_2}}
                                        </a>
                                        <br>
                                    @endif
                                </td>
                                <td>
                                    {{!empty($datum->file_name_2_submission)?date("d/m/Y", strtotime($datum->file_name_2_submission)):"N/A"}}
                                </td>
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