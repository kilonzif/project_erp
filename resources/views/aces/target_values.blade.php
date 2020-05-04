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
                        <li class="breadcrumb-item active">Targets
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-md-12">
                @if($project->indicators->count() > 0)
                    <form action="{{route('user-management.ace.targets.save',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id),$year_id])}}"
                          id="indicators-form" method="post">
                        @csrf
                        <div class="card mb-1">
                            <h6 class="card-header p-1 card-head-inverse bg-yellow bg-darken-3 white" style="border-radius:0">
                                {{$ace->name}} - Target Values
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="submission_period">Select Ace <span class="required">*</span></label>
                                                <select name="ace_id" disabled class="form-control select2" id="ace_id" required>
                                                    <option value="" selected disabled>Select Ace</option>
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="reporting_year">Select Year <span class="required">*</span></label>
                                                <select name="reporting_year" class="form-control" required id="reporting_year">
                                                    <option value="">Select Year</option>
                                                    @php
                                                        $reporting_year_start = config('app.reporting_year_start');
                                                        $reporting_year_length = config('app.reporting_year_length');
                                                        $year = null;
                                                        if(isset($getYear)) {
                                                            $year = $getYear->reporting_year;
                                                        }
                                                    @endphp
                                                    @for($a=$reporting_year_start;$a < $reporting_year_length+$reporting_year_start; $a++)
                                                        @if($year)
                                                            <option value="{{$a}}" {{($year == "$a")?'selected':''}}>{{$a}}</option>
                                                        @else
                                                            <option value="{{$a}}" {{(old('reporting_year') == "$a")?'selected':''}}>{{$a}}</option>
                                                        @endif
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        {{--<div class="col-md-3">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label for="submission_period">Start Period<span class="required">*</span></label>--}}
                                                {{--@if(isset($getYear))--}}
                                                    {{--<input type="date" required value="{{old('start')?old('start'): $getYear->start_period}}"--}}
                                                           {{--name="start" class="form-control" id="start">--}}
                                                {{--@else--}}
                                                    {{--<input type="date" required value="{{old('start')?old('start'):""}}"--}}
                                                           {{--name="start" class="form-control" id="start">--}}
                                                {{--@endif--}}

                                                {{--@if ($errors->has('start'))--}}
                                                    {{--<p class="text-right">--}}
                                                        {{--<small class="warning text-muted">{{ $errors->first('start') }}</small>--}}
                                                    {{--</p>--}}
                                                {{--@endif--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-3">--}}
                                            {{--<label for="submission_period">End Period <span class="required">*</span></label>--}}
                                            {{--@if(isset($getYear))--}}
                                                {{--<input type="date" required value="{{old('end')?old('end'): $getYear->end_period}}"--}}
                                                   {{--name="end" class="form-control" id="end">--}}
                                            {{--@else--}}
                                                {{--<input type="date" required value="{{old('end')?old('end'):""}}"--}}
                                                       {{--name="end" class="form-control" id="end">--}}
                                            {{--@endif--}}
                                            {{--@if ($errors->has('end'))--}}
                                                {{--<p class="text-right">--}}
                                                    {{--<small class="warning text-muted">{{ $errors->first('end') }}</small>--}}
                                                {{--</p>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--@php--}}
                            {{--$indicators = $project->indicators->where('parent_id','=',0)->where('status','=',1);--}}
                        {{--@endphp--}}
                        <div class="row">
                            @foreach($indicators as $indicator)
                                <div class="col-md-6">
                                    <div class="card mb-1">
                                        <h6 class="card-header p-1 card-head-inverse bg-yellow bg-darken-3 white" style="border-radius:0">
                                            {{--<h6 class="card-title"></h6>--}}
                                            <strong>{{"Indicator ".$indicator->identifier." - ".$indicator->title}}</strong>
                                            {{--<br>--}}
                                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                            <div class="heading-elements">
                                                <ul class="list-inline mb-0">
                                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                                </ul>
                                            </div>
                                        </h6>
                                        <div class="card-content collapse show">
                                            <div class="card-body table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    @if($indicator->indicators->count() > 0)
                                                        @php
                                                            $sub_indicators = $indicator->indicators->where('status','=',1);
                                                        @endphp
                                                        @foreach($sub_indicators as $sub_indicator)
                                                            <tr>
                                                                <td>{{$sub_indicator->title}} <span class="required">*</span></td>
                                                                <td style="width: 150px">
                                                                    <div class="form-grou{{ $errors->has('indicators.'.$sub_indicator->id) ? ' form-control-warning' : '' }}">

                                                                        @if(isset($values[$sub_indicator->id]))
                                                                            <input type="number" step="0.01" min="0" id="indicator_{{$sub_indicator->id}}"
                                                                                   class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}" placeholder="Eg. 1"
                                                                                   value="{{old('indicators.'.$sub_indicator->id)?old('indicators.'.$sub_indicator->id):$values[$sub_indicator->id]}}"
                                                                                   required name="indicators[{{$sub_indicator->id}}]">
                                                                        @else
                                                                            <input type="number" step="0.01" min="0" id="indicator_{{$sub_indicator->id}}"
                                                                                   class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$sub_indicator->id) ? ' is-invalid' : '' }}" placeholder="Eg. 1"
                                                                                   value="{{old('indicators.'.$sub_indicator->id)?old('indicators.'.$sub_indicator->id):''}}"
                                                                                   required name="indicators[{{$sub_indicator->id}}]">
                                                                        @endif

                                                                        @if ($errors->has('indicators.'.$sub_indicator->id))
                                                                            <p class="text-right mb-0">
                                                                                <small class="warning text-muted">{{ $errors->first('indicators.'.$sub_indicator->id) }}</small>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="form-grou{{ $errors->has('indicators.'.$indicator->id) ? ' form-control-warning' : '' }}">
                                                                    @if(isset($values[$indicator->id]))
                                                                        <input type="number" step="0.01" min="0" id="indicator_{{$indicator->id}}" name="indicators[{{$indicator->id}}]"
                                                                               value="{{old('indicators.'.$indicator->id)?old('indicators.'.$indicator->id):$values[$indicator->id]}}"
                                                                               class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$indicator->id) ? ' is-invalid' : '' }}"
                                                                               required placeholder="Eg. 1">
                                                                    @else
                                                                        <input type="number" step="0.01" min="0" id="indicator_{{$indicator->id}}" name="indicators[{{$indicator->id}}]"
                                                                               value="{{old('indicators.'.$indicator->id)?old('indicators.'.$indicator->id): ''}}"
                                                                               class="form-control frm-control-sm-custom{{ $errors->has('indicators.'.$indicator->id) ? ' is-invalid' : '' }}"
                                                                               required placeholder="Eg. 1">
                                                                    @endif
                                                                    @if ($errors->has('indicators.'.$indicator->id))
                                                                        <p class="text-right mb-0">
                                                                            <small class="warning text-muted">{{ $errors->first('indicators.'.$indicator->id) }}</small>
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <button type="submit" name="submit" value="complete" class="btn btn-secondary square mb-2"> <i class="ft-check-circle"></i> Save</button>
                            </div>
                        </div>
                    </form>
                @else
                    <h2 class="center">No Indicators available</h2>
                @endif
            </div>

        </div>

    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>
    <script>

        $('.select2').select2({
            placeholder: "Select Courses",
            allowClear: true
        });

    </script>
@endpush