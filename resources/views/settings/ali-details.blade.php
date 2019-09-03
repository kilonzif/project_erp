@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/toggle/switchery.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/switch.css')}}">
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Ace Level Indicator</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Settings
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{route('ace_level_indicators')}}">Ace Level Indicator</a>
                        </li>
                        <li class="breadcrumb-item active">Details
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <h4 class="card-title info">Information & Configurations</h4>
                            <p class="card-text">This ACE Level Indicator belongs to
                                <span class="text-bold-600">Indicator {{$ace_level_indicator->indicator_id}}</span>
                            </p>
                            <p class="card-text">
                                Unit of Measures: <span class="text-bold-600">{{$ace_level_indicator->unit_measures->count()}}</span>
                            </p>
                            <p class="card-text">
                                Total Specifics: <span class="text-bold-600">{{$ace_level_indicator->specifics->count()}}</span>
                            </p>
                        </div>
                    </div>
                </div>
                @php
                    $counter = 1;
                @endphp
                @if($ace_level_indicator->unit_measures->count() > 0)
                    @foreach($ace_level_indicator->unit_measures as $uom)
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Section {{$counter++}}</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse">
                                <div class="card-body">
                                    <p class="card-text">
                                        {{$uom->title}}
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <tbody>
                                                @foreach($uom->specifics as $specific)
                                                <tr>
                                                    <td>{{$specific->title}}</td>
{{--                                                    <td>{{$specific->order_no}}</td>--}}
                                                    <td>
                                                        <input onchange="changeActive({{$specific->id}})" type="checkbox" id="active{{$specific->id}}" {{($specific->active == 1)?'selected' : ''}} class="switchery" data-size="xs" checked/>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                @if($ace_level_indicator->specifics->where('unit_measure_id','=',null)->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Section {{$counter++}}</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse">
                            <div class="card-body">
                                <p class="card-text">
                                    No Unit of Measure
                                </p>
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <tbody>
                                        @foreach($ace_level_indicator->specifics->where('unit_measure_id','=',null) as $specific)
                                            <tr>
                                                <td>{{$specific->title}}</td>
                                                <td>{{$specific->active}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Unit of Measure</h4>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse">
                        <div class="card-body">
                            <form action="{{route('unit_measure.save')}}" id="add_uom" method="post">
                                @csrf
                                <input type="hidden" name="ali_id" value="{{$ace_level_indicator->id}}">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" required min="5" value="{{ old('title') }}" name="title" class="form-control" id="title">
                                            @if ($errors->has('title'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('title') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="order">Order</label>
                                            <input type="number" required name="order" min="1" value="{{ old('order') }}" class="form-control" id="order">
                                            @if ($errors->has('order'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('order') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-secondary square" type="submit"><i class="ft-save mr-1"></i>
                                        Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Specifics</h4>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form action="{{route('specific.save')}}" id="add_specifics" method="post">
                                @csrf
                                <input type="hidden" name="ali_id" value="{{$ace_level_indicator->id}}">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" required min="5" value="{{ old('title') }}" name="title" class="form-control" id="title">
                                            @if ($errors->has('title'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('title') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="order">Order</label>
                                            <input type="number" required name="order" min="1" value="{{ old('order') }}" class="form-control" id="order">
                                            @if ($errors->has('order'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('order') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="unit_measure">Unit of Measure</label><br>
                                            <select name="uom_id" id="unit_measure" style="width: 100%;" class="select2 form-control">
                                                <option value="">Select</option>
                                                @foreach($uoms as $uom)
                                                    <option value="{{$uom->id}}">{{$uom->title}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('unit_measure'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('unit_measure') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
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
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/toggle/bootstrap-checkbox.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/toggle/switchery.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')

    <script src="{{asset('js/scripts/forms/switch.js')}}" type="text/javascript"></script>
    <script>
        $('.select2').select2({
            placeholder: "Select a Unit of Measure",
            allowClear: true
        });
        function changeActive(id) {
            alert(id)
        }
        // $( "#myselect option:selected" ).text();
    </script>
@endpush