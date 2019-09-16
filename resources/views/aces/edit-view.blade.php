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
        <form id="edit-form" action="{{route('user-management.ace.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($ace->id)}}">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group{{ $errors->has('name') ? ' form-control-warning' : '' }}">
                        <label for="permission_name">ACE Name <span class="required">*</span></label>
                        <input type="text" required min="2" name="name" placeholder="ACE Name" class="form-control"
                               value="{{ old('name')?old('name'):$ace->name }}" id="name">
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
                        <input type="text" required placeholder="Acronym" min="2" name="acronym" class="form-control"
                               value="{{ old('acronym')?old('acronym'):$ace->acronym }}" id="acronym">
                        @if ($errors->has('acronym'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('acronym') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group{{ $errors->has('dlr') ? ' form-control-warning' : '' }}">
                        <label for="dlr">DLR Amount <span class="required">*</span></label>
                        <input type="text" required placeholder="DLR Amount" step="0.10" min="0" name="dlr" class="form-control"
                               value="{{ old('dlr')?old('dlr'):$ace->dlr }}" id="dlr" style="text-align: right;">
                        @if ($errors->has('dlr'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('dlr') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('currency') ? ' form-control-warning' : '' }}">
                        <label for="field">{{__('Currency')}} <span class="required">*</span></label>
                        <select class="form-control" required name="currency" id="currency">
                            <option value="">--Choose--</option>
                            @foreach($currency as $cc)
                                <option {{($cc->id == $ace->currency_id)?"selected":""}} value="{{$cc->id}}">
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
                <div class="col-md-2">
                    <div class="form-group{{ $errors->has('field') ? ' form-control-warning' : '' }}">
                        <label for="field">{{__('Field of Study')}} <span class="required">*</span></label>
                        <select class="form-control" required name="field" id="field">
                            <option value="">--Choose--</option>
                            <option {{($ace->field == 'Agriculture')?"selected":""}} value="Agriculture">Agriculture</option>
                            <option {{($ace->field == 'Health')?"selected":""}} value="Health">Health</option>
                            <option {{($ace->field == 'STEM')?"selected":""}} value="STEM">STEM</option>
                            <option {{($ace->field == 'Education')?"selected":""}} value="Education">Education</option>
                            <option {{($ace->field == 'Applied Soc. Sc.')?"selected":""}} value="Applied Soc. Sc.">Applied Soc. Sc.</option>
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
                        <input type="email" required placeholder="Email Address" min="2" name="email" class="form-control"
                               value="{{ old('email') ? old('email'):$ace->email }}" id="email">
                        @if ($errors->has('email'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('email') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('contact_name') ? ' form-control-warning' : '' }}">
                        <label for="contact_name">Contact Person Name</label>
                        <input type="text" required min="2" name="contact_name" placeholder="Contact Person Name" class="form-control"
                               value="{{ old('contact_name')  ? old('contact_name'):$ace->contact_person }}" id="contact_name">
                        @if ($errors->has('contact_name'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('contact_name') }}</small>
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
                                <option {{($ace->institution_id == $university->id)?"selected":""}} value="{{$university->id}}">{{$university->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('university'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('university') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group{{ $errors->has('contact') ? ' form-control-warning' : '' }}">
                        <label for="contact">Phone Number <span class="required">*</span></label>
                        <input type="text" name="contact" required placeholder="Phone Number" class="form-control"
                               value="{{ old('contact')?old('contact'):$ace->contact }}" id="contact">
                        @if ($errors->has('contact'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('contact') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('contact_person_phone') ? ' form-control-warning' : '' }}">
                        <label for="contact">Contact Person's Number</label>
                        <input type="text" name="contact_person_phone" placeholder="Phone Number" class="form-control"
                               value="{{ old('contact_person_phone') ? old('contact_person_phone'):$ace->person_number }}" id="contact_person_phone">
                        @if ($errors->has('contact_person_phone'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('contact_person_phone') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('contact_email') ? ' form-control-warning' : '' }}">
                        <label for="contact_email">Contact Person Email</label>
                        <input type="email" placeholder="Contact Person Email" min="6" name="contact_email" class="form-control"
                               value="{{ old('contact_email')  ? old('contact_email'):$ace->person_email }}" id="contact_email">
                        @if ($errors->has('contact_email'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('contact_email') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div style="margin-top: 2rem;" class="col-md-4">
                    <div class="form-group">
                        <div class="skin skin-square">
                            <input type="radio" name="active" value="1" {{($ace->active == 1)?"checked":""}} id="active">
                            <label for="active" class="">Active</label>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 2rem;" class="col-md-4">
                    <div class="form-group">
                        <div class="skin skin-square">
                            <input type="radio" name="active" value="0" {{($ace->active == 0)?"checked":""}} id="inactive">
                            <label for="inactive" class="">Inactive</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('ace_type') ? ' form-control-warning' : '' }}">
                        <label for="ace_type">Type of Centres</label>
                        <select class="form-control" required name="ace_type" id="ace_type">
                            <option value="">--Choose--</option>
                            <option {{($ace->ace_type == 'engineering')?"selected":""}} value="engineering">Colleges of Engineering </option>
                            <option {{($ace->ace_type == 'emerging')?"selected":""}} value="emerging">Emerging Centre</option>
                            <option {{($ace->ace_type == 'ACE')?"selected":""}} value="ACE">ACE</option>
                        </select>
                        @if ($errors->has('ace_type'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('ace_type') }}</small>
                            </p>
                        @endif

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('position') ? ' form-control-warning' : '' }}">
                        <label for="position">Contact Person's Position</label>
                        <input type="text" name="position" placeholder="Position" class="form-control"
                               value="{{ old('position') ? old('position'):$ace->position }}" id="position">
                        @if ($errors->has('position'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('position') }}</small>
                            </p>
                        @endif
                    </div>
                </div>

                    {{--<div class="col-md-12">--}}
                        {{--<h4 class="card-title">Indicator 1(Institution Readiness)</h4>--}}
                    {{--</div>--}}
                {{--@foreach($indicator_ones as $key=>$indicator_one)--}}
                    {{--<div class="col-md-6">--}}
                        {{--<h4>{{$indicator_one->requirement}}</h4>--}}
                        {{--<input type="hidden" name="requirement[]" value="{{ old('requirement')?old('requirement'):$indicator_one->requirement}}">--}}
                        {{--<div class="form-group">--}}
                            {{--<label>Finalised</label>--}}
                            {{--<div class="skin skin-square">--}}
                                {{--<label for="finalised" class="">Yes</label>--}}
                                {{--<input type="radio" name="{{'finalised'.$key}}" value="1"  {{($indicator_one->finalised == 1)?"checked":""}} id="finalised">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="skin skin-square">--}}
                                {{--<label for="finalised" class="">NO</label>--}}
                                {{--<input type="radio" name="{{'finalised'.$key}}" value="1" {{($indicator_one->finalised == 0)?"checked":""}}  id="finalised">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                        {{--<label>Submission Date:</label>--}}
                            {{--<input type="text" value="{{ old('submission_date')?old('submission_date'):$indicator_one->submission_date}}">--}}
                        {{--<input type="date" class="form-control" name="submission_date[]"  id="submission_date" value="{{ old('submission_date')?old('submission_date'):$indicator_one->submission_date}}">--}}
                        {{--</div>--}}
                        {{--@if(!$indicator_one->file_name)--}}
                            {{--<div class="form-group">--}}
                                {{--<label>File Upload:</label>--}}
                                {{--<input type="file" class="form-control" name="file_name[]" placeholder="{{$indicator_one->file_name}}" value="{{ old('file_name')?old('file_name'):$indicator_one->file_name}}">--}}
                            {{--</div>--}}

                            {{--@else--}}
                            {{--<div class="form-group">--}}
                                {{--<label>File uploaded:  {{$indicator_one->file_name}}</label>--}}
                                {{--<img src=""  id="preview"/>--}}
                                 {{--<input type="file" id="filename{{$key}}" name="file_name[]"  class="form-control" style="display:none"/>--}}
                                {{--<a href="javascript:;" onclick="changeFile('filename{{$key}}')">Change</a>--}}
                            {{--</div>--}}

                        {{--@endif--}}

                        {{--<div class="form-group{{ $errors->has('web_link[]') ? ' form-control-warning' : '' }}">--}}
                        {{--<label>URL:</label>--}}
                        {{--<input type="text" name="url[]" placeholder="url" class="form-control"  value="{{ old('url')?old('url'):$indicator_one->url}}" id="url">--}}
                        {{--@if ($errors->has('url[]'))--}}
                        {{--<p class="text-right">--}}
                        {{--<small class="warning text-muted">{{ $errors->first('url[]') }}</small>--}}
                        {{--</p>--}}
                        {{--@endif--}}
                        {{--</div>--}}
                        {{--<div class="form-group{{ $errors->has('web_link[]') ? ' form-control-warning' : '' }}">--}}
                            {{--<label for="web_link1">Web Link</label>--}}
                            {{--<input type="text" name="web_link[]" placeholder="web_link" class="form-control"  value="{{ old('web_link')?old('web_link'):$indicator_one->web_link}}" id="web_link1">--}}
                            {{--@if ($errors->has('web_link[]'))--}}
                            {{--<p class="text-right">--}}
                            {{--<small class="warning text-muted">{{ $errors->first('web_link[]') }}</small>--}}
                            {{--</p>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                        {{--<div class="form-group{{ $errors->has('comments[]') ? ' form-control-warning' : '' }}">--}}
                            {{--<label for="comments1">Comments</label>--}}
                            {{--<input type="text" name="comments[]" placeholder="comments" class="form-control"  value="{{ old('comments')?old('comments'):$indicator_one->comments}}" id="comments1">--}}
                            {{--@if ($errors->has('comments[]'))--}}
                            {{--<p class="text-right">--}}
                            {{--<small class="warning text-muted">{{ $errors->first('comments[]') }}</small>--}}
                            {{--</p>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--@endforeach--}}

            </div>
            <div class="form-group">
                <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                    Update</button>
            </div>
        </form>
    </div>
</div>
