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
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
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
                        @foreach($labels as $key=>$currentRequirement)
                            @php
                                try{
                                    $values=($indicator_ones[$key]);
                                }catch (Exception $E){
                                    $values=[];
                              }
                            @endphp

                            <div class="card" id="card_{{$key}}">
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
                                            <input type="hidden" name="card_id" value="card_{{$key}}">
                                            <div class="row">

                                                @if($labels[$key]['submission_date']['show'])
                                                    <div class="col-md-4">
                                                        <div class="form-group{{ $errors->has('submission_date[]') ? ' form-control-warning' : '' }}">
                                                            <label for="submission_date">Submission Date <span class="required">*</span></label>
                                                            <input type="date" class="form-control" name="submission_date[]"  id="submission_date"
                                                                   @if($labels[$key]['submission_date']['required']) required @endif
                                                                   value="{{old('submission_date1[]',empty($values[0]['submission_date'])?"":$values[0]['submission_date'])}}">
                                                            @if ($errors->has('submission_date[]'))
                                                                <p class="text-right">
                                                                    <small class="warning text-muted">{{ $errors->first('submission_date[]') }}</small>
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($labels[$key]['file1'])
                                                    <div class="col-md-6">
                                                        <div class="form-group {{ $errors->has('file_one[]')? 'form-control-warning':'' }}">
                                                            @if($key=="PROCEDURES MANUAL")<label>Financial Management Manual<span class="required">*</span></label>@elseif($key=="IMPLEMENTATION PLAN")<label>Implementation Plan<span class="required">*</span></label>@else<label>File 1 Upload<span class="required">*</span></label> @endif
                                                            <input type="file" class="form-control" name="file_one[]" required  id="filename{{$key[0]}}"
                                                                   @if($labels[$key]['file1']['required']) required @endif
                                                                   value="{{old('file_one',empty($values[0]['file_one'])?"":$values[0]['file_one'])}}">

                                                            @if ($errors->has('file_one[]'))
                                                                <p class="text-right">
                                                                    <small class="warning text-muted">{{ $errors->first('file_one[]') }}</small>
                                                                </p>
                                                            @endif

                                                                @if(isset($values[0]['file_one']) && is_file('indicator1/'.$values[0]['file_one']))
                                                                    <a href="{{asset('indicator1/'.$values[0]['file_one'])}}" target="_blank">
                                                                        <span class="fa fa-file">  </span>   Download uploaded file
                                                                    </a>
                                                                @endif
                                                        </div>

                                                    </div>
                                                @endif

                                                @if($labels[$key]['file2'])
                                                    <div class="col-md-6">
                                                        <div class="form-group {{ $errors->has('file_two[]')? 'form-control-warning':'' }}">
                                                            @if($key=="PROCEDURES MANUAL")<label>Procurement Manual<span class="required">*</span></label>@else<label>File 2 Upload<span class="required">*</span></label>@endif

                                                               <input type="file" class="form-control" required id="file_two[]"
                                                                   @if($labels[$key]['file2']['required']) required @endif
                                                                   name="file_two[]" value="{{old('file_two',empty($values[0]['file_two'])?"":$values[0]['file_two'])}}">
                                                            @if ($errors->has('file_two[]'))
                                                                <p class="text-right">
                                                                    <small class="warning text-muted">{{ $errors->first('file_two[]') }}</small>
                                                                </p>
                                                            @endif

                                                                @if(isset($values[0]['file_two']) && is_file('indicator1/'.$values[0]['file_two']))
                                                                    <a href="{{asset('indicator1/'.$values[0]['file_two'])}}" target="_blank">
                                                                        <span class="fa fa-file"></span>    Download the uploaded file
                                                                    </a>
                                                                @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($labels[$key]['url'])
                                                    <div class="col-md-6">
                                                        <div class="form-group{{ $errors->has('url[]') ? ' form-control-warning' : '' }}">
                                                            <label>URL<span class="required">*</span></label>
                                                            <input type="text" name="url[]" required placeholder="url" class="form-control"
                                                                   @if($labels[$key]['url']['required']) required @endif
                                                                   value="{{ old('url',empty($values[0]['url'])?"":$values[0]['url'])}}" id="url">
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
                                                                <textarea class="form-control" placeholder="Comments" id="comments1" name="comments[]">{{ old('comments',empty($values[0]['comments'])?"":$values[0]['comments'])}}</textarea>
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
                                                        <button class="btn btn-secondary square" href="#card_{{$key}}" type="submit"><i class="ft-save mr-1"></i>
                                                            Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        @endforeach


                        {{--sectoral board--}}
                            <div class="card" id="action-card">
                                <div class="card-header">
                                    <h4 class="card-title">SECTORAL ADVISORY BOARD</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body" id="card_sectoralboard">

                                        <form action="{{route('user-management.ace.indicator_one.sectoral_board',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="ss_requirement" value="SECTORAL ADVISORY BOARD">
                                            <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group{{ $errors->has('ss_submission_date') ? ' form-control-warning' : '' }}">
                                                            <label for="ss_submission_date">Submission Date <span class="required">*</span></label>
                                                            <input type="date" class="form-control" required name="ss_submission_date"
                                                                   id="ss_submission_date" value="{{ old('ss_submission_date',empty($sectoral_board->submission_date))?"":$sectoral_board->submission_date}}">
                                                            @if ($errors->has('ss_submission_date'))
                                                                <p class="text-right">
                                                                    <small class="warning text-muted">{{ $errors->first('ss_submission_date') }}</small>
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group {{ $errors->has('ss_file_one')? 'form-control-warning':'' }}">
                                                            <label for="ss_file_one">Minutes of 1st Sectorial Advisory Board meetings <span class="required">*</span><span class="warning text-muted">{{__('PDF file')}}</span></label>
                                                            <input type="file" class="form-control" name="ss_file_one" required  id="filename1"
                                                                   value="{{old('ss_file_one',empty($sectoral_board->file_one))?"":$sectoral_board->file_one}}">
                                                            @if ($errors->has('ss_file_one'))
                                                                <p class="text-right">
                                                                    <small class="warning text-muted">{{ $errors->first('ss_file_one') }}</small>
                                                                </p>
                                                            @endif
                                                            @if(!empty($sectoral_board) && $sectoral_board->file_one !="")
                                                                <strong>Minutes of 1st Sectorial Advisory Board meetings</strong>
                                                                <a href="{{asset('indicator1/'.$sectoral_board->file_one)}}" target="_blank">
                                                                    <span class="fa fa-file"></span>   Download file
                                                                </a>
                                                                <br>
                                                            @endif

                                                        </div>

                                                    </div>
                                                <div class="col-md-4">
                                                    <div class="form-group {{ $errors->has('ss_file_two')? 'form-control-warning':'' }}">
                                                        <label for="ss_file_one">List of Board Members<span class="required">*</span><span class="warning text-muted">{{__('Excel (.xlsx) file')}}</span></label>
                                                        <input type="file" class="form-control" name="ss_file_two" required  id="filename2"
                                                               value="{{old('ss_file_two',empty($sectoral_board->ss_file_two))?"":$sectoral_board->ss_file_two}}">
                                                        @if ($errors->has('ss_file_two'))
                                                            <p class="text-right">
                                                                <small class="warning text-muted">{{ $errors->first('ss_file_two') }}</small>
                                                            </p>
                                                        @endif
                                                        @if(!empty($sectoral_board) && $sectoral_board->file_two !="")
                                                            <strong>List of the members of the board</strong>
                                                            <a href="{{asset('indicator1/'.$sectoral_board->file_two)}}" target="_blank">
                                                                <span class="fa fa-file"></span>   Download file
                                                            </a>
                                                            <br>
                                                        @endif

                                                    </div>

                                                </div>

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



                    </div>

                </div>
            </div>





        </div>
    </div>









@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
    <script>
        function changeFile(key) {
            $('#'+key).show();
        }

    </script>
@endpush

