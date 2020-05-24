{{--<div class="row" id="dlrs-div">--}}
    {{--<div class="col-12">--}}
        {{--<div class="card">--}}
            {{--<div class="card-content">--}}
                {{--<div class="card-body">--}}
                    {{--<table class="table table-striped table-bordered indicators-details" id="indicators_table">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                            {{--<th>Indicator</th>--}}
                            {{--<th style="width: 200px;">Created Date</th>--}}
                            {{--<th style="width: 200px;">Modified Date</th>--}}
                            {{--<th style="width: 50px;">Action</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--@foreach($indicator_details as $indicator_detail)--}}
                            {{--<tr>--}}
                                {{--<td>--}}
                                    {{--@php--}}
                                        {{--$indicator_iden = \App\Indicator::where('id','=',$indicator_detail->indicator_id)->pluck('title')->first();--}}
                                    {{--@endphp--}}
                                    {{--{{$indicator_iden}}--}}
                                {{--</td>--}}
                                {{--<td>{{$indicator_detail->created_at}}</td>--}}
                                {{--<td>{{$indicator_detail->created_at}}</td>--}}
                                {{--<td>--}}
                                    {{--<div class="btn-group" role="group" aria-label="Basic example">--}}
                                        {{--<a href="{{route('report_submission.view_indicator_details',[$indicator_detail->id])}}" disabled class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="View Indicator Details"><i class="ft-eye"></i></a></a>--}}
                                    {{--</div>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    {{--</table>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
<div class="row" id="dlrs-div">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <table class="table table-striped table-bordered indicators-details" id="indicators_table">
                        <thead>
                        <tr>
                            <th>Indicator</th>
                            <th style="width: 200px;">Created Date</th>
                            <th style="width: 50px;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{$report->indicator->title}}
                            </td>
                            <td>{{date('d/m/Y', strtotime($report->created_at))}}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{route('report_submission.view_indicator_details',
                                                    [$report->id])}}" disabled class="btn btn-s btn-secondary"
                                       data-toggle="tooltip" data-placement="top"
                                       title="View Indicator Details"><i class="ft-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>