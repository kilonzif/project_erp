@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">--}}

    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
@endpush
@push('other-styles')
    {{--<style>--}}
        {{--table#generalReporting td{--}}
            {{--font-size: 0.9rem;--}}
            {{--padding: 0.4rem 0.75rem;--}}
        {{--}--}}
    {{--</style>--}}
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
@endpush
@section('content')
    {{--@php dd(old('indicator.3')) @endphp--}}
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('user-management.aces')}}">ACEs</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{route('user-management.aces.profile',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">
                                {{$ace->acronym}}</a>
                        </li>
                        <li class="breadcrumb-item active">DLR COST - {{$year}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <a href="{{route('user-management.aces.profile',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}"
           class="btn btn-secondary square mb-1">Go Back</a>
        <div class="row">
            <div class="col-12">
                <div class="card mb-1">
                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                        DLR Unit & Maximum Costs - {{$year}}
                    </h6>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="table-responsive" style="padding: 0 1.2rem;">
                                <form action="{{route('user-management.ace.dlr_cost.save',
                                [\Illuminate\Support\Facades\Crypt::encrypt($ace->id),$year])}}" method="POST">
                                    @csrf
                                    @include('aces.dlr-costs-table')
                                    <button type="submit" class="btn btn-secondary">Save Values</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
@endpush


