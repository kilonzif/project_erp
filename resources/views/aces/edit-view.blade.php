
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">
@endpush



<div class="card-header">
        <h4 class="card-title">Edit ACE</h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="card-content collapse show">
        <div class="card-body">
            <form id="add-form" action="{{route('user-management.ace.update')}}" method="GET" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $ace->id }}" name="ace_id" id="ace_id" class=" form-control">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('name') ? ' form-control-warning' : '' }}">
                            <label for="permission_name">ACE Name <span class="required">*</span></label>
                            <input type="text" required min="2" name="name" placeholder="ACE Name" class="form-control" value="{{ $ace->name }}" id="permission_name">
                            @if ($errors->has('name'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('name') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('acronym') ? ' form-control-warning' : '' }}">
                            <label for="acronym">Acronym <span class="required">*</span></label>
                            <input type="text" required placeholder="Acronym" min="2" name="acronym" class="form-control" value="{{ $ace->acronym }}" id="acronym">
                            @if ($errors->has('acronym'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('acronym') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('field') ? ' form-control-warning' : '' }}">
                            <label for="field">{{__('Field of Study')}} <span class="required">*</span></label>
                            <select class="form-control" required name="field" id="field">
                                <option value="">--Choose--</option>
                                <option {{($ace->field == 'Agriculture')  ? "selected":""}} value="Agriculture">Agriculture</option>
                                <option {{($ace->field == 'Health')  ? "selected":""}} value="Health">Health</option>
                                <option {{($ace->field == 'STEM')  ? "selected":""}} value="STEM">STEM</option>
                                <option {{($ace->field == 'Education')  ? "selected":""}} value="Education">Education</option>
                                <option {{($ace->field == 'Applied Soc. Sc.')  ? "selected":""}} value="Applied Soc. Sc.">Applied Soc. Sc.</option>
                            </select>
                            @if ($errors->has('field'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('field') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('grant1') ? ' form-control-warning' : '' }}">
                            <label for="grant1">Grant Amount 1</label>
                            <input type="number" placeholder="SDR Grant Amount 1" min="0" name="grant1" class="form-control"
                                   value="{{ $ace->grant1 }}" id="grant1" style="text-align: right;">
                            @if ($errors->has('grant1'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('grant1') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('currency1') ? ' form-control-warning' : '' }}">
                            <label for="field">{{__('Currency 1')}}</label>
                            <select class="form-control" name="currency1" id="currency1">
                                <option value=""  disabled>--Choose--</option>
                                @foreach($currency as $cc)
                                    <option {{($cc->id == old('currency1'))?"selected":""}} value="{{$cc->id}}">
                                        {{$cc->name.' - '.$cc->symbol}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('currency1'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('currency1') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('grant2') ? ' form-control-warning' : '' }}">
                            <label for="dlr">Grant Amount 2</label>
                            <input type="number" placeholder="Grant Amount 2" min="0" name="grant2" class="form-control"
                                   value="{{ $ace->grant2 }}" id="dlr" style="text-align: right;">
                            @if ($errors->has('grant2'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('grant2') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('currency') ? ' form-control-warning' : '' }}">
                            <label for="field">{{__('Currency 2')}}</label>
                            <select class="form-control"  name="currency2" id="currency2">
                                <option value=""  disabled>--Choose--</option>
                                @foreach($currency as $cc)
                                    <option {{($cc->id == old('currency2'))?"selected":""}} value="{{$cc->id}}">
                                        {{$cc->name.' - '.$cc->symbol}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('currency2'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('currency2') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" required placeholder="Email Address" min="2" name="email" class="form-control" value="{{ $ace->email }}" id="email">
                            @if ($errors->has('email'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('email') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('contact') ? ' form-control-warning' : '' }}">
                            <label for="contact">Phone Number</label>
                            <input type="text" name="contact"  placeholder="Phone Number" class="form-control" value="{{ $ace->contact }}" id="contact">
                            @if ($errors->has('contact'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('contact') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('ace_type') ? ' form-control-warning' : '' }}">
                            <label for="ace_type">Type of Centres<span class="required">*</span></label>
                            <select class="form-control"  onchange="changeAceStatus()" required name="ace_type" id="ace_type">
                                <option value="">--Choose--</option>
                                <option {{($ace->ace_type == 'engineering')  ? "selected":""}} value="engineering">Colleges of Engineering</option>
                                <option {{($ace->ace_type == 'emerging')  ? "selected":""}} value="emerging">Emerging Centre</option>
                                <option {{($ace->ace_type == 'ACE')  ? "selected":""}} value="ACE">ACE</option>
                                <option {{($ace->ace_type == 'add-on')  ? "selected":""}} value="add-on">Add-on</option>

                            </select>
                            @if ($errors->has('ace_type'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('ace_type') }}</small>
                                </p>
                            @endif

                        </div>
                    </div>
                    <div class="col-md-3" id="ace_state_div">
                        <div class="form-group{{ $errors->has('ace_state') ? ' form-control-warning' : '' }}">
                        <label for="ace_state">Ace State <span class="required">*</span></label>
                        <select class="form-control" required name="ace_state" id="ace_state">
                            <option  value="">Choose State</option>
                            <option {{($ace->ace_state == 'NEW')  ? "selected":""}} value="NEW">NEW</option>
                            <option {{($ace->ace_state == 'RENEWED')  ? "selected":""}} value="RENEWED">RENEWED</option>
                        </select>
                        @if ($errors->has('university'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('ace_state') }}</small>

                            </p>
                        @endif
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('university') ? ' form-control-warning' : '' }}">
                            <label for="country">University <span class="required">*</span></label>
                            <select class="form-control" required name="university" id="university">
                                <option value="">Choose university</option>
                                @foreach($universities as $university)
                                    <option  {{($ace->institution_id == $university->id)  ? "selected":""}} value="{{$university->id}}">{{$university->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('university'))
                                <p class="text-right">

                                    <small class="warning text-muted">{{ $errors->first('email') }}</small>
                                    <small class="warning text-muted">{{ $errors->first('university') }}</small>

                                </p>
                            @endif
                        </div>
                    </div>
                    <div style="margin-top: 2rem;" class="col-md-2">
                        <div class="form-group">
                            <div class="skin skin-square">
                                <input type="radio" name="active" value="1" {{ ($ace->active=="1")? "checked" : "" }} id="active">
                                <label for="active" class="">Active</label>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 2rem;" class="col-md-2">
                        <div class="form-group">
                            <div class="skin skin-square">
                                <input type="radio" name="active" value="0" id="inactive" {{ ($ace->active=="0")? "checked" : "" }}>
                                <label for="inactive" class="">Inactive</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                            Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    @push('vendor-script')
        <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
    @endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>
    <script>

        function changeAceStatus(){
            var e = document.getElementById("ace_type");
            var ace_type = e.options[e.selectedIndex].value;
            if(ace_type == 'engineering' || ace_type=='add-on'){
                $('#ace_state_div').css("display", "none");
            }
            else if(ace_type == 'emerging' || ace_type=='ACE'){
                $('#ace_state_div').css("display", "block");
            }
            else{
                $('#ace_state_div').css("display", "block");
            }

        }


        $('#aces-table').dataTable( {
            "ordering": false
        } );

        $('.select2').select2({
            placeholder: "Select Programmes",
            allowClear: true
        });
</script>
    @endpush