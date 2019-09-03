
@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/extended/form-extended.css')}}">

     <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endpush
@push('other-styles')

    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-tooltip.css')}}">
@endpush

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Projects</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Settings
                        </li>
                        <li class="breadcrumb-item active">Projects
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" id="basic-layout-form">Add Projects</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>

                    </ul>
                </div>
            </div>
             
            <div class="card-content collapse show">
                <div class="card-body">
                    <form class="form" action="{{route('settings.projects.save')}}" method="post">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="projectinput1">Title<span class="required"> *</span></label>
                                        <input type="text" id="projectinput1"  required min="3" class="form-control" placeholder="Title"
                                               name="title">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="projectinput2">Project Coordinator<span class="required"> *</span></label>
                                        <input type="text" id="projectinput2"  required min="2"class="form-control" placeholder="Project Coordinator"
                                               name="project_coordinator">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="projectinput1">Grant Id<span class="required"> *</span></label>
                                        <input type="text" id="projectinput1"  required min="1" class="form-control" placeholder="Grant Id"
                                               name="grant_id">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="projectinput2">Total Grant<span class="required"> *</span></label>
                                        <input type="text" id="projectinput2"   required min="1" class="form-control" placeholder="Total Grant"
                                               name="total_grant">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <fieldset>
                                            <h5>Project Start Date<span class="required"> *</span>

                                            </h5>
                                            <div class="form-group">
                                                <input type="date" class="form-control " id="date-mask" placeholder=" Date" name="start_date"
                                                />
                                            </div>
                                        </fieldset>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <fieldset>
                                            <h5>Project End Date<span class="required"> *</span>

                                            </h5>
                                            <div class="form-group">
                                                <input type="date" class="form-control " id="date-mask" placeholder="Date"   name="end_date"
                                                />
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

     <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Projects</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <table class="table table-striped table-bordered all_indicators">
                                <thead>
                                <tr>
                                    {{--<th style="width: 30px;">No.</th>--}}
                                    <th >Projects Title</th>
                                    <th style="">Start Date</th>
                                     <th style="">End Date</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            {{--<td>{{$indicator->number}}</td>--}}
                                            <td>{{$project->title}}</td>
                                            <td>{{date('M d, Y',strtotime($project->start_date))}}</td>
                                            <td>{{date('M d, Y',strtotime($project->end_date))}}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{ route('settings.projects.view', [Crypt::encrypt($project->id)]) }}" class="btn btn-s btn-dark" data-toggle="tooltip" data-placement="top" title="View Project"><i class="ft-eye"></i></a>  </a>

                                                    <a href="{{ route('settings.projects.edit', [Crypt::encrypt($project->id)]) }}" class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Project"><i class="ft-edit-3"></i></a></a>
                                                    <a href="#" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Project"><i class="ft-trash-2"></i></a></a>
                                                   {{--  <a class="dropdow-item btn {{($user->status == 0)?'btn-success' : 'btn-danger'}} btn-s" href="#"
                                                       onclick="event.preventDefault();
                                                               document.getElementById('delete-form-{{$count}}').submit();">
                                                        @if($user->status == 0)
                                                            {{ __('Activate') }}
                                                        @else
                                                            {{ __('Deactivate') }}
                                                        @endif
                                                    </a> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
    </div>

@endsection
@push('vendor-script')
    
@endpush


@push('vendor-script')
   <script src="{{asset('vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>

    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>


@endpush
@push('end-script')

   <script src="{{asset('js/scripts/forms/extended/form-inputmask.js')}}" type="text/javascript"></script>


    <script>
        $('.select2').select2({
            placeholder: "Select a Unit of Measure",
            allowClear: true
        });
        $('.all_indicators').dataTable();
    </script>
@endpush