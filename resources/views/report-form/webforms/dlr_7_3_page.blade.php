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
                                @include('report-form.webforms.dlr_7_3_form')
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
                                <th style="width: 50px">#</th>
                                <th style="min-width: 150px">{{lang('Type of Accreditation',$lang)}}</th>
                                <th style="min-width: 200px">{{lang('Accreditation Agency',$lang)}}</th>
                                <th style="min-width: 200px">{{lang('Accreditation Reference',$lang)}}</th>
                                <th style="min-width: 150px">{{lang('Name of Contact Person',$lang)}}</th>
                                <th style="min-width: 150px">{{lang('Email of Contact Person',$lang)}}</th>
                                <th style="min-width: 100px">{{lang('Phone Number of Contact Person',$lang)}}</th>
                                <th style="min-width: 100px">{{lang('Date of Accreditation',$lang)}}</th>
                                <th style="min-width: 100px">{{lang('Expiry date of Accreditation',$lang)}}</th>
                                <th style="min-width: 200px">{{lang('Action',$lang)}}</th>
                            </tr>
                            @php $count = 1; @endphp
                            @foreach($data as $datum)
                                <tr>
                                    <td>{{$count++}}</td>
                                    <td>{{$datum->typeofaccreditation}}</td>
                                    <td>{{$datum->accreditationreference}}</td>
                                    <td>{{$datum->accreditationagency}}</td>
                                    <td>{{$datum->contactname}}</td>
                                    <td>{{$datum->contactemail}}</td>
                                    <td>{{$datum->contactphone}}</td>
                                    <td>{{!empty($datum->dateofaccreditation) ? date("d/m/Y", strtotime($datum->dateofaccreditation)):"N/A"}}</td>
                                    <td>{{!empty($datum->exp_accreditationdate) ? date("d/m/Y", strtotime($datum->exp_accreditationdate)):"N/A"}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                        <a href="#form-card" onclick="editRecord('{{$indicator_info->id}}','{{$datum->id}}')" class="btn btn-s btn-secondary">
                                            {{__('Edit')}}</a>
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