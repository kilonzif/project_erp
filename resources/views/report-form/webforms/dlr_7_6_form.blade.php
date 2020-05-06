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

                        </tr>
                        @foreach($data as $datum)
                            <tr>

                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection