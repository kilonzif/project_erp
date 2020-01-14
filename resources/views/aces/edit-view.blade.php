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
                    <div class="col-md-8">
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
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <div class="form-group{{ $errors->has('dlr') ? ' form-control-warning' : '' }}">
                            <label for="dlr">Grant Amount </label>
                            <input type="number" placeholder="DLR Amount" min="0" name="dlr" class="form-control"
                                   value="{{ $ace->dlr }}" id="dlr" style="text-align: right;">
                            @if ($errors->has('dlr'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('dlr') }}</small>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group{{ $errors->has('currency') ? ' form-control-warning' : '' }}">
                            <label for="field">{{__('Currency')}} <span class="required">*</span></label>
                            <select class="form-control" required name="currency" id="currency">
                                <option value="" selected disabled>--Choose--</option>
                                @foreach($currency as $cc)
                                    <option {{($ace->currency_id == $cc->id)  ? "selected":""}} value="{{$cc->id}}">
                                        {{$cc->name.' - '.$cc->symbol}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('currency'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('currency') }}</small>
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
                    <div class="col-md-4">
                        <div class="form-group{{ $errors->has('ace_type') ? ' form-control-warning' : '' }}">
                            <label for="ace_type">Type of Centres<span class="required">*</span></label>
                            <select class="form-control" required name="ace_type" id="ace_type">
                                <option value="">--Choose--</option>
                                <option {{($ace->ace_type == 'engineering')  ? "selected":""}} value="engineering">Colleges of Engineering </option>
                                <option {{($ace->ace_type == 'emerging')  ? "selected":""}} value="emerging">Emerging Centre</option>
                                <option {{($ace->ace_type == 'ACE')  ? "selected":""}} value="ACE">ACE</option>
                            </select>
                            @if ($errors->has('ace_type'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('ace_type') }}</small>
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
                    <div class="col-md-2">
                        <div class="form-group{{ $errors->has('contact') ? ' form-control-warning' : '' }}">
                            <label for="contact">Phone Number <span class="required">*</span></label>
                            <input type="text" name="contact" required placeholder="Phone Number" class="form-control" value="{{ $ace->contact }}" id="contact">
                            @if ($errors->has('contact'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('contact') }}</small>
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