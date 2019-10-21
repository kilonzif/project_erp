<div class="card-header">
    <h4 class="card-title">Editing Comment</h4>
    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
    <div class="heading-elements">
        <ul class="list-inline mb-0">
            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
        </ul>
    </div>
</div>
<div class="card-content collapse show">
    <div class="card-body card-dashboard">
        <form action="{{Route('report_form_comment.update')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($comment->id)}}">
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    <div class="form-group">
                        <label for="name">Comment <span class="required"></span> </label>
                        <textarea class="form-control" placeholder="Comment" name="ace_comment" >{{$comment->comments}}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                    Update Comment</button>
            </div>
        </form>
    </div>
</div>