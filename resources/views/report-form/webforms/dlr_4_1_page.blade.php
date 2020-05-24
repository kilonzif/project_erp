@extends('report-form.webforms.webform')
@section('web-form')
<div class="row">
    @if($report->editable)
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header p-1 card-head-inverse bg-teal">
                {{$indicator_info->identifier}} : {{$indicator_info->title}}
            </h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div id="form-card">
                            @include('report-form.webforms.dlr_4_1_form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header p-1 card-head-inverse bg-primary">
                {{lang('Saved Records',$report->language)}}
            </h5>
            <div class="card-content">
                <div class="card-body">
                    <div class="col-md-12 table-responsive">

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th style="width: 200px">{{lang('Program Title',$report->language)}}</th>
                                <th>{{lang('Accreditation Level',$report->language)}}</th>
                                <th>{{lang('Accreditation Type',$report->language)}}</th>
                                <th>{{lang('Accreditation Reference',$report->language)}}</th>
                                <th style="width: 200px">{{lang('Accreditation Agency',$report->language)}}</th>
                                <th style="width: 150px">{{lang('Agency Contact Name',$report->language)}}</th>
                                <th>{{lang('Email',$report->language)}}</th>
                                <th>{{lang('Phone Number',$report->language)}}</th>
                                <th>{{lang('Date of Accreditation',$report->language)}}</th>
                                <th>{{lang('Expiry Date of Accreditation',$report->language)}}</th>
                                <th>{{lang('Newly Accredited',$report->language)}}</th>
                                <th style="min-width: 180px">{{lang('Action',$report->language)}}</th>
                            </tr>
                            @foreach($data as $datum)

                                <tr>
                                    <td>{{$datum ->programmetitle}}</td>
                                    <td>{{$datum ->level}}</td>
                                    <td>{{$datum ->typeofaccreditation}}</td>
                                    <td>{{$datum ->accreditationreference}}</td>
                                    <td>{{$datum ->accreditationagency}}</td>
                                    <td>{{$datum ->agencyname}}</td>
                                    <td>{{$datum ->agencyemail}}</td>
                                    <td>{{$datum ->agencycontact}}</td>
                                    <td>{{date("d/m/Y", strtotime($datum ->dateofaccreditation))}}</td>
                                    <td>{{date("d/m/Y", strtotime($datum ->exp_accreditationdate))}}</td>
                                    <td>
                                        @isset($datum ->newly_accredited_programme)
                                            {{$datum ->newly_accredited_programme}}
                                        @endisset
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                        <a href="#form-card" onclick="editRecord('{{$indicator_info->id}}','{{$datum ->id}}')" class="btn btn-s btn-secondary">
                                            {{lang('Edit',$report->language)}}</a>
                                        <a href="{{route('report_submission.web_form_remove_record',
                                        [\Illuminate\Support\Facades\Crypt::encrypt($indicator_info->id),$datum ->id])}}"
                                           class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top"
                                           onclick="return confirm('{{lang('Are you sure you want to delete this record?',$report->language)}}');"
                                           title="{{lang('Delete Record',$report->language)}}"><i class="ft-trash-2"></i></a>
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
</div>

@endsection