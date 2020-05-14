@extends('report-form.webforms.webform')
@section('web-form')
    <div class="row">
        <div class="col-md-12">

            @if($report->editable)
                <div class="card">
                    <h5 class="card-header p-1 card-head-inverse bg-teal">
                        {{$indicator_info->identifier}} : {{$indicator_info->title}}
                    </h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12" >
                                <div id="form-card">
                                    @include('report-form.webforms.dlr_6_2_form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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
                                            Guideline File -
                                            @if($datum->guideline_file !="")
                                                @if(is_file(public_path($directory.'/'.$datum->guideline_file)))
                                                <a href="{{asset($directory.'/'.$datum->guideline_file)}}" target="_blank">
                                                    <span class="fa fa-file"></span>{{$datum->guideline_file}}
                                                </a>
                                                @endif
                                                {{$datum->guideline_file}}
                                                <br>
                                        @endif
                                        <td>
                                            Members File -
                                        @if($datum->members_file !="")
                                                @if(is_file(public_path($directory.'/'.$datum->members_file)))
                                                <a href="{{asset($directory.'/'.$datum->members_file)}}" target="_blank">
                                                    <span class="fa fa-file"></span>{{$datum->members_file}}
                                                </a>
                                                @endif
                                               {{$datum->members_file}}
                                                <br>
                                            @endif
                                        </td>
                                        <td>
                                            Report File -
                                            @if($datum->report_file !="")
                                                @if(is_file(public_path($directory.'/'.$datum->report_file)))
                                                <a href="{{asset($directory.'/'.$datum->report_file)}}" target="_blank">
                                                    <span class="fa fa-file"></span>{{$datum->report_file}}
                                                </a>
                                                @endif
                                            {{$datum->report_file}}
                                                <br>
                                        @endif
                                        <td>
                                            Audited Account File -
                                            @if($datum->guideline_file !="")
                                                @if(is_file(public_path($directory.'/'.$datum->audited_account_file)))
                                                <a href="{{asset($directory.'/'.$datum->audited_account_file)}}" target="_blank">
                                                    <span class="fa fa-file"></span>{{$datum->audited_account_file}}
                                                </a>
                                                @endif
                                            {{$datum->audited_account_file}}
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