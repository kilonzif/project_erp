<div class="card-header">
    <h4 class="card-title">Editing Sub-Indicator</h4>
    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
    <div class="heading-elements">
        <ul class="list-inline mb-0">
            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
        </ul>
    </div>
</div>
<div class="card-content collapse show">
    <div class="card-body">
        <form action="{{route('sub_indicator.update')}}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{$sub_indicator->id}}">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Title <span class="required">*</span></label>
                        <input type="text" required min="5" value="{{ (old('title')) ? old('title') : $sub_indicator->title }}" name="title" class="form-control" id="title">
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
                        <input type="number" required name="order" min="1" value="{{ (old('order')) ? old('title') : $sub_indicator->order_no }}" class="form-control" id="order">
                        @if ($errors->has('order'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('order') }}</small>
                            </p>
                        @endif
                    </div>
                </div>

                {{--Unit of measure select--}}
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="unit_measure">Unit of Measure</label><br>
                        <select name="uom_id" id="unit_measure" style="width: 100%;" class="select2 form-control">
                            <option value="">Please select</option>
                            @foreach($uoms as $uom)
                                <option @if($sub_indicator->unit_measure_id == $uom->id) selected @endif value="{{$uom->id}}">{{$uom->title}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('unit_measure'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('unit_measure') }}</small>
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