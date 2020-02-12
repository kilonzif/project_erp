@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
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
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Settings
                        </li>
                        <li class="breadcrumb-item act
                        ive">Indicators
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card" id="indicator-card">
                    <div class="card-header">
                        <h4 class="card-title">Add Indicator</h4>
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
                            <form action="{{route('indicators.save')}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">Title <span class="required">*</span></label>
                                            <input type="text" required min="5" value="{{ old('title') }}" placeholder="Indicator Title" name="title" class="form-control" id="title">
                                            @if ($errors->has('title'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('title') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="unit_of_measure">Unit of Measure <span class="required">*</span></label>
                                            <input type="text" required min="3" placeholder="Unit of Measure" value="{{ old('unit_of_measure') }}" name="unit_of_measure" class="form-control" id="unit_of_measure">
                                            @if ($errors->has('unit_of_measure'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('unit_of_measure') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="identifier">Identifier <span class="required">*</span></label>
                                            <input type="text" required name="identifier" min="1" placeholder="Identifier eg. 1 or A" value="{{ old('identifier') }}" class="form-control" id="identifier">
                                            @if ($errors->has('identifier'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('identifier') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="order_no">Order No. <span class="required">*</span></label>
                                            <input type="number" required name="order_no" min="1" placeholder="Order No." value="{{ old('order_no') }}" class="form-control" id="order_no">
                                            @if ($errors->has('order_no'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('order_no') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="on_report">Show on Report? <span class="required">*</span></label>
                                            <select name="on_report" id="on_report" class=" form-control" style="width: 100%;">
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                            @if ($errors->has('on_report'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('on_report') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="project">Parent Indicator</label>
                                            <select name="parentIndicator" id="parentIndicator" class=" form-control" style="width: 100%;">
                                                <option value="0">NONE</option>
                                                @foreach($indicators as $indicator)
                                                    <option value="{{$indicator->id}}">{{$indicator->title}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('parentIndicator'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('parentIndicator') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="d-inline-block custom-control custom-checkbox mr-1">
                                                <input type="checkbox" class="custom-control-input" checked value="1" name="upload" id="upload">
                                                <label class="custom-control-label" for="upload">Requires indicator uploads.</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="d-inline-block custom-control custom-checkbox mr-1">
                                                <input type="checkbox" class="custom-control-input" checked value="1" name="set_milestone" id="set_milestone">
                                                <label class="custom-control-label" for="set_milestone">Has Milestones</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="d-inline-block custom-control custom-checkbox mr-1">
                                                <input type="checkbox" class="custom-control-input" value="1" name="set_target" id="set_target">
                                                <label class="custom-control-label" for="set_target">Set Target Value</label>
                                            </div>
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Indicators</h4>
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
                            <table class="table table-striped table-bordered all_indicators">
                                <thead>
                                <tr>
                                    <th style="width: 100px;">Identifier</th>
                                    <th>Title</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $count = 0;
                                @endphp
                                    @foreach($parentIndicators as $pi)
                                        @php
                                            $count += 1;
                                        @endphp
                                        <tr>
                                            <td>{{$pi->identifier}}</td>
                                            <td>{{$pi->title}}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{route('indicator.config',[$pi->id])}}" class="btn btn-s btn-dark" data-toggle="tooltip" data-placement="top" title="Configure Indicator"><i class="ft-settings"></i></a></a>
                                                    <a href="#indicator-card" onclick="edit_indicator({{$pi->id}})" class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Indicator"><i class="ft-edit-3"></i></a></a>
                                                    {{--<a href="#" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="Indicator Status"><i class="ft-times"></i></a></a>--}}
                                                    <a class="dropdow-item btn {{($pi->status == 0)?'btn-success' : 'btn-danger'}} btn-s" href="#"
                                                       data-toggle="tooltip" data-placement="top" title="{{($pi->status == 0)?'Activate Indicator' : 'Deactivate Indicator'}}"
                                                       onclick="event.preventDefault(); document.getElementById('delete-indicator-{{$count}}').submit();">
                                                        @if($pi->status == 0)
                                                            <i class="ft-check"></i>
                                                        @else
                                                            <i class="ft-x"></i>
                                                        @endif
                                                    </a>
                                                </div>
                                                <form id="delete-indicator-{{$count}}" action="{{ route('indicator.activate',[\Illuminate\Support\Facades\Crypt::encrypt($indicator->id)]) }}" method="POST" style="display: none;">
                                                    @csrf {{method_field('DELETE')}}
                                                    <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($pi->id)}}">
                                                </form>
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
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/checkbox-radio.js')}}" type="text/javascript"></script>

    <script>
        $('.select2').select2({
            placeholder: "Select a Unit of Measure",
            allowClear: true
        });
        $('.all_indicators').dataTable({
            "columnDefs": [
                { "orderable": false, "targets": [0,1,2] }
            ],
            // "order": [[ 0, 'asc' ]],
            pageLength: 50,
            responsive: true
        });

        //Script to call the edit view for indicators
        function edit_indicator(key) {

            var path = "{{route('indicator.edit')}}";
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
                    $('#indicator-card').block({
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
                    $('#indicator-card').empty();
                    $('#indicator-card').html(data.theView);
                },
                complete:function(){
                    $('#indicator-card').unblock();
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }
    </script>
@endpush