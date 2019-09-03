{{-- <div class="card">
    <div class="card-body">
        <form method="POST" action="{{ url('comments') }}">
            @csrf
            <input type="hidden" name="commentable_type" value="\{{ get_class($model) }}" />
            <input type="hidden" name="commentable_id" value="{{ $model->id }}" />
            <div class="form-group">
                <label for="message">Enter your message here:</label>
                <textarea class="form-control @if($errors->has('message')) is-invalid @endif" name="message" rows="3"></textarea>
                <div class="invalid-feedback">
                    Your message is required.
                </div>

            </div>
            <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">Submit</button>
        </form>
    </div>
</div>
<br /> --}}

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
                          <button class="btn btn-primary btn-submit" id="loader" type="submit"><i class="fa fa-spinner spinner " style=" display: none;"></i>    Send</button>
                        </div>
                      </div>
                    </fieldset>

              {{-- <fieldset class="form-group position-relative has-icon-left col-10 m-0">

                <input type="text" class="form-control" id="iconLeft4" name="message" placeholder="Type your message">


                <div class="form-control-position control-position-right">
                  <i class="ft-image"></i>
                </div>
              </fieldset>

              <fieldset class="form-group position-relative has-icon-left col-2 m-0">
                <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o d-lg-none"></i>
                  <span class="d-none d-lg-block">Submit</span>
                </button>
              </fieldset> --}}



                {{-- <fieldset class="form-group position-relative has-icon-left col-2 m-0">
                      <div class="input-group">
                        <input type="text" class="form-control" placeholder="Addon To Right" aria-describedby="basic-addon4">
                        <div class="input-group-append">
                          <span class="input-group-text" id="basic-addon4"><i class="ft-user"></i></span>
                        </div>
                      </div>
                    </fieldset> --}}





            </form>
          </section>



@push('end-script')

<script type="text/javascript">

    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }

    });

    $(".btn-submit").click(function(e){

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
                    //console.log('before');
                },




           success:function(data){

               // $("ls").append( "");
              // $("ul").append("<li> " + message + " </li>");

               $("dl").append("<dt>"+ data.view +"</dt>");

             // $("ul").append("<dl><dt>"message"</dt></dl>");
             // alert(data.success);


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


