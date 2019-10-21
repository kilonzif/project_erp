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
        <form action="{{Route('save_comment_feedback')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    <div class="form-group">
                        <label for="name">Comment <span class="required"></span> </label>
                        <textarea class="form-control" placeholder="Comment" name="ace_comment" value="{{}}"></textarea>
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
