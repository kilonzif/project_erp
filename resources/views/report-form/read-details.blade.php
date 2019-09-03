
@extends('layouts.app')
@push('vendor-styles')
{{--    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/extended/form-extended.css')}}">--}}

    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endpush
@push('other-styles')

{{--    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-tooltip.css')}}">--}}
@endpush

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Report Indicator Details</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Report Indicator Details
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="mb-1">
            <a class="btn btn-dark square text-left mr-3" href="{{ \Illuminate\Support\Facades\URL::previous() }}">
                <i class="ft-arrow-left mr-sm-1"></i>{{__('Go Back')}}
            </a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Indicator - {{$indicator->identifier}} Information</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered indicator-info">
                                    <thead>
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        @foreach($headers as $key=>$header)
                                            <th>{{$header}}</th>
                                        @endforeach
                                        <th style="width: 50px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach($indicator_details->data as $indicator_detail)
                                        {{--{{var_dump($slugs)}}--}}
                                    <tr>
                                        <td>{{$counter++}}</td>
                                        @foreach($slugs as $slug)
                                            @if(isset($indicator_detail[$slug]))
                                                <td>{{$indicator_detail[$slug]}}</td>
                                            @else
                                                <td>N/A</td>
                                            @endif
                                        @endforeach

                                    {{--<td>{{$project->title}}</td>--}}
                                    {{--<td>{{date('M d, Y',strtotime($project->start_date))}}</td>--}}
                                    {{--<td>{{date('M d, Y',strtotime($project->end_date))}}</td>--}}
                                    <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                    {{--<a href="{{ route('settings.projects.view', [Crypt::encrypt($project->id)]) }}" class="btn btn-s btn-dark" data-toggle="tooltip" data-placement="top" title="View Project"><i class="ft-eye"></i></a>  </a>--}}

                                    <a href="#" class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Indicator Details"><i class="ft-edit-3"></i></a></a>
                                    {{--<a href="#" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Project"><i class="ft-trash-2"></i></a></a>--}}
                                    {{--  <a class="dropdow-item btn {{($user->status == 0)?'btn-success' : 'btn-danger'}} btn-s" href="#"--}}
                                    {{--onclick="event.preventDefault();--}}
                                    {{--document.getElementById('delete-form-{{$count}}').submit();">--}}
                                    {{--@if($user->status == 0)--}}
                                    {{--{{ __('Activate') }}--}}
                                    {{--@else--}}
                                    {{--{{ __('Deactivate') }}--}}
                                    {{--@endif--}}
                                    {{--</a> --}}
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
    </div>

@endsection
@push('vendor-script')

@endpush


@push('vendor-script')
{{--    <script src="{{asset('vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
{{--    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>--}}
@endpush
@push('end-script')
{{--    <script src="{{asset('js/scripts/forms/extended/form-inputmask.js')}}" type="text/javascript"></script>--}}
    <script>
        $('.indicator-info').DataTable({
            // responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'print','colvis'
            ]
        });
        $('.buttons-print, .buttons-excel').addClass('btn btn-primary square mr-1');
    </script>
@endpush