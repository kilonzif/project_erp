<div class="card-header">
    <h4 class="card-title">Add Role</h4>
    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
    <div class="heading-elements">
        <ul class="list-inline mb-0">
            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
        </ul>
    </div>
</div>
<div class="card-content collapse show">
    <div class="card-body">
        <form action="{{route('user-management.roles.create')}}" method="post">
            @csrf
            <div id="action-form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role_name">Role Name</label>
                            <input type="text" required min="2" name="role_name" class="form-control" id="role_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="display_name">Display Name</label>
                            <input type="text" name="display_name" class="form-control" id="display_name">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{--<label for="role_desp">Role Description</label>--}}
                            <input type="text" name="role_desp" placeholder="Role Description" class="form-control" id="role_desp">
                        </div>
                    </div>
                </div>
            </div>

            @if($permissions->count() > 0)
                <div class="form-group">
                    <select multiple="multiple" name="permissions[]" size="10" class="duallistbox">
                        @foreach($permissions as $permission)
                            <option value="{{$permission->id}}">{{$permission->name}}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="form-group">
                    <label for="message">No permissions set</label>
                </div>
            @endif
            <div class="form-group">
                <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                    Save</button>
            </div>
        </form>
    </div>
</div>
