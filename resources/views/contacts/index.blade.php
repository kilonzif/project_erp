@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/extended/form-extended.css')}}">
    <style>
        span.symbol{
            font-size: 12px;
        }
    </style>
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item">user management</li>
                        <li class="breadcrumb-item active">Contacts
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-md-12">
        <div class="card">
            <h6 class="card-header p-1 card-head-inverse bg-primary" style="border-radius:0">
                Central Contact Management
            </h6>
            <div class="card-content">
                <div class="card-body table-responsive">
                    <div id="edit_view">
                        <form class="form" action="{{route('user-management.contacts.save')}}" method="post">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('role') ? ' form-control-warning' : '' }}">
                                            <label for="role">{{ __('Role') }}</label>
                                            <select id="role" onchange="changeOnRole()" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" value="{{ old('email') }}" required>
                                                <option value="">Select Role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}">{{$role->position_title}}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('role'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('role') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('institution') ? ' form-control-warning' : '' }}" style="display: block;" id="institution_toggle">
                                            <label for="institution">{{ __('Select Institution') }}</label>
                                            <select id="institution" class="form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" name="institution" value="{{ old('institution') }}">
                                                <option value="">Select Institution</option>
                                                @foreach($institutions as $institution)
                                                    <option value="{{$institution->id}}">{{$institution->name}}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('institution'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('institution') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('country') ? ' form-control-warning' : '' }}" id="country_toggle" style="display: none;">
                                            <label for="ace">{{ __('Select Country') }}</label>
                                            <select id="country" class="form-control{{ $errors->has('ace') ? ' is-invalid' : '' }}" name="country" value="{{ old('country') }}">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option {{old('country') == $country->id ? 'selected': ''}} value="{{$country->id}}">{{$country->country}}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('country'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('country') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('thematic_field') ? ' form-control-warning' : '' }}" id="thematic_field_toggle" style="display: none;">
                                            <label for="thematic_field">{{ __('Select Thematic Field') }}</label>
                                            <select id="thematic_field" class="form-control{{ $errors->has('thematic_field') ? ' is-invalid' : '' }}" name="thematic_field" value="{{ old('thematic_field') }}">
                                                <option value="">Select Field of Study</option>
                                                <option value="Agriculture">Agriculture</option>
                                                <option value="Health">Health</option>
                                                <option value="STEM">STEM</option>
                                                <option value="Education">Education</option>
                                                <option value="Applied Soc. Sc.">Applied Soc. Sc.</option>
                                            </select>

                                            @if ($errors->has('thematic_field'))
                                                <p class="text-right mb-0">
                                                    <small class="warning text-muted">{{ $errors->first('thematic_field') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {{--<input type="hidden" value="{{ $ace->id }}" name="ace_id" id="ace_id" class=" form-control">--}}
                                        <div class="form-group{{ $errors->has('mailing_name') ? ' form-control-warning' : '' }}">

                                            <label for="email">Name <span class="required">*</span></label><input type="text" required placeholder="Name" min="2" name="mailing_name" class="form-control" value="{{ old('mailing_name') }}" id="mailing_name">
                                            @if ($errors->has('mailing_name'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('mailing_name') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('mailing_phone') ? ' form-control-warning' : '' }}">
                                            <label for="email">Phone </label><input type="text"  placeholder="Phone Number" min="2" name="mailing_phone" class="form-control" value="{{ old('mailing_phone') }}" id="mailing_phone">
                                            @if ($errors->has('mailing_phone'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('mailing_phone') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                                            <label for="email">Email <span class="required">*</span></label><input type="email" required placeholder="Email Address" min="2" name="mailing_email" class="form-control" value="{{ old('mailing_email') }}" id="mailing_email">
                                            @if ($errors->has('mailing_email'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('mailing_email') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary fa fa-save" style="margin-top: 1.9rem">
                                              Save Contact
                                        </button><br><br>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                    </div>

                    <table class="table table-striped table-bordered all_indicators" id="all_indicators">
                        <thead>
                        <tr>
                            <th> Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Title</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                        </thead>

                        @foreach($all_contacts as $contact)
                            <tbody>
                            <tr>
                                <td>{{$contact->contact_name}}</td>
                                <td>{{$contact->email}}</td>
                                <td>{{$contact->contact_phone}}</td>
                                <td>
                                    @php
                                    $title = \App\Position::where('id',$contact->position_id)->first();
                                    @endphp

                                    {{$title->position_title}}
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="#edit_view" onclick="edit_view('{{\Illuminate\Support\Facades\Crypt::encrypt($contact->id)}}')" class="btn btn-s btn-secondary">
                                            <i class="ft-edit"></i></a>
                                        <a href="{{route('user-management.mailinglist.delete',[\Illuminate\Support\Facades\Crypt::encrypt($contact->id)])}}"
                                           class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this Contact?');"
                                           title="Delete Report"><i class="ft-trash-2"></i></a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>





@endsection
    @push('vendor-script')
        <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    @endpush
    @push('end-script')
        <script>
            $('#all_indicators').dataTable( {
                "ordering": false
            } );

        function changeOnRole(){
            var e = document.getElementById("role");
            var role = e.options[e.selectedIndex].value;
            var path = "{{route('user-management.contacts.get_role')}}";
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                url: path,
                type: 'GET',
                data: {id:role},
                success: function(data){
                    getCategory(data);
                },
                complete:function(){

                }
                ,
                error: function (data) {
                    console.log("error");
                    console.log(data)
                }
            });


        }

        function getCategory(data) {
            if(data === 'Vice Chancellor'){
                $('#institution_toggle').css("display", "block");
                $('#thematic_field_toggle').css("display", "none");
                $('#country_toggle').css("display", "none");
            }else if(data === 'PSC Member' || data === 'Focal Person' || data === 'Country TTL'){
                $('#country_toggle').css("display","block");
                $('#institution_toggle').css("display", "none");
                $('#thematic_field_toggle').css("display", "none");
            }
            else{
                $('#thematic_field_toggle').css("display","block");
                $('#institution_toggle').css("display", "none");
                $('#country_toggle').css("display", "none");
            }

        }



        function edit_view(key) {
            var path = "{{route('user-management.contacts.edit_view')}}";
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                url: path,
                type: 'GET',
                data: {id:key},
                beforeSend: function(){
                    $('#edit_view').block({
                        message: '<div class="ft-loader icon-spin font-large-1"></div>',
                        overlayCSS: {
                            backgroundColor: '#ccc',
                            opacity: 0.8,
                            cursor: 'wait'
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: 'transparent'
                        }
                    });;
                },
                success: function(data){
                    $('#edit_view').empty();
                    $('#edit_view').html(data.theView);
                    // console.log(data)
                },
                complete:function(){
                    $('#edit_view').unblock();
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }

        
    </script>

@endpush



