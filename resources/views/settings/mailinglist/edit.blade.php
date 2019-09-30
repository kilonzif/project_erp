
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
            <h3 class="content-header-title mb-0"></h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Settings
                        </li>
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" id="basic-layout-form">Edit Contact</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>

                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
        <form class="form" action="{{route('settings.mailinglist.update',['id' => $aceemails->id])}}" method="post">
                        @csrf
                        <div class="form-body">

                            <div class="row">
                                <input type="hidden" value="{{ $ace->id }}" name="ace_id" id="ace_id" class=" form-control">
                                <div class="col-md-6">
                                    <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                                        <label for="mailing_name">Name <span class="required">*</span></label>
                                        <input type="text"  required placeholder="Name" min="2" name="mailing_name" class="form-control" value="{{ $aceemails->contact_name }}" id="mailing_name">
                                        @if ($errors->has('mailing_name'))
                                            <p class="text-right">
                                                <small class="warning text-muted">{{ $errors->first('mailing_name') }}</small>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="mailing_title">Title <span class="required">*</span></label>
                                    <select class="form-control" name="mailing_title" >
                                        <option value="">Select Title</option>
                                        <option {{($aceemails->contact_title == 'Center Leader')  ? "selected":""}}  value="Center Leader">Center Leader</option>
                                        <option {{($aceemails->contact_title=='Deputy Center Leader')  ? "selected":""}}  value="Deputy Center Leader">Deputy Center Leader</option>
                                        <option {{($aceemails->contact_title=='Finance Officer') ? "selected":""}} value="Finance Officer">Finance Officer</option>
                                        <option {{($aceemails->contact_title=='Focal Person')  ? "selected":""}} value="Focal Person">Focal Person</option>
                                        <option {{ ($aceemails->contact_title=='M & E')  ? "selected":""}}  value="M & E">M & E</option>
                                        <option {{($aceemails->contact_title=='Primary Expert') ? "selected":""}} value="Primary Expert">Primary Expert</option>
                                        <option {{($aceemails->contact_title=='Procument Officer') ? "selected":""}} value="Procument Officer">Procument Officer</option>
                                        <option {{($aceemails->contact_title=='Project / Program Manager') ? "selected":""}} value="Project / Program Manager">Project / Program Manager</option>
                                        <option {{($aceemails->contact_title == 'PSC Member') ? "selected":""}}  value="PSC Member">PSC Member</option>
                                        <option {{($aceemails->contact_title == 'Secondary Expert') ? "selected":""}} value="Secondary Expert">Secondary Expert</option>
                                        <option {{($aceemails->contact_title == 'Vice Chancellor') ? "selected":""}} value="Vice Chancellor">Vice Chancellor</option>

                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group{{ $errors->has('mailing_phone') ? ' form-control-warning' : '' }}">
                                        <label for="mailing_phone">Phone Number <span class="required">*</span></label>
                                        <input type="text" required placeholder="Phone Number" min="2" name="mailing_phone" class="form-control" value="{{ $aceemails->contact_phone }}" id="mailing_phone">
                                        @if ($errors->has('mailing_email'))
                                            <p class="text-right">
                                                <small class="warning text-muted">{{ $errors->first('mailing_phone') }}</small>
                                            </p>
                                        @endif
                                    </div>
                                </div>

                              <div class="col-md-6">
                                    <div class="form-group{{ $errors->has('mailing_email') ? ' form-control-warning' : '' }}">
                                            <label for="email">Email <span class="required">*</span></label>
                                            <input type="email" required placeholder="Email Address" min="2" name="mailing_email" class="form-control" value="{{ $aceemails->email }}" id="mailing_email">
                                            @if ($errors->has('mailing_email'))
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