<div class="card-header">
    <h4 class="card-title">Editing DLR Indicator</h4>
    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
    <div class="heading-elements">
        <ul class="list-inline mb-0">
            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
            {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
        </ul>
    </div>
</div>
<div class="card-content collapse show">
    <div class="card-body">
        <form action="{{route('settings.dlr_indicator.update')}}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{$indicator->id}}">
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="title">Title <span class="required">*</span></label>
                        <input type="text" required min="3" value="{{ (old('title')) ? old('title') : $indicator->indicator_title }}" placeholder="Indicator Title" name="title" class="form-control" id="title">
                        @if ($errors->has('title'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('title') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="order">Position/Order <span class="required">*</span></label>
                        <input type="number" name="order" id="order" min="1" class="form-control"
                               value="{{ (old('order')) ? old('order') : $indicator->order }}">
                        @if ($errors->has('order'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('order') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                    Save</button>
            </div>
        </form>
    </div>
</div>