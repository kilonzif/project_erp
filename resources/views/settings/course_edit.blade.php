<div class="card" id="course-card">
    <div class="card-header">
        <h4 class="card-title">Update Programme</h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="card-content collapse show">
        <div class="card-body">
            <form action="{{route('settings.course.update')}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$course->id}}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" min="3" placeholder="name"
                                   value="{{ old('name') ? old('name') : $course->name }}"
                                   name="name" class="form-control" id="name">
                            @if ($errors->has('name'))
                                <p class="text-right">
                                    <small class="warning text-muted">{{ $errors->first('name') }}</small>
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
</div>