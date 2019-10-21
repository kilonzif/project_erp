@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
@endpush
@push('other-styles')
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
@endpush
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card" id="add-box">
                <h6 class="card-header p-1 card-head-inverse bg-primary" style="border-radius:0">
                    Comments/Feedback
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                        </ul>
                    </div>
                </h6>
                <div class="card-content collapse">
                    <div class="card-body table-responsive">
                        <form action="{{Route('save_comment_feedback')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="user_id" value="{{$user_id}}">
                                    <div class="form-group">
                                        <label for="name">Comment <span class="required"></span> </label>
                                        <textarea class="form-control" placeholder="Comment" name="ace_comment"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                                    Save</button>
                            </div>
                        </form>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="card col-md-12">
            <h6 class="card-header p-1 card-head-inverse bg-primary" style="border-radius:0">
                Comments/Feedback
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                    </ul>
                </div>
            </h6>
            <div class="card-content">
                <div class="card-body table-responsive">

                    <table class="table table-bordered table-striped">
                        <thead style="font-style:italic">

                        <td>Ace</td>
                        <td>Ace Officer</td>
                        <td>Comment/Feedback</td>
                        <td>Action</td>
                        </thead>

                        @foreach($comments as $comment)
                            @php
                                $count = 0;
                            @endphp
                            <tr>
                                <td>{{$ace_name[$count]}}</td>
                                <td>{{$ace_officer[$count]}}</td>
                                <td>{{$comment->comments}}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('report_form_comment.delete',  [Crypt::encrypt($comment->id)] ) }}" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Contact"><i class="ft-trash-2"></i></a></a>
                                        {{--                                        <a href="{{ route('report_form_comment.edit',  [Crypt::encrypt($comment->id)] ) }}" class="btn btn-s btn-primary" data-toggle="tooltip" data-placement="top" title="Edit Contact"><i class="ft-edit-2"></i></a></a>--}}
                                        <a href="#add-box" onclick="edit_ace_comment('{{\Illuminate\Support\Facades\Crypt::encrypt($comment->id)}}')" class="btn btn-s btn-secondary">
                                            <i class="ft-edit"></i></a>
                                    </div>
                                </td>

                            </tr>

                            @php
                                $count++;
                            @endphp
                        @endforeach
                    </table>

                </div>
            </div>
        </div>

    </div>



@endsection

<script>
    function edit_ace_comment(key) {

        var path = "{{route('report_form_comment.edit')}}";
        $.ajaxSetup(    {
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            }
        });
        $.ajax({
            url: path,
            type: 'GET',
            data: {id:key},
            beforeSend: function(){
                $('#add-box').block({
                    message: '<div class="ft-loader icon-spin font-large-1"></div>',
                    overlayCSS: {
                        backgroundColor: '#ccc',
                        opacity: 0.8,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'transparent'
                    }
                });;
            },
            success: function(data){
                $('#add-box').empty();
                $('#add-box').html(data.theView);
                // console.log(data)
            },
            complete:function(){
                $('#add-box').unblock();
            }
            ,
            error: function (data) {
                console.log(data)
            }
        });
    }
</script>
