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
                                    {{$datum->guideline_file}}
                                    <td>
                                        Members File -
                                        {{$datum->members_file}}

                                    </td>
                                    <td>
                                        Report File -

                                    {{$datum->report_file}}

                                    <td>
                                        Audited Account File -

                                    {{$datum->audited_account_file}}

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