@extends('layouts.app')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item active">File Uploads
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Upload Additional Files Here</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-content">
                            <div class="card-body">
                                <form action="{{route('file-uploads.save')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label>Ace</label>
                                            <select class="form-control" name="ace_id">
                                                <option value="">Select ACE</option>
                                                @foreach($aces as $ace)
                                                    <option value="{{$ace->id}}">{{$ace->acronym}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group{{ $errors->has('submission_date') ? ' form-control-warning' : '' }}">
                                                <label for="ss_submission_date">Submission Date <span class="required">*</span></label>
                                                <input type="date" class="form-control" required name="submission_date"
                                                       id="submission_date" value="#">
                                                @if ($errors->has('submission_date'))
                                                    <p class="text-right">
                                                        <small class="warning text-muted">{{ $errors->first('submission_date') }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Category</label>
                                            <input type="text" name="file_category" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('file_one')? 'form-control-warning':'' }}">
                                                <label for="file_one">File 1 <span class="required">*</span></label>
                                                <input type="file" class="form-control" name="file_one" required  id="file_one"
                                                       value="old('file_one')">
                                                @if ($errors->has('wp_file'))
                                                    <p class="text-right">
                                                        <small class="warning text-muted">{{ $errors->first('file_one') }}</small>
                                                    </p>
                                                @endif

                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('file_two')? 'form-control-warning':'' }}">
                                                <label for="wp_file">File 2</label>
                                                <input type="file" class="form-control" name="file_two"  id="file_two"
                                                       value="old('file_two')">
                                                @if ($errors->has('file_two'))
                                                    <p class="text-right">
                                                        <small class="warning text-muted">{{ $errors->first('file_two') }}</small>
                                                    </p>
                                                @endif

                                            </div>

                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group{{ $errors->has('comments') ? ' form-control-warning' : '' }}">
                                                <label for="comments1">Comments</label>
                                                <textarea class="form-control" placeholder="Comments" id="comments1" name="comments"></textarea>
                                                @if ($errors->has('comments'))
                                                    <p class="text-right">
                                                        <small class="warning text-muted">{{ $errors->first('comments') }}</small>
                                                    </p>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                                                    Save</button>
                                            </div>
                                        </div>

                                    </div>

                                </form><br>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-heading">
                            Documents <i class="ft-file"></i>
                        </h4>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>ACE</th>
                            <th>Category</th>
                            <th>Files</th>
                            <th>Comment Section</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                        @foreach($uploads as $up)
                            <tr>
                                @php
                                    $aces = \App\Ace::where('id','=',$up->ace_id)->first();
                                $ace_name=$aces->name;

                                @endphp
                                <td>{{$ace_name}}</td>
                                <td>{{$up->file_category}}</td>
                                <td>
                                    @isset($up->file_one)
                                    <a href="{{asset('indicator1/'.$up->file_one)}}" target="_blank">
                                        <span class="fa fa-file"></span>   {{$up->file_one}}
                                    </a>
                                    @endisset

                                <br>
                                        @isset($up->file_two)
                                    <a href="{{asset('indicator1/'.$up->file_two)}}" target="_blank">
                                        <span class="fa fa-file"></span>   {{$up->file_two}}
                                    </a>
                                            @endisset

                                </td>
                                <td>{{$up->comments}}</td>
                                <td>
                                    {{--<a href="#edit_contact" onclick="edit_workplan('{{\Illuminate\Support\Facades\Crypt::encrypt($wp->id)}}')" >--}}
                                    {{--<i class="ft-edit blue"></i></a>--}}
                                    <a class="danger" href="{{route('file-uploads.delete',[\Illuminate\Support\Facades\Crypt::encrypt($up->id)])}}"
                                       data-toggle="tooltip" data-placement="top" onclick="return confirm('Are you sure you want to delete this Workplan?');"
                                       title="Delete Report"><i class="ft-trash-2"></i></a>
                                </td>
                            </tr>

                        @endforeach
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

@endsection

@push('end-script')

@endpush