@extends('layouts.app')
@push('vendor-styles')
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/toggle/switchery.min.css')}}">
@endpush
{{--@push('other-styles')--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
{{--@endpush--}}
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('user-management.aces')}}">ACEs</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('user-management.aces.profile',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">
                                {{$ace->acronym}}
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('user-management.ace.milestones',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">
                                Milestones
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Milestones {{$dlr_milestone->milestone_no}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card mt-3">
            <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                Milestone {{$dlr_milestone->milestone_no}} : {{$indicator->title}}
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    </ul>
                </div>
            </h6>
            <div class="card-content collapse show">
                <div class="card-body table-responsive">
                    <form action="{{route('user-management.ace.milestone.update',
                    [\Illuminate\Support\Facades\Crypt::encrypt($ace->id),$dlr_milestone->id])}}"
                          method="post">@csrf @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="submission_date"><strong>Milestone Description</strong><span class="required">*</span></label>
                                    <textarea type="text" name="description" id="description" required class="form-control">
                                        {{ old('description',empty($dlr_milestone['description'])?"":$dlr_milestone['description'])}}
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="estimated_cost"><strong>Estimated Cost </strong><span class="required">*</span></label>
                                    <input type="number" name="estimated_cost" id="estimated_cost" min="0" required
                                           step="0.01" class="form-control text-right" value="{{$dlr_milestone->estimated_cost}}">
                                    @if ($errors->has('estimated_cost'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('estimated_cost') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="estimated_earning"><strong>Estimated Earning </strong><span class="required">*</span></label>
                                    <input type="number" name="estimated_earning" id="estimated_earning" min="0"
                                           step="0.01" class="form-control text-right" required
                                           value="{{$dlr_milestone->estimated_earning}}">
                                    @if ($errors->has('estimated_earning'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('estimated_earning') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="start_expected_timeline"><strong>Start Timeline</strong></label>
                                    <input type="date" name="start_expected_timeline" id="start_expected_timeline"
                                           min="1" class="form-control"  value="{{$dlr_milestone->start_expected_timeline}}" >
                                    @if ($errors->has('start_expected_timeline'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('start_expected_timeline') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="end_expected_timeline"><strong>End Timeline </strong><span class="required">*</span></label>
                                    <input type="date" name="end_expected_timeline" id="end_expected_timeline"
                                           class="form-control" required value="{{$dlr_milestone->end_expected_timeline}}">
                                    @if ($errors->has('end_expected_timeline'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('end_expected_timeline') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status"><strong>Status</strong></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="0">Select Status</option>
                                        <option {{($dlr_milestone->status == 1)?"selected":""}} value="1">Pending Submission</option>
                                        <option {{($dlr_milestone->status == 2)?"selected":""}} value="2">Requesting for Verification</option>
                                        <option {{($dlr_milestone->status == 3)?"selected":""}} value="3">Approved after Verification</option>
                                        <option {{($dlr_milestone->status == 4)?"selected":""}} value="4">Not approved after Verification</option>
                                        <option {{($dlr_milestone->status == 5)?"selected":""}} value="5">Resubmit after Verification</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('status') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">Save Information</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card mt-3" id="form-card">
            <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                Milestone Indicators
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    </ul>
                </div>
            </h6>
            <div class="card-content collapse show">
                <div class="card-body table-responsive">
                    <form action="{{route('user-management.ace.milestone.target',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id),
                    $dlr_milestone->id])}}"
                          method="POST">
                        @csrf
                        <input type="hidden" name="target_id" id="target_id" value="">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <input type="text" name="target_indicator" id="target_indicator" required
                                           class="form-control" placeholder="Enter Milestone Target..." value="">
                                    @if ($errors->has('target_indicator'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('target_indicator') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success">Save Target</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped mb-0 reports-table">
                        <thead>
                        <tr>
                            <th width="50px">No.</th>
                            <th>Milestone Targets</th>
                            {{--<th width="100px">Status</th>--}}
                            {{--<th width="100px">Verification</th>--}}
                            <th width="100px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $count = 0; @endphp
                        @foreach($dlr_milestone->targets as $target)
                            @php $count++; $target_indicator = $target->target_indicator@endphp
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$target->target_indicator}}</td>
                                {{--<td>--}}
                                    {{--<input onchange="changeStatus({{$count}})" type="checkbox"--}}
                                           {{--id="active{{$target->id}}" data-toggle="tooltip" data-placement="top"--}}
                                           {{--title="{{($target->status == 0)?'Activate' : 'Deactivate'}}"--}}
                                            {{--class="switchery" data-size="xs" @if($target->status == 1) checked @endif/>--}}
                                {{--</td>--}}
                                {{--<td>{{$target->verification_status}}</td>--}}
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="#form-card" onclick="edit_target(this)"
                                           class="btn btn-s btn-secondary" data-target_id = {{$target->id}}
                                           data-indicator="{{$target->target_indicator}}">Edit</a>
                                        <a href="{{route('user-management.ace.remove_target',[$target->id])}}" class="btn btn-s btn-danger"
                                           data-placement="top" title="Delete Record"
                                           onclick="return confirm('Are you sure you want to delete this record?');">
                                            <i class="ft-trash-2"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/toggle/switchery.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/switch.js')}}" type="text/javascript"></script>
    <script>

        function changeStatus(key){
            // alert()
            document.getElementById('delete-indicator-'+key).submit()
        }

        // $('.select2').select2({
        //     placeholder: "Select Courses",
        //     allowClear: true
        // });
        function edit_target(elem) {
            $('#target_id').val($(elem).data("target_id"));
            $('#target_indicator').val($(elem).data("indicator"));
        }

    </script>
@endpush