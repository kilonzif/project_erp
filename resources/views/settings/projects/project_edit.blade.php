
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
                <h4 class="card-title" id="basic-layout-form">Edit Projects</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>

                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
        <form class="form" action="{{route('settings.projects.update',['id' => $projects->id])}}" method="post">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title<span class="required"> *</span></label>
                                        <input type="text" id="title"  required min="3" class="form-control" 
                                               name="title"   value="{{ $projects->title }}">



                                                @if ($errors->has('title'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('title') }}</small>
                                                </p>
                                            @endif
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="project_coordinator">Project Coordinator<span class="required"> *</span></label>
                                        <input type="text" id="project_coordinator"  required min="2"class="form-control" 
                                               name="project_coordinator" value="{{ $projects->project_coordinator }}">



                                                @if ($errors->has('project_coordinator'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('project_coordinator') }}</small>
                                                </p>
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="grant_id">Grant Id<span class="required"> *</span></label>
                                        <input type="text" id="grant_id"  required min="1" class="form-control" 
                                               name="grant_id" value="{{ $projects->grant_id }}">

                                               @if ($errors->has('grant_id'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('grant_id') }}</small>
                                                </p>
                                            @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_grant">Total Grant<span class="required"> *</span></label>
                                        <input type="text" id="total_grant"   required min="1" class="form-control" 
                                               name="total_grant" value="{{ $projects->total_grant }}">


                                               @if ($errors->has('total_grant'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('total_grant') }}</small>
                                                </p>
                                            @endif
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
                                                <input type="date" class="form-control " id="date-mask"  name="start_date" value="{{ $projects->start_date }}"
                                                />


                                               @if ($errors->has('start_date'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('start_date') }}</small>
                                                </p>
                                            @endif
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
                                                <input type="date" class="form-control " id="date-mask"    name="end_date"  value="{{ $projects->end_date }}"
                                                />

                                                @if ($errors->has('end_date'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('end_date') }}</small>
                                                </p>
                                            @endif
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