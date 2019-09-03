@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-tooltip.css')}}">
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Unit Measures</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Settings
                        </li>
                        <li class="breadcrumb-item active">Unit Measures
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" id="basic-layout-form">Add Unit Measure</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>

                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
                    <form class="form">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="projectinput1">Title</label>
                                        <input type="text" id="projectinput1" class="form-control" placeholder="Title"
                                               name="fname">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="projectinput2">Order Number</label>
                                        <input type="text" id="projectinput2" class="form-control" placeholder="Order Number"
                                               name="lname">
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

        <section id="configuration">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">List of Unit Measures</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <table class="table table-striped table-bordered all_measures">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Order</th>
                                        <th style="width: 40px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($unit_measures as $unit_measure)
                                        <tr>
                                            <td>{{$unit_measure->title}}</td>
                                            <td>{{$unit_measure->order_no}}</td>
                                            <td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="#" class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Indicator"><i class="ft-edit-3"></i></a></a>
                                                    {{--<a href="#" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Indicator"><i class="ft-trash-2"></i></a></a>--}}
                                                    {{--<a class="dropdow-item btn {{($user->status == 0)?'btn-success' : 'btn-danger'}} btn-s" href="#"--}}
                                                    {{--onclick="event.preventDefault();--}}
                                                    {{--document.getElementById('delete-form-{{$count}}').submit();">--}}
                                                    {{--@if($user->status == 0)--}}
                                                    {{--{{ __('Activate') }}--}}
                                                    {{--@else--}}
                                                    {{--{{ __('Deactivate') }}--}}
                                                    {{--@endif--}}
                                                    {{--</a>--}}
                                                </div>
                                            </td>
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
        </section>
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')

    <script>
        $('.all_measures').dataTable();
    </script>
@endpush