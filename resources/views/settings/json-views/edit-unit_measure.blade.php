<div class="card-header">
    <h4 class="card-title">Editing Unit of Measure</h4>
    <div class="heading-elements">
        <ul class="list-inline mb-0">
            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
        </ul>
    </div>
</div>
<div class="card-content collapse show">
    <div class="card-body">
        <form action="{{route('sub_indicator.unit_measure.update')}}" id="add_uom" method="post">
            @csrf
            <input type="hidden" name="id" value="{{$uom->id}}">
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="title">Title <span class="required">*</span></label>
                        <input type="text" required min="5" value="{{ (old('title')) ? old('title') : $uom->title }}" name="title" class="form-control" id="title">
                        @if ($errors->has('title'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('title') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="order">Order No. <span class="required">*</span></label>
                        <input type="number" required name="order" min="1" value="{{ (old('order')) ? old('order') : $uom->order_no }}" class="form-control" id="order">
                        @if ($errors->has('order'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ old('order') }}</small>
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