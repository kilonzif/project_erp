<div class="card-header">
    <h4 class="card-title">Editing Indicator</h4>
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
        <form action="{{route('indicator.update')}}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{$indicator->id}}">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Title <span class="required">*</span></label>
                        <input type="text" required min="5" value="{{ (old('title')) ? old('title') : $indicator->title }}" placeholder="Indicator Title" name="title" class="form-control" id="title">
                        @if ($errors->has('title'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('title') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="unit_of_measure">Unit of Measure <span class="required">*</span></label>
                        <input type="text" required min="3" placeholder="Unit of Measure" name="unit_of_measure" class="form-control"
                               value="{{ (old('unit_of_measure')) ? old('unit_of_measure') : $indicator->unit_measure }}"  id="unit_of_measure">
                        @if ($errors->has('unit_of_measure'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('unit_of_measure') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="identifier">Identifier <span class="required">*</span></label>
                        <input type="text" required name="identifier" min="1" placeholder="Identifier eg. 1 or A"
                               value="{{ (old('identifier')) ? old('identifier') : $indicator->identifier }}" class="form-control" id="identifier">
                        @if ($errors->has('identifier'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('identifier') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="order_no">Order No. <span class="required">*</span></label>
                        <input type="number" required name="order_no" min="1" placeholder="Order No."
                               value="{{ (old('order_no')) ? old('order_no') : $indicator->order_no }}" class="form-control" id="order_no">
                        @if ($errors->has('order_no'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('order_no') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="on_report">Show on Report? <span class="required">*</span></label>
                        <select name="on_report" id="on_report" class=" form-control" style="width: 100%;">
                            <option @if($indicator->show_on_report == 1) selected @endif value="1">Yes</option>
                            <option @if($indicator->show_on_report == 0) selected @endif value="0">No</option>
                        </select>
                        @if ($errors->has('on_report'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('on_report') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="project">Parent Indicator</label>
                        <select name="parentIndicator" id="parentIndicator" class=" form-control" style="width: 100%;">
                            <option @if($indicator->isparent == 0) selected @endif value="0">NONE</option>
                            @foreach($indicators as $activeIndicator)
                                <option @if($indicator->parent_id == $activeIndicator->id) selected @endif value="{{$activeIndicator->id}}">{{$activeIndicator->title}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('parentIndicator'))
                            <p class="text-right">
                                <small class="warning text-muted">{{ $errors->first('parentIndicator') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="d-inline-block custom-control custom-checkbox mr-1">
                            <input type="checkbox" class="custom-control-input"  @if($indicator->upload == 1) checked @endif
                            value="1" name="upload" id="upload">
                            <label class="custom-control-label" for="upload">Requires indicator uploads.</label>
                        </div>
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