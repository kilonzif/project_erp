<div class="card-header">
    <h4 class="card-title">Edit Institution</h4>
    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
    <div class="heading-elements">
        <ul class="list-inline mb-0">
            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
        </ul>
    </div>
</div>
<div class="card-content collapse show">
    <div class="card-body">
        <form id="add-form" action="{{route('user-management.institution.update')}}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{$institution->id}}">
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group{{ $errors->has('name') ? ' form-control-warning' : '' }}">
                        <label for="permission_name">Institution Name <span class="required">*</span></label>
                        <input type="text" required min="2" name="name" placeholder="Institution Name" class="form-control"
                               value="{{ old('name')?old('name') : $institution->name }}" id="permission_name">
                        @if ($errors->has('name'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('name') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group{{ $errors->has('contact_name') ? ' form-control-warning' : '' }}">
                        <label for="contact_name">Contact Person Name</label>
                        <input type="text" required min="2" name="contact_name" placeholder="Contact Person Name" class="form-control"
                               value="{{ old('contact_name')? old('contact_name') : $institution->contact_person }}" id="contact_name">
                        @if ($errors->has('contact_name'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('contact_name') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('contact') ? ' form-control-warning' : '' }}">
                        <label for="contact">Phone Number <span class="required">*</span></label>
                        <input type="text" name="contact" placeholder="Phone Number" class="form-control"
                               value="{{ old('contact')?old('contact') : $institution->contact }}" id="contact">
                        @if ($errors->has('contact'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('contact') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" placeholder="Email Address" min="2" name="email" class="form-control"
                               value="{{ old('email')?old('email') : $institution->email }}" id="email">
                        @if ($errors->has('email'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('email') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group{{ $errors->has('contact_email') ? ' form-control-warning' : '' }}">
                        <label for="contact_email">Contact Person Email</label>
                        <input type="email" placeholder="Contact Person Email" min="6" name="contact_email" class="form-control"
                               value="{{ old('contact_email') ?old('contact_email') : $institution->person_email}}" id="contact_email">
                        @if ($errors->has('contact_email'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('contact_email') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group{{ $errors->has('country') ? ' form-control-warning' : '' }}">
                        <label for="country">Country <span class="required">*</span></label>
                        <select class="form-control" required name="country" id="country">
                            <option value="">Choose country</option>
                            @foreach($countries as $country)
                                <option {{($institution->country_id == $country->id)?'selected' : ''}} value="{{$country->id}}">{{$country->country}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('country'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('country') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('position') ? ' form-control-warning' : '' }}">
                        <label for="position">Contact Person's Position</label>
                        <input type="text" name="position" placeholder="Position" class="form-control"
                               value="{{ old('position') ?old('position') : $institution->position}}" id="position">
                        @if ($errors->has('position'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('position') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="skin skin-square">
                            <input type="radio" name="is_uni" value="1" {{($institution->university == 1)?'checked' : ''}} id="is_uni_true">
                            <label for="is_uni_true" class="">University</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="skin skin-square">
                            <input type="radio" name="is_uni" value="0" {{($institution->university == 0)?'checked' : ''}} id="is_uni_false">
                            <label for="is_uni_false" class="">Partner</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group{{ $errors->has('contact_person_phone') ? ' form-control-warning' : '' }}">
                        <label for="contact">Contact Person's Number</label>
                        <input type="text" name="contact_person_phone" placeholder="Phone Number" class="form-control"
                               value="{{ old('contact_person_phone') ?old('contact_person_phone') : $institution->person_number}}" id="contact_person_phone">
                        @if ($errors->has('contact_person_phone'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('contact_person_phone') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                    Update</button>
            </div>
        </form>
    </div>
</div>