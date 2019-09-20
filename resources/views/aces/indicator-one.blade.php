@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">ACEs</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('user-management.aces')}}">ACEs</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('user-management.aces.profile',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">
                                {{$ace->acronym}}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Indicator #1 (Institutional Readiness)
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-md-12">
                @csrf
                <div class="card mb-1">
                    <h6 class="card-header p-1 card-head-inverse bg-amber bg-accent-4 white" style="border-radius:0">
                        {{$ace->name}} - Institutional Readiness (Indicator #1)
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </h6>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="row">
                                {{--@if (\Auth::user()->hasRole('webmaster|super-admin'))--}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="submission_period">Select Ace <span class="required">*</span></label>
                                        <select name="ace_id" disabled class="form-control select2" id="ace_id" required>
                                            <option value="">Select Ace</option>
                                            @foreach($all_aces as $this_ace)
                                                <option @if($ace->id == $this_ace->id) selected="selected" @endif value="{{\Illuminate\Support\Facades\Crypt::encrypt($this_ace->id)}}">{{$this_ace->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('ace_id'))
                                            <p class="text-right">
                                                <small class="warning text-muted">{{ $errors->first('ace_id') }}</small>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h4 class="card-title">INDICATOR 1(INSTITUTION READINESS)</h4>
                    </div>
                </div>


                {{ csrf_field() }}

                <div class="row">
                    <div class="col-12">
                        {{--@php--}}
                        {{--if(!empty($indicator_ones)){--}}
                         {{--if(sizeof($indicator_ones)!=sizeof($labels)){--}}
                         {{--dd($labels);--}}

                        {{--}--}}
                        {{--dd("jhjhdf");--}}
                        {{--}            --}}
                        {{--@endphp--}}
                        {{--@if(!empty($indicator_ones))--}}
                            {{--@foreach($labels as $key=>$currentRequirement)--}}
                                {{--@foreach($indicator_ones as $i)--}}

                                {{--@endforeach--}}
                            {{--@endforeach--}}

                        @foreach($labels as $key=>$currentRequirement)
                                    @php
                                    try{
                                        $values=($indicator_ones[$key]);
                                    }catch (Exception $E){
                                        $values=[];
                                  }

                                    @endphp

                            <div class="card" id="action-card">
                                <div class="card-header">
                                    <h4 class="card-title">{{$key}}</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">

                                        <form action="{{route('user-management.ace.indicator_one.save',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="requirement[]" value="{{$key}}" >
                                            <div class="row">

                                                @if($labels[$key]['submission_date'])
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Submission Date:</label>
                                                            <input type="date" class="form-control" name="submission_date[]"  id="submission_date" value="{{old('submission_date1[]',empty($values[0]['submission_date'])?"":$values[0]['submission_date'])}}">
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($labels[$key]['file1'])
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>File 1 Upload:</label>
                                                            <input type="file" class="form-control" name="file_one[]" value="{{old('file_one',empty($values[0]['file_one'])?"":$values[0]['file_one'])}}">
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($labels[$key]['file2'])
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>File 1 Upload:</label>
                                                            <input type="file" class="form-control" id="file_two[]" name="file_two[]" value="{{old('file_two',empty($values[0]['file_two'])?"":$values[0]['file_two'])}}">
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($labels[$key]['url'])
                                                    <div class="col-md-6">
                                                        <div class="form-group{{ $errors->has('url[]') ? ' form-control-warning' : '' }}">
                                                            <label>URL:</label>
                                                            <input type="text" name="url[]" placeholder="url" class="form-control"  value="{{ old('url',empty($values[0]['url'])?"":$values[0]['url'])}}" id="url">
                                                            @if ($errors->has('url[]'))
                                                                <p class="text-right">
                                                                    <small class="warning text-muted">{{ $errors->first('url[]') }}</small>
                                                                </p>
                                                            @endif

                                                        </div>
                                                    </div>
                                                @endif
                                                @if($labels[$key]['comments'])
                                                    <div class="col-md-12">
                                                        <div class="form-group{{ $errors->has('comments[]') ? ' form-control-warning' : '' }}">
                                                            <label for="comments1">Comments</label>
                                                            <input type="text" name="comments[]" placeholder="comments" class="form-control"  value="{{ old('comments',empty($values[0]['comments'])?"":$values[0]['comments'])}}" id="comments1">
                                                            @if ($errors->has('comments[]'))
                                                                <p class="text-right">
                                                                    <small class="warning text-muted">{{ $errors->first('comments[]') }}</small>
                                                                </p>
                                                            @endif

                                                        </div>
                                                    </div>
                                                @endif

                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                                                            Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>





        </div>
    </div>









@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
@endpush

