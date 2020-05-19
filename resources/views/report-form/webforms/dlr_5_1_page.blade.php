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
                            @include('report-form.webforms.dlr_5_1_form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-md-12">
        <div class="card">
                <h6 class="card-header p-1 card-head-inverse bg-primary">
                    Saved Records
                </h6>
                <div class="card-content">
                    <div class="card-body table-responsive">
                        <table class="table table-scrollable table-striped table-bordered">
                            <tr>
                                <th style="min-width: 30px">#</th>
                                <th style="min-width: 120px">{{$lang['Amount (USD)']}}</th>
                                <th style="min-width: 120px">{{$lang['Original Amount']}}</th>
                                <th style="min-width: 120px">{{$lang['Original Amount Currency']}}</th>
                                <th style="min-width: 250px">{{$lang['Source']}}</th>
                                <th style="min-width: 120px">{{$lang['Date of Receipt']}}</th>
                                <th style="min-width: 250px">{{$lang['Account Details']}}</th>
                                <th style="min-width: 120px">{{$lang['Region']}}</th>
                                <th style="min-width: 250px">{{$lang['Purpose of Funds']}}</th>
                                @if($report->editable)
                                <th style="min-width: 180px">{{$lang['Action']}}</th>
                                @endif
                            </tr>
                            @php $counter=0; @endphp
                            @foreach($data as $datum)
                                @php $counter++; @endphp
                                <tr>
                                    <td>{{$counter}}</td>
                                    <td>{{number_format($datum->amountindollars,2)}}</td>
                                    <td>{{number_format($datum->originalamount,2)}}</td>
                                    <td>{{$datum->currency}}</td>
                                    <td>{{$datum->source}}</td>
                                    <td>{{date("d/m/Y", strtotime($datum->datereceived))}}</td>
                                    <td>{{$datum->bankdetails}}</td>
                                    <td>{{$datum->region}}</td>
                                    <td>{{$datum->fundingreason}}</td>
                                    @if($report->editable)
                                    <td>
                                        <div class="btn-group" role="group">
                                        <a href="#form-card" onclick="editRecord('{{$indicator_info->id}}','{{$datum->id}}')" class="btn btn-s btn-secondary">
                                            {{__('Edit')}}</a>
                                        <a href="{{route('report_submission.web_form_remove_record',[\Illuminate\Support\Facades\Crypt::encrypt($indicator_info->id),$datum->id])}}"
                                           class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this record?');"
                                           title="Delete Record"><i class="ft-trash-2"></i></a>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>

@endsection