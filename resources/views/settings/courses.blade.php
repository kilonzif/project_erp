@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
@endpush
@push('other-styles')
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item">Settings
                        </li>
                        <li class="breadcrumb-item active">Programmes
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card" id="course-card">
                    <div class="card-header">
                        <h4 class="card-title">Programme</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form action="{{route('settings.course.add')}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" min="3" placeholder="name" value="{{ old('name') }}" name="name" class="form-control" id="name">
                                            @if ($errors->has('name'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('name') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    {{--<div class="col-md-4">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="code">Code</label>--}}
                                            {{--<input type="text" min="3" placeholder="Code" value="{{ old('code') }}" name="code" class="form-control" id="code">--}}
                                            {{--@if ($errors->has('code'))--}}
                                                {{--<p class="text-right">--}}
                                                    {{--<small class="warning text-muted">{{ $errors->first('code') }}</small>--}}
                                                {{--</p>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                                        Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Programmes</h4>
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
                            <table class="table table-striped table-bordered all_programmes">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th style="width: 50px;">Edit</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($courses as $course)
                                    <tr>
                                        {{--<td>{{$indicator->number}}</td>--}}
                                        <td>{{$course->name}}</td>
                                        <td>
                                            <a href="#course-card" onclick="edit_course({{$course->id}})" class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top"
                                               title="Edit Programme"><i class="ft-edit-3"></i></a></a>
                                        </td>
{{--                                        <td>{{$course->code}}</td>--}}
                                        {{--<td>--}}
                                            {{--<div class="btn-group" role="group" aria-label="Basic example">--}}
                                                {{--<a href="#course-card" disabled class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Course"><i class="ft-edit-3"></i></a></a>--}}
                                                {{--<a href="#" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="Indicator Status"><i class="ft-times"></i></a></a>--}}
                                                {{--<a class="dropdow-item btn {{($indicator->status == 0)?'btn-success' : 'btn-danger'}} btn-s" href="#"--}}
                                                   {{--data-toggle="tooltip" data-placement="top" title="{{($indicator->status == 0)?'Activate Indicator' : 'Deactivate Indicator'}}"--}}
                                                   {{--onclick="event.preventDefault(); document.getElementById('delete-indicator-{{$count}}').submit();">--}}
                                                    {{--@if($indicator->status == 0)--}}
                                                        {{--<i class="ft-check"></i>--}}
                                                    {{--@else--}}
                                                        {{--<i class="ft-x"></i>--}}
                                                    {{--@endif--}}
                                                {{--</a>--}}
                                            {{--</div>--}}
                                            {{--<form id="delete-indicator-{{$count}}" action="{{ route('indicator.activate',[\Illuminate\Support\Facades\Crypt::encrypt($indicator->id)]) }}" method="POST" style="display: none;">--}}
                                                {{--@csrf {{method_field('DELETE')}}--}}
                                                {{--<input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($indicator->id)}}">--}}
                                            {{--</form>--}}
                                        {{--</td>--}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')

    <script>
        $('.all_programmes').dataTable();

        //Script to call the edit view for user
        function edit_course(key) {

            var path = "{{route('settings.course_edit_view')}}";
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
                    $('#course-card').block({
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
                    });
                },
                success: function(data){
                    $('#course-card').empty();
                    $('#course-card').html(data.theView);
                    // console.log(data)
                },
                complete:function(){
                    $('#course-card').unblock();
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }
    </script>
@endpush