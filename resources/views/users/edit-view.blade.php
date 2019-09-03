<div class="card-header">
    <h4 class="card-title">Editing User</h4>
    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
    <div class="heading-elements">
        <ul class="list-inline mb-0">
            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
        </ul>
    </div>
</div>
<div class="card-content collapse show">
    <div class="card-body card-dashboard">
        <form method="POST" action="{{ route('user-management.user.update_user') }}">
            @csrf
            <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($user->id)}}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('name') ? ' form-control-warning' : '' }}">
                        <label for="permission_name">{{ __('Name') }} <span class="required">*</span></label>
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                               name="name" value="{{ old('name')?old('name'):$user->name }}" required autofocus>

                        @if ($errors->has('name'))
                            <p class="text-right mb-0">
                                <small class="warning text-muted">{{ $errors->first('name') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                        <label for="permission_name">{{ __('E-Mail Address') }} <span class="required">*</span></label>
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               name="email" value="{{ old('email')?old('email'):$user->email }}" required>

                        @if ($errors->has('email'))
                            <p class="text-right mb-0">
                                <small class="warning text-muted">{{ $errors->first('email') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('phone') ? ' form-control-warning' : '' }}">
                        <label for="phone">{{ __('Phone Number') }} <span class="required">*</span></label>
                        <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                               name="phone" value="{{ old('phone')?old('phone'):$user->phone }}" required>

                        @if ($errors->has('phone'))
                            <p class="text-right mb-0">
                                <small class="warning text-muted">{{ $errors->first('phone') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('role') ? ' form-control-warning' : '' }}">
                        <label for="role">{{ __('Role') }} <span class="required">*</span></label>
                        <select id="role" onchange="changeOnRole()" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" value="{{ old('email') }}" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option @if($user->hasRole($role->name))selected @endif value="{{$role->id}}">{{$role->display_name}}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('role'))
                            <p class="text-right mb-0">
                                <small class="warning text-muted">{{ $errors->first('role') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('institution') ? ' form-control-warning' : '' }}" style="display: {{(!$user->ace)?'block':'none'}};" id="institution_toggle">
                        <label for="institution">{{ __('Select Institution') }} <span class="required">*</span></label>
                        <select id="institution" class="form-control{{ $errors->has('institution') ? ' is-invalid' : '' }}" name="institution" value="{{ old('institution') }}">
                            <option value="">Select Institution</option>
                            @foreach($institutions as $institution)
                                <option {{($user->institution == $institution->id)? 'selected': ''}} value="{{$institution->id}}">{{$institution->name}}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('institution'))
                            <p class="text-right mb-0">
                                <small class="warning text-muted">{{ $errors->first('institution') }}</small>
                            </p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('ace') ? ' form-control-warning' : '' }}" id="ace_toggle" style="display: {{($user->ace)?'block':'none'}};">
                        <label for="ace">{{ __('Select ACE') }} <span class="required">*</span></label>
                        <select id="ace" class="form-control{{ $errors->has('ace') ? ' is-invalid' : '' }}" name="ace" value="{{ old('ace') }}">
                            <option value="">Select ACE</option>
                            @foreach($aces as $ace)
                                <option {{($user->ace == $ace->id)? 'selected': ''}} value="{{$ace->id}}">
                                    {{$ace->name." (".$ace->acronym.")"}}
                                </option>
                            @endforeach
                        </select>

                        @if ($errors->has('ace'))
                            <p class="text-right mb-0">
                                <small class="warning text-muted">{{ $errors->first('ace') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary mr-2">
                        {{ __('Update User') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>