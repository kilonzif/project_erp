<form class="form" action="{{route('user-management.contacts.update',['id' => $contacts->id])}}" method="post">
    @csrf
    <div class="form-body">
        <div class="row">
            <input type="hidden" name="contact_id" value="{{$contacts->id}}">
            <div class="col-md-4">
                <div class="form-group{{ $errors->has('role') ? ' form-control-warning' : '' }}">
                    <label for="role">{{ __('Role/Position') }}</label>
                    <select id="role" onchange="changeOnRole()" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" value="{{ old('email') }}" required>
                        <option value="">Select Role</option>

                        @foreach($roles as $role)
                            <option {{($contacts->position_id == $role->id)  ? "selected":""}}  value="{{$role->id}}">{{$role->position_title}}</option>
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
                <div class="form-group{{ $errors->has('institution') ? ' form-control-warning' : '' }}" style="display: none;" id="institution_toggle">
                    <label for="institution">{{ __('Select Institution') }}</label>
                    <select id="institution" class="form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" name="institution" value="{{ old('institution') }}">
                        <option value="">Select Institution</option>
                        @foreach($institutions as $institution)
                            <option {{($contacts->$institution == $institution->id)  ? "selected":""}} value="{{$institution->id}}">{{$institution->name}}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('institution'))
                        <p class="text-right mb-0">
                            <small class="warning text-muted">{{ $errors->first('institution') }}</small>
                        </p>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('aces') ? ' form-control-warning' : '' }}" style="display: block;" id="aces_toggle">
                    <label for="ace">{{ __('Select Ace') }}</label>
                    <select id="ace" class="form-control{{ $errors->has('ace') ? ' is-invalid' : '' }}" name="ace" value="{{ old('ace') }}">
                        <option value="">Select Ace</option>
                        @foreach($aces as $ace)
                            <option {{($contacts->$ace ==$ace->id)  ? "selected":""}} value="{{$ace->id}}">{{$ace->name}}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('aces'))
                        <p class="text-right mb-0">
                            <small class="warning text-muted">{{ $errors->first('aces') }}</small>
                        </p>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('country') ? ' form-control-warning' : '' }}" id="country_toggle" style="display: none;">
                    <label for="ace">{{ __('Select Country') }}</label>
                    <select id="country" class="form-control{{ $errors->has('ace') ? ' is-invalid' : '' }}" name="country" value="{{ old('country') }}">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option {{($contacts->country == $country->id)  ? "selected":""}} value="{{$country->id}}">{{$country->country}}</option>
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
                        <option {{($contacts->thematic_field == 'Agriculture')  ? "selected":""}} value="Agriculture">Agriculture</option>
                        <option {{($contacts->thematic_field == 'Health')  ? "selected":""}} value="Health">Health</option>
                        <option {{($contacts->thematic_field == 'STEM')  ? "selected":""}} value="STEM">STEM</option>
                        <option {{($contacts->thematic_field == 'Education')  ? "selected":""}} value="Education">Education</option>
                        <option {{($contacts->thematic_field == 'Applied Soc. Sc.')  ? "selected":""}} value="Applied Soc. Sc.">Applied Soc. Sc.</option>
                    </select>

                    @if ($errors->has('thematic_field'))
                        <p class="text-right mb-0">
                            <small class="warning text-muted">{{ $errors->first('thematic_field') }}</small>
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-4" id="second_role_toggle" style="display: {{isset($contacts->second_role_id) ? 'block': 'none'}}">
                <div class="form-group{{ $errors->has('role') ? ' form-control-warning' : '' }}">
                    <label for="second_role">{{ __('Second Ace Role/Position') }}</label>
                    <select id="second_role"  class="form-control{{ $errors->has('second_role') ? ' is-invalid' : '' }}" name="second_role" >
                        <option value="">Select Role (2)</option>
                        @foreach($ace_roles as $role)
                            <option {{($contacts->second_role_id == $role->id)  ? "selected":""}} value="{{$role->id}}">{{$role->position_title}}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('second_role'))
                        <p class="text-right mb-0">
                            <small class="warning text-muted">{{ $errors->first('second_role') }}</small>
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group{{ $errors->has('person_title') ? ' form-control-warning' : '' }}">
                    <label for="email">Person Title <span class="required">*</span></label><input type="text" required placeholder="Title eg Mr., Ms, Mrs" min="2" name="person_title" class="form-control" value="{{ (old('person_title')) ? old('person_title') : $contacts->person_title }}" id="person_title">
                    @if ($errors->has('person_title'))
                        <p class="text-right">
                            <small class="warning text-muted">{{ $errors->first('person_title') }}</small>
                        </p>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group{{ $errors->has('mailing_name') ? ' form-control-warning' : '' }}">

                    <label for="email">Name <span class="required">*</span></label>
                    <input type="text" required placeholder="Name" min="2" name="mailing_name" class="form-control" value="{{ (old('mailing_name')) ? old('mailing_name') : $contacts->mailing_name }}"  id="mailing_name">
                    @if ($errors->has('mailing_name'))
                        <p class="text-right">
                            <small class="warning text-muted">{{ $errors->first('mailing_name') }}</small>
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group{{ $errors->has('gender') ? ' form-control-warning' : '' }}">
                    <label for="email">Gender <span class="required">*</span></label>
                    <select name="gender" required class="form-control">
                        <option value="">Select Gender</option>
                        <option {{($contacts->gender == 'male')  ? "selected":""}} value="male">Male</option>
                        <option {{($contacts->gender == 'female')  ? "selected":""}} value="female">Female</option>
                    </select>
                    @if ($errors->has('gender'))
                        <p class="text-right">
                            <small class="warning text-muted">{{ $errors->first('gender') }}</small>
                        </p>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group{{ $errors->has('mailing_phone') ? ' form-control-warning' : '' }}">
                    <label for="mailing_phone">Phone Number</label>
                    <input type="text"  placeholder="Phone Number" min="2" name="mailing_phone" class="form-control" value="{{ (old('mailing_phone')) ? old('mailing_phone') : $contacts->mailing_phone }}" id="mailing_phone">
                    @if ($errors->has('mailing_phone'))
                        <p class="text-right">
                            <small class="warning text-muted">{{ $errors->first('mailing_phone') }}</small>
                        </p>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group{{ $errors->has('mailing_email') ? ' form-control-warning' : '' }}">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" required placeholder="Email Address" min="2" name="mailing_email" class="form-control"  value="{{ (old('mailing_email')) ? old('mailing_email') : $contacts->mailing_email}}" id="mailing_email">
                    @if ($errors->has('mailing_email'))
                        <p class="text-right">
                            <small class="warning text-muted">{{ $errors->first('email') }}</small>
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group{{ $errors->has('new_contact') ? ' form-control-warning' : '' }}">
                    <label for="email">New Contact ? <span class="required">*</span></label>
                    <select name="new_contact" class="form-control" required>
                        <option {{$contacts->new_contact =='1'?"selected":""}} value="1">Yes</option>
                        <option {{$contacts->new_contact =='0'?"selected": ""}} value="0">No</option>
                    </select>
                    @if ($errors->has('new_contact'))
                        <p class="text-right">
                            <small class="warning text-muted">{{ $errors->first('new_contact') }}</small>
                        </p>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-primary fa fa-save" style="margin-top: 1.9rem">
                    Update Contact
                </button><br><br>
            </div>
        </div>
    </div>
</form>
<br>