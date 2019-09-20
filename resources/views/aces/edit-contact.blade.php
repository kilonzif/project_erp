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
                                <option {{($aceemails->contact_title=='Procument Officer') ? "selected":""}} value="Procument Officer">Procument Officer</option>
                                <option {{ ($aceemails->contact_title=='M & E')  ? "selected":""}}  value="M & E">M & E</option>
                                <option {{($aceemails->contact_title == 'PSC Member') ? "selected":""}}  value="PSC Member">PSC Member</option>Vi
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