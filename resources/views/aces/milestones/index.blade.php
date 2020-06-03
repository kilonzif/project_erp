@extends('layouts.app')
{{--@push('vendor-styles')--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
{{--@endpush--}}
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
                        <li class="breadcrumb-item active">Milestones
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <button type="button" class="btn btn-secondary btn-square mb-4" data-toggle="modal" data-target="#animation">
            <i class="ft-plus"></i> New Milestone
        </button>
        @if($dlr_milestone_indicators->count() > 0)
            @foreach($dlr_milestone_indicators as $milestone_indicator)
                <div class="card mb-3">
                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                        {{$milestone_indicator->title}}
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </h6>
                    <div class="card-content collapse show">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped mb-0 reports-table">
                                <thead>
                                <tr>
                                    <th width="100px">No.</th>
                                    <th>Description</th>
                                    <th width="150px">Status</th>
                                    <th width="150px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($milestone_indicator->getMilestones as $milestone)
                                    <tr>
                                    <td>{{$milestone->milestone_no}}</td>
                                    <td>{{$milestone->description}}</td>
                                    <td>{{$milestone->status}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{route('user-management.ace.milestone.edit',
                                            [\Illuminate\Support\Facades\Crypt::encrypt($ace->id),$milestone->id])}}"
                                               class="btn btn-s btn-secondary">Edit</a>
                                            <a href="{{route('user-management.ace.milestone.delete',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id),$milestone->id])}}" class="btn btn-s btn-danger" data-toggle="tooltip"
                                               data-placement="top" title="Delete Record"
                                               onclick="return confirm('Are you sure you want to delete this record?');">
                                                <i class="ft-trash-2"></i></a>
                                        </div>
                                    </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <h1 class="text-center mt-5">No Milestones have been set</h1>
        @endif

        <!-- Modal -->
        <div class="modal text-left" id="animation" tabindex="-1" role="dialog" aria-labelledby="milestoneForm_3"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="milestoneForm_3">Add Milestone</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('user-management.ace.milestone',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}"
                            method="post" enctype="multipart/form-data">@csrf
                            <div class="row">
                                {{--<input type="hidden" name="report_id" value="{{$report->id}}">--}}
                                {{--<input type="hidden" name="indicator_id" value="{{$indicator_info->id}}">--}}
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="indicator">DLR Indicator<span class="required">*</span></label>
                                        <select name="indicator" id="indicator" class="form-control">
                                            <option value="">Select DLR</option>
                                            @foreach($dlr_milestone_indicators as $indicator)
                                                <option value="{{$indicator->id}}">{{$indicator->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="submission_date">Milestone No.<span class="required">*</span></label>
                                        <input type="number" name="milestone_no" id="milestone_no" min="1" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="submission_date">Milestone Description<span class="required">*</span></label>
                                        <textarea type="text" name="description" id="description" required rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">Save Milestone</button>
                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{--@push('vendor-script')--}}
    {{--<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>--}}
    {{--<script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>--}}
{{--@endpush--}}
{{--@push('end-script')--}}
    {{--<script src="{{asset('js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>--}}
    {{--<script>--}}

        {{--$('.select2').select2({--}}
            {{--placeholder: "Select Courses",--}}
            {{--allowClear: true--}}
        {{--});--}}

    {{--</script>--}}
{{--@endpush--}}