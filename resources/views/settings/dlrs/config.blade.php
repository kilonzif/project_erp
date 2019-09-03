@extends('layouts.app')
@push('vendor-styles')
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/toggle/switchery.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
@endpush
@push('other-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/switch.css')}}">
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-tooltip.css')}}">--}}
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <h3 class="content-header-title mb-0">DLR Indicator Configuration</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Settings
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('settings.dlr_indicators')}}">DLR Indicators</a>
                        </li>
                        <li class="breadcrumb-item active">{{$indicator->indicator_title}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">

            {{--Indicator Information--}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <h4 class="card-title info">{{$indicator->indicator_title}}</h4>
                            <h5><span class="teal">Total Sub-Indicators</span> -
                            <span class="card-text">{{$indicator->indicators->count()}}</span></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                {{--Add Sub-Indicator--}}
                <div class="card" id="sub_indicator_box">
                    <div class="card-header">
                        <h4 class="card-title">Add Sub-Indicator</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form action="{{route('settings.dlr_sub_indicator.save',[$indicator->id])}}" method="post">
                                @csrf
                                <input type="hidden" name="indicator" value="{{$indicator->id}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title <span class="required">*</span></label>
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
                                            <label for="order">Position/Order <span class="required">*</span></label>
                                            <input type="number" name="order" id="order" min="1" class="form-control">
                                            @if ($errors->has('order'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('order') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button class="btn btn-secondary square" style="margin-top: 1.9rem;"
                                                    type="submit"><i class="ft-save mr-1"></i>
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

        {{--Table list of sub-indicators--}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sub-Indicators</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <table class="table table-striped table-bordered ace_level_indicator">
                                <thead>
                                <tr>
                                    <th style="width: 100px;">Order</th>
                                    <th>Title</th>
                                    <th style="width: 30px;">Status</th>
                                    <th style="width: 50px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $count = 0;
                                @endphp
                                @foreach($sub_indicators as $sub_indicator)
                                    @php
                                        $count += 1;
                                    @endphp
                                    <tr>
                                        <td>{{$sub_indicator->order}}</td>
                                        <td>
                                            {{$sub_indicator->indicator_title}}
                                        </td>
                                        <td>
                                            <input  onchange="changeStatus({{$count}})"
                                                    type="checkbox" id="active{{$sub_indicator->id}}"
                                                    data-toggle="tooltip" data-placement="top" title="{{($sub_indicator->status == 0)?'Activate Indicator' : 'Deactivate Indicator'}}"
                                                    class="switchery" data-size="xs" @if($sub_indicator->status == 1) checked @endif}}/>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="#sub_indicator_box" onclick="edit_sub({{$sub_indicator->id}})" class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Indicator"><i class="ft-edit-3"></i></a></a>
                                            </div>
                                            <form id="delete-indicator-{{$count}}" action="{{ route('settings.dlr_sub_indicator.activate',[\Illuminate\Support\Facades\Crypt::encrypt($sub_indicator->id)]) }}" method="POST" style="display: none;">
                                                @csrf {{method_field('DELETE')}}
                                                <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($sub_indicator->id)}}">
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
    {{--    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>--}}
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
        })

        function changeStatus(key){
            // alert()
            document.getElementById('delete-indicator-'+key).submit()
        }

        //Script to call the edit view for sub-indicators
        function edit_sub(key) {
            var box = '#sub_indicator_box div.collapse';
            $(box).addClass('show');
            var path = "{{route('settings.dlr_sub_indicator.edit')}}";
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
                    $('#sub_indicator_box').block({
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
                    $('#sub_indicator_box').empty();
                    $('#sub_indicator_box').html(data.theView);
                    // console.log(data)
                },
                complete:function(){
                    $('#sub_indicator_box').unblock();
                }
                ,
                error: function (data) {
                }
            });
        }
    </script>
@endpush