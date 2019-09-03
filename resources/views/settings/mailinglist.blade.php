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
            <h3 class="content-header-title mb-0">Mailing List</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Settings
                        </li>
                        <li class="breadcrumb-item active">
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" id="basic-layout-form">Add to Mailing List</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>

                    </ul>
                </div>
            </div>

            <div class="card-content collapse show">
                <div class="card-body">
                    <form class="form" action="{{route('settings.mailinglist.save')}}" method="post">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                            <label for="ace_id">Aces <span class="required">*</span></label>
                                            <select name="ace_id" id="ace_id" class=" form-control">
                                                @foreach($aces as $ace)
                                                    <option value="{{$ace->id}}">  {{$ace->name}}  </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('ace_id'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('ace_id') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                </div>
                              <div class="col-md-6">
                                    <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                                            <label for="email">Email <span class="required">*</span></label>
                                            <input type="email" required placeholder="Email Address" min="2" name="email" class="form-control" value="{{ old('email') }}" id="email">
                                            @if ($errors->has('email'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('email') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                </div>
                            </div>
                            <div class="row">

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
                        <h4 class="card-title"></h4>
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
                                    <th > Name</th>
                                    <th style="">Email</th>

                                    <th style="width: 100px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($aceemails as $aceemail)
                                        <tr>
                                            <td>{{$aceemail->ace->name}}</td>
                                            <td>{{$aceemail->email}}</td>

                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{-- {{ route('settings.projects.view', [Crypt::encrypt($project->id)]) }} --}}" class="btn btn-s btn-dark" data-toggle="tooltip" data-placement="top" title="Email"><i class="ft-eye"></i></a>  </a>

                                                    <a href="{{ route('settings.mailinglist.edit', [Crypt::encrypt($aceemail->id)]) }}" class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Email"><i class="ft-edit-3"></i></a></a>
                                                    <a href="{{ route('settings.mailinglist.delete',  [Crypt::encrypt($aceemail->id)] ) }}" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Email"><i class="ft-trash-2"></i></a></a>
                                                    {{-- <a class="dropdow-item btn {{($user->status == 0)?'btn-success' : 'btn-danger'}} btn-s" href="#"
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