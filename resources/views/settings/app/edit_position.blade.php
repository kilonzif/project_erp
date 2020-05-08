<form action="{{route('settings.app_settings.update_position')}}" method="post">
    <div class="row">
        @csrf
        <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($update_this_position->id)}}">
        <div class="col-md-4 form-group {{ $errors->has('position_title') ? ' form-control-warning' : '' }}">
            <label>Position Title<span class="required">*</span></label>
            <div class="input-group">
                <input type='text' name="position_title"  class="form-control" value="{{ (old('position_title')) ? old('position_title') : $update_this_position->position_title}}" placeholder="Role" required
                />
            </div>
            @if ($errors->has('position_title'))
                <p class="text-right mb-0">
                    <small class="warning text-muted">{{ $errors->first('position_title') }}</small>
                </p>
            @endif
        </div>
        <div class="col-md-4 form-group {{ $errors->has('position_type') ? ' form-control-warning' : '' }}">
            <label>Position Type<span class="required">*</span></label>
            <select name="position_type" id="position_type" class="form-control" required>
                <option value="">Select One</option>
                <option {{($update_this_position->position_type=="ACE level")? "selected":""}} value="ACE level"> ACE level</option>
                <option {{($update_this_position->position_type=="Institutional level")? "selected":""}} value="Institutional level">Institutional level</option>
                <option {{($update_this_position->position_type=="Country level")? "selected":""}} value="Country level"> Country level</option>
                <option {{($update_this_position->position_type=="Experts level")? "selected":""}} value="Experts level">Experts level</option>
                <option {{($update_this_position->position_type=="Sectoral Board level")? "selected":""}} value="Sectoral Board level">Sectoral Board level</option>
            </select>
            @if ($errors->has('position_type'))
                <p class="text-right mb-0">
                    <small class="warning text-muted">{{ $errors->first('position_type') }}</small>
                </p>
            @endif
        </div>
        <div class="col-md-2 form-group{{ $errors->has('position_rank') ? ' form-control-warning' : '' }}">
            <label>Position Rank<span class="required">*</span></label>
            <div class="input-group">
                <input type='text' name="position_rank" class="form-control" value="{{ (old('position_rank')) ? old('position_rank') : $update_this_position->rank}}" placeholder="Rank e.g 1" required
                />
            </div>
            @if ($errors->has('position_rank'))
                <p class="text-right mb-0">
                    <small class="warning text-muted">{{ $errors->first('position_rank') }}</small>
                </p>
            @endif
        </div>
        <div class="col-md-2 form-group">
            <button class="btn btn-primary" style="margin-top: 1.7rem;" type="submit"><i class="ft-save"></i> Update</button>
        </div>
    </div>
</form>