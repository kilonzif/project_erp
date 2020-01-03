
<form class="form" action="{{route('user-management.mailinglist.update',['id' => $contacts->id])}}" method="post">
    @csrf
    <div class="form-body">

        <div class="row">
            <input type="hidden" value="{{ $ace->id }}" name="ace_id" id="ace_id" class=" form-control">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">
                    <label for="mailing_name">Name <span class="required">*</span></label>
                    <input type="text"  required placeholder="Name" min="2" name="mailing_name" class="form-control" value="{{ (old('mailing_name')) ? old('mailing_name') : $contacts->contact_name }}" id="mailing_name">
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
                    <option {{($contacts->contact_title == 'Center Leader')  ? "selected":""}}  value="Center Leader">Center Leader</option>
                    <option {{($contacts->contact_title=='Deputy Center Leader')  ? "selected":""}} value="Deputy Center Leader">Deputy Center Leader</option>
                    <option {{($contacts->contact_title=='Finance Officer') ? "selected":""}} value="Finance Officer">Finance Officer</option>
                    <option {{($contacts->contact_title=='Procurement Officer') ? "selected":""}}  value="Procurement Officer">Procurement Officer</option>
                    <option {{($contacts->contact_title=='Project / Program Manager') ? "selected":""}} value="Project / Program Manager">Project / Program Manager</option>
                    <option {{($contacts->contact_title=='MEL Officer') ? "selected":""}}  value="MEL Officer">MEL Officer</option>
                </select>
            </div>
            <div class="col-md-6">
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

            <div class="col-md-6">
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

        </div>
    </div>

    <div class="col-md-4 offset-3">
        <button type="submit" class="btn btn-primary">
            Update
        </button>
        <br><br>
    </div>
</form>
