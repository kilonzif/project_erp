@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/extended/form-extended.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-tooltip.css')}}">
@endpush

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Excel Upload</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Settings
                        </li>
                        <li class="breadcrumb-item active">Excel Upload
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-md-5">
                <div class="card" id="action-card">
                    <div class="card-header">
                        <h4 class="card-title">Add file</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form class="form" action="{{route('settings.excelupload.save')}}" enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="form-body">
                                    <label for="indicator_id">indicator <span class="required">*</span></label>
                                    <select name="indicator_id" id="indicator_id" class=" form-control" required>
                                        <option value="">Select indicator</option>
                                        @foreach($indicators as $indicator)
                                            <option value="{{$indicator->id}}">Indicator {{$indicator->identifier}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('indicator_id'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('indicator_id') }}</small>
                                        </p>
                                    @endif
                                </div>
                                <br>
                                <div class="form-body">
                                     <div class="form-group">
                                        <fieldset class="form-group">
                                            <label for="upload_file">Browse File <span class="warning text-muted">{{__(' Excel (.xlsx) files only')}}</span></label>
                                            <input type="file" style="padding: 8px;" required class="form-control" name="upload_file" id="upload_file">
                                            @if ($errors->has('upload_file'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted" id="file-error">{{ $errors->first('upload_file') }}</small>
                                                </p>
                                            @endif
                                        </fieldset>
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

            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Uploaded Templates</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered all_indicators">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th style="width: 100px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($exceluploads as $excelupload)
                                        <tr>
                                            <td>
                                                <strong>
                                                    Indicator {{$excelupload->indicator->identifier}}
                                                </strong>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{ route('settings.excelupload.download',  [Crypt::encrypt($excelupload->id)] ) }}" class="btn btn-s btn-secondary"><i class="fa fa-cloud-download"></i><span></span></a>
                                                    {{-- <a href="{{ route('settings.excelupload.delete',  [Crypt::encrypt($excelupload->id)] ) }}"
                                                       class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete ">
                                                       <br>
                                                        <i class="ft-trash-2"></i>

                                                    </a> --}}

                                                    <a href="{{ route('settings.excelupload.delete',  [Crypt::encrypt($excelupload->id)] ) }}" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Project"><i class="ft-trash-2"></i></a>
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
    <script src="{{asset('vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/extended/form-inputmask.js')}}" type="text/javascript"></script>
@endpush