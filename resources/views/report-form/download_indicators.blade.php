@extends('layouts.app')
@push('vendor-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endpush
@push('other-styles')
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Reports</a>
                        </li>
                        <li class="breadcrumb-item active">Download Indicators Templates
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="mb-1">
            <a class="btn btn-dark square text-left mr-3" href="{{\Illuminate\Support\Facades\URL::previous()}}">
                <i class="ft-arrow-left mr-sm-1"></i>{{__('Back to Report')}}
            </a>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header p-1 card-head-inverse bg-teal">
                        Select and Download Template
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="label-control">Download All the Templates: <span class="fa fa-file-zip-o"></span></label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{ url('download?file_path=/public/English_indicatorTemplates.zip') }}"
                                           class="btn btn-s btn-outline-secondary mb-2">
                                            <i class="fa fa-cloud-download"></i> English <span class="flag-icon flag-icon-gb"></span>
                                        </a>
                                        {{--<a type="submit" href="{{route('settings.excelupload.downloadall_eng')}}"--}}
                                           {{--class="btn btn-s btn-outline-secondary mb-2">--}}
                                            {{--<i class="fa fa-cloud-download"></i> English <span class="flag-icon flag-icon-gb"></span>--}}
                                        {{--</a>--}}
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                     <div class="form-group">
                                         <a href="{{ url('download?file_path=/public/French_indicatorTemplates.zip') }}"
                                        class="btn btn-s btn-outline-secondary mb-2">
                                         <i class="fa fa-cloud-download"></i> French <span class="flag-icon flag-icon-fr"></span>
                                     </a>
                                     {{--<a type="submit" href="{{route('settings.excelupload.downloadall_fr')}}"--}}
                                        {{--class="btn btn-s btn-outline-secondary mb-2">--}}
                                         {{--<i class="fa fa-cloud-download"></i> French <span class="flag-icon flag-icon-fr"></span>--}}
                                     {{--</a>--}}
                                     </div>
                                 </div>
                            </div>

                            <table class="table table-bordered table-striped" id="templates_table">
                                <tr>
                                    <th>Indicator Identifier</th>
                                    <th>Identifier Name</th>
                                    <th>Download</th>
                                </tr>

                                <tbody>


                                @foreach($indicators as $indicator)
                                    @if($indicator->IsUploadable($indicator->id))

                                        @php
                                            $excel_uploads =\App\ExcelUpload::where('indicator_id','=',(integer)$indicator->id)
                                            ->orderBy('language', 'asc')
                                            ->get();
                                        @endphp


                                        <tr>
                                        <td value="{{$indicator->id}}"> {{$indicator->identifier}}</td>
                                            <td value="{{$indicator->id}}"> {{$indicator->title}}</td>
                                            <td>
                                                @if($excel_uploads)
                                                   @foreach($excel_uploads as $i)
                                                    @if($i->language=="english")
                                                        <a href="{{ route('settings.excelupload.downloadEn',  [\Illuminate\Support\Facades\Crypt::encrypt($i->id)] ) }}"
                                                           class="btn btn-s btn-outline-secondary mb-2">
                                                            <i class="fa fa-cloud-download"></i> English  <span class="flag-icon flag-icon-gb"></span>
                                                        </a>
                                                    @endif
                                                        @if($i->language=="french")
                                                        <a href="{{ route('settings.excelupload.downloadFr',  [\Illuminate\Support\Facades\Crypt::encrypt($i->id)] ) }}"
                                                           class="btn btn-s btn-outline-secondary mb-2">
                                                            <i class="fa fa-cloud-download"></i> French  <span class="flag-icon flag-icon-fr"></span>
                                                        </a>
                                                    @endif

                                                    @endforeach

                                                @endif
                                            </td>
                                        </tr>
                                    @endif
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
    <script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script>
        $('#templates_table').dataTable( {
            "ordering": false
        } );
    </script>
@endpush
