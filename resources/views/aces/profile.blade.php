@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/extended/form-extended.css')}}">
    <style>
        span.symbol{
            font-size: 12px;
        }
    </style>
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
                        <li class="breadcrumb-item active">{{$ace->acronym}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-blue bg-darken-4 white">
                        <h4 class="card-title">{{$ace->name}}</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">

                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td style="width: 50px;"><strong>Acronym</strong><br>{{$ace->acronym}}</td>
                                    <td><strong>Institution</strong><br>{{$ace->university->name}}</td>
                                    <td style="width: 50px;"><strong>Contact</strong><br>{{$ace->contact}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong><br>{{$ace->email}}</td>
                                    <td><strong>Contact Person</strong><br>{{$ace->contact_person ." - ".$ace->person_number.
                                    " - ".$ace->person_email." - ".$ace->position}}</td>
                                    <td><strong>Field</strong><br>{{$ace->field}}</td>
                                </tr>
                            </table>

                            <div class="row">
                                <div class="col-md-3 text-left">
                                    <a class="btn btn-secondary square ml-3" href="{{route('user-management.ace.indicator_one',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">
                                        <i class="ft-plus-circle"></i>Institutional Readiness (Indicator 1)
                                    </a>
                                </div>
                                <div class="col-md-3 text-right">
                                    <a class="btn btn-primary square ml-3"href="{{route('user-management.ace.baselines',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">
                                        <i class="ft-plus-circle"></i>Programmes
                                    </a>
                                </div>
                                <div class="col-md-3 text-right">
                                    <a class="btn btn-primary square"href="{{route('user-management.ace.baselines',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">
                                        Indicator Baselines
                                    </a>
                                </div>

                                <div class="col-md-3 text-left">
                                    <a class="btn btn-secondary square ml-3" href="{{route('user-management.ace.targets',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}">
                                       <i class="ft-plus-circle"></i> New Targets
                                    </a>
                                </div>
                                @if($target_years->isNotEmpty())
                                    <div class="col-md-6">
{{--                                        <form action="{{route('user-management.ace.targets',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id)])}}"></form>--}}
                                        {{--<fieldset>--}}
                                            {{--<div class="input-group">--}}
                                                {{--<select name="period" id="period" class="form-control">--}}
                                                    {{--@foreach($target_years as $target_year)--}}
                                                        {{--<option value="{{$target_year->id}}">--}}
                                                            {{--{{date('F Y',strtotime($target_year->start_period))." - ".date('F Y',strtotime($target_year->end_period))}}--}}
                                                        {{--</option>--}}
                                                    {{--@endforeach--}}
                                                {{--</select>--}}
                                                {{--<div class="input-group-append" id="button-addon4">--}}
                                                    {{--<button class="btn btn-primary" type="button"><i class="ft-crosshair"></i> Open Target Values</button>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</fieldset>--}}
                                        <div class="btn-group mr-1 mb-1">
                                            <button type="button" class="btn bg-warning bg-darken-4 btn-min-width dropdown-toggle white" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"><i class="ft-crosshair"></i>  Select Target Year</button>
                                            <div class="dropdown-menu">
                                                @foreach($target_years as $target_year)
                                                    <a class="dropdown-item" href="{{route('user-management.ace.targets',[\Illuminate\Support\Facades\Crypt::encrypt($ace->id),$target_year->id])}}">
                                                        {{date('F Y',strtotime($target_year->start_period))." - ".date('F Y',strtotime($target_year->end_period))}}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>





        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h6 class="card-header p-1 card-head-inverse bg-primary" style="border-radius:0">
                        Mailing List
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                            </ul>
                        </div>
                    </h6>
                    <div class="card-content collapse">
                <div class="card-body table-responsive">
                   {{--  <h5>
                        <small>
                            <span class="text-secondary text-bold-500">Unit of Measure:</span>
                            Number (Indicator Definition: Count of regional students in specific ACE courses)
                        </small>
                    </h5> --}}



                    <form class="form" action="{{route('settings.mailinglist.save')}}" method="post">
                        @csrf
                        <div class="form-body">
                            <div class="row">

                              <div class="col-md-6">
                                <input type="hidden" value="{{ $ace->id }}" name="ace_id" id="ace_id" class=" form-control">
                                    <div class="form-group{{ $errors->has('email') ? ' form-control-warning' : '' }}">

                                            <label for="email">Email <span class="required">*</span></label><input type="email" required placeholder="Email Address" min="2" name="email" class="form-control" value="{{ old('email') }}" id="email">
                                            @if ($errors->has('email'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('email') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" style="margin-top: 1.9rem">
                                Submit
                            </button>
                            </div>
                            </div>
                        </div>


                        <div class="">

                        </div>
                    </form>

<br>


       <table class="table table-striped table-bordered all_indicators">
                                <thead>
                                <tr>
                                    {{--<th style="width: 30px;">No.</th>--}}
{{--                                     <th > Name</th>
 --}}                                    <th style="">Email</th>

                                    <th style="width: 100px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($aceemails as $aceemail)
                                        <tr>
{{--                                             <td>{{$aceemail->ace->name}}</td>
 --}}                                            <td>{{$aceemail->email}}</td>

                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{ route('settings.mailinglist.delete',  [Crypt::encrypt($aceemail->id)] ) }}" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Email"><i class="ft-trash-2"></i></a></a>
                                                    {{-- <a class="dropdow-item btn {{($user->status == 0)?'btn-success' : 'btn-danger'}} btn-s" href="#"
                                                       onclick="event.preventDefault();
                                                               document.getElementById('delete-form-{{$count}}').submit();">
                                                        @if($user->status == 0)
                                                            {{ __('Activate') }}
                                                        @else
                                                            {{ __('Deactivate') }}
                                                        @endif
                                                    </a> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                </div>
            </div>






                </div>
            </div>
        </div>


{{--
 <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">                        Mailing List
</h6>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>

                            </ul>
                        </div>
                    </div>

                    <div class="card-content collapse show">
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>  --}}


        <div class="row">
            <div class="col-md-12">
                <h4>DLR Indicators
                    @if($ace_dlrs->count() <= 0)
                        <small> - <span class="mr-md-1 text-danger">No DLRs Added.</span></small>
                    @endif
                </h4>
            </div>

            @if($ace_dlrs->count() > 0)
                @foreach($ace_dlrs as $ace_dlr)
                <div class="col-md-6">
                    <div class="card">
                        <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                            {{$ace_dlr->indicator_title}}
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                </ul>
                            </div>
                        </h6>
                        <div class="card-content collapse show">
                            <div class="card-body table-responsive">
                                <form action="{{route('settings.save_dlr_indicators_cost',[$ace->id])}}" method="post">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{$ace_dlr->id}}">
                                    <div class="row">
                                        <label class="col-md-7" style="padding-top: 0.9rem;">Maximum SDR per DLR</label>
                                        <div class="col-md-5">
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="number" class="form-control" name="max"
                                                       value="{{isset($dlr_max_costs[$ace_dlr->id])?$dlr_max_costs[$ace_dlr->id]:0}}">
                                                <div class="form-control-position">
                                                    <span class="symbol">{{$ace->currency->symbol}}</span>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
        {{--                            @if($unit > $max)--}}
                                        {{--<p class="text-right">--}}
                                            {{--<small class="danger text-muted">The Unit Cost is more than the Maximum</small>--}}
                                        {{--</p>--}}
                                    {{--@endif--}}

                                    @if($ace_dlr->indicators->count() > 0)
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th>DLR Indicators</th>
                                            <th style="width: 200px;">Cost per Unit (SDR)</th>
                                            {{--<th style="width: 200px;">Maximum SDR per DLR</th>--}}
                                        </tr>
                                        @php
                                            $sub_indicators = $ace_dlr->indicators->where('status','=',1);
                                        @endphp
                                        @foreach($sub_indicators as $sub_indicator)
                                            @php
                                                $unit = 0;
                                                    if(isset($dlr_unit_costs[$sub_indicator->id])){
                                                        $unit = $dlr_unit_costs[$sub_indicator->id];
                                                    }
                                            @endphp
                                            <tr>
                                                <td>{{$sub_indicator->indicator_title}}</td>
                                                <td>
                                                    <fieldset class="form-group position-relative has-icon-left mb-0">
                                                        <input type="number" class="form-control" id="single_{{$sub_indicator->id}}"
                                                               value="{{$unit}}" name="single[{{$sub_indicator->id}}]">
                                                        <div class="form-control-position">
                                                            <span class="symbol">{{$ace->currency->symbol}}</span>
                                                        </div>
                                                    </fieldset>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                        @endif
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-secondary square">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/extended/form-inputmask.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>
    <script>

        $('.select2').select2({
            placeholder: "Select Courses",
            allowClear: true
        });
        // Currency in USD
        $('.currency-inputmask').inputmask("{{$ace->currency->symbol}} 99999999");
    </script>
@endpush