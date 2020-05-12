<section class="chat-app-form" style="
    position: absolute;
    left: 0;
    right: 0;
    padding: 10px;
     background:none;
    overflow: hidden;">
    <form class="chat-app-input" method="POST" action="{{ url('comments') }}">
        @csrf
        <input type="hidden" name="commentable_type" value="\{{ get_class($model) }}" />
        <input type="hidden" name="commentable_id" value="{{ $model->id }}" />
        <fieldset>
            <div class="input-group">
                <input type="text" class="form-control" name="message" placeholder="Type Your Message" aria-describedby="button-addon2">
                <div class="input-group-append" id="button-addon2">
                    <button class="btn btn-primary btn-submit" id="loader" type="submit"><i class="fa fa-spinner spinner " style=" display: none;"></i> Send</button>
                </div>
            </div>
        </fieldset>
    </form>
</section>

@push('end-script')
    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".btn-submi").click(function(e){

            e.preventDefault();

            var message = $("input[name=message]").val();
            var commentable_type=$("input[name=commentable_type]").val();
            var commentable_id=$("input[name=commentable_id]").val();

            var url = "{{ url('comments') }}";

            $.ajax({
                type:'POST',
                url:url,
                data:{
                    message:message,
                    commentable_type:commentable_type,
                    commentable_id:commentable_id
                },
                beforeSend: function(){
                    $('#loader>i').css("display","block");
                },
                success:function(data){
                    $("dl").append("<dt>"+ data.view +"</dt>");
                },
                complete: function(){
                    $('#loader>i').css("display","none")
                },
                error:function(data){
                    alert(data.error);
                }
            });
        });
    </script>
@endpush


