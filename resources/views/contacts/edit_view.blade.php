<form class="form" action="{{route('user-management.contacts.update',['id' => $contacts->id])}}" method="post">
    @csrf
    <div class="form-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group{{ $errors->has('role') ? ' form-control-warning' : '' }}">
                    <label for="role">{{ __('Role') }}</label>
                    <select id="role" onchange="changeOnRole()" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" value="{{ old('email') }}" required>
                        <option value="">Select Role</option>
                        <option {{($contacts->contact_title == 'PSC Member')  ? "selected":""}} value="PSC Member">PSC Member (country)</option>
                        <option {{($contacts->contact_title == 'Focal Person')  ? "selected":""}} value="Focal Person">Focal Person (country)</option>
                        <option {{($contacts->contact_title == 'Country TTL')  ? "selected":""}} value="Country TTL">Country TTL (country)</option>
                        <option {{($contacts->contact_title == 'Vice Chancellor')  ? "selected":""}} value="Vice Chancellor">Vice Chancellor (institution) </option>
                        <option {{($contacts->contact_title == 'Primary Expert')  ? "selected":""}} value="Primary Expert">Primary Expert (thematic area)</option>
                        <option {{($contacts->contact_title == 'Secondary expert')  ? "selected":""}} value="Secondary expert">Secondary expert (thematic area)</option>
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
                            <option {{($contacts->institution == $institution->id)  ? "selected":""}}  value="{{$institution->id}}">{{$institution->name}}</option>
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
            <div class="col-md-4">
                {{--<input type="hidden" value="{{ $ace->id }}" name="ace_id" id="ace_id" class=" form-control">--}}
                <div class="form-group{{ $errors->has('mailing_name') ? ' form-control-warning' : '' }}">

                    <label for="email">Name <span class="required">*</span></label><input type="text" required placeholder="Name" min="2" name="mailing_name" class="form-control" value="{{ (old('mailing_name')) ? old('mailing_name') : $contacts->contact_name }}"  id="mailing_name">
                    @if ($errors->has('mailing_name'))
                        <p class="text-right">
                            <small class="warning text-muted">{{ $errors->first('mailing_name') }}</small>
                        </p>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group{{ $errors->has('mailing_phone') ? ' form-control-warning' : '' }}">
                    <label for="mailing_phone">Phone Number <span class="required">*</span></label>
                    <input type="text" required placeholder="Phone Number" min="2" name="mailing_phone" class="form-control" value="{{ (old('mailing_phone')) ? old('mailing_phone') : $contacts->contact_phone }}" id="mailing_phone">
                    @if ($errors->has('mailing_email'))
                        <p class="text-right">
                            <small class="warning text-muted">{{ $errors->first('mailing_phone') }}</small>
                        </p>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group{{ $errors->has('mailing_email') ? ' form-control-warning' : '' }}">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" required placeholder="Email Address" min="2" name="mailing_email" class="form-control"  value="{{ (old('mailing_email')) ? old('mailing_email') : $contacts->email}}" id="mailing_email">
                    @if ($errors->has('mailing_email'))
                        <p class="text-right">
                            <small class="warning text-muted">{{ $errors->first('email') }}</small>
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