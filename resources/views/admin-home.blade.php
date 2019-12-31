@extends('layouts.app')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item active">Dashboard
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <!-- Stats -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-12">
                <a href="{{route('user-management.users')}}">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch">
                                <div class="p-2 text-center bg-primary bg-darken-2">
                                    <i class="icon-users font-large-2 white"></i>
                                </div>
                                <div class="p-2 bg-gradient-x-primary white media-body">
                                    <h5>Users</h5>
                                    <h5 class="text-bold-400 mb-0"> {{$users}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <a href="{{route('report_submission.reports')}}">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch">
                                <div class="p-2 text-center bg-danger bg-darken-2">
                                    <i class="icon-layers font-large-2 white"></i>
                                </div>
                                <div class="p-2 bg-gradient-x-danger white media-body">
                                    <h5>Reports</h5>
                                    <h5 class="text-bold-400 mb-0"> {{$reports}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <a href="{{route('report_submission.reports')}}">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch">
                                <div class="p-2 text-center bg-success bg-darken-2">
                                    <i class="icon-doc font-large-2 white"></i>
                                </div>
                                <div class="p-2 bg-gradient-x-success white media-body">
                                    <h5>New Reports</h5>
                                    <h5 class="text-bold-400 mb-0"><i class="ft-plus"></i> {{$new_reports}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <a href="{{route('user-management.institutions')}}">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch">
                                <div class="p-2 text-center bg-cyan bg-darken-2">
                                    <i class="icon-globe font-large-2 white"></i>
                                </div>
                                <div class="p-2 bg-gradient-x-cyan white media-body">
                                    <h5>Institutions</h5>
                                    <h5 class="text-bold-400 mb-0">{{$institutions}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>


        {{--<div class="row">--}}
            {{--<div class="col-md-6">--}}
                {{--<div class="card">--}}
                    {{--<div class="card-header">--}}
                        {{--<h4 class="card-title">Report Status</h4>--}}
                        {{--<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>--}}
                        {{--<div class="heading-elements">--}}
                            {{--<ul class="list-inline mb-0">--}}
                                {{--<li><a data-action="collapse"><i class="ft-minus"></i></a></li>--}}
                                {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-content collapse show">--}}
                        {{--<div class="card-body">--}}

                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
@endsection
