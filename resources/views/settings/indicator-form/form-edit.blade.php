@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/icheck/custom.css')}}">--}}
@endpush
@push('other-styles')
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/checkboxes-radios.css')}}">--}}
@endpush
@section('content')
    {{--@php dd(old('indicator.3')) @endphp--}}
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Upload Indicators Details</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('settings.indicator.generated_forms')}}">Indicator Forms</a>
                        </li>
                        <li class="breadcrumb-item active">Create Indicators Form
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <p class="text-left">
            <a href="{{route('settings.indicator.generated_forms')}}" class="btn btn-sm btn-secondary square">Back to lists</a>
        </p>
        <div class="row">
            {{--<div class="col-md-3">--}}
            {{--<div class="card">--}}
            {{--<div class="card-header">--}}
            {{--<h5>Fields Types</h5>--}}
            {{--<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>--}}
            {{--</div>--}}
            {{--<div class="card-content">--}}
            {{--<div class="card-body">--}}

            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            {{--<h5>Select Indicator</h5>--}}
                            <form action="{{route('settings.indicator.generate_form.update',[$form->_id])}}" method="post">
                                @csrf {{method_field('PATCH')}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <fieldset class="form-group">
                                            <label for="project">Select Project</label>
                                            <select name="project" required class="select2 form-control" id="project">
                                                @foreach($projects as $project)
                                                    <option @if($form->project == $project->id) selected @endif value="{{$project->id}}">Indicator {{$project->title}}</option>
                                                @endforeach
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="form-group">
                                            <label for="basicInputFile">Select Indicator</label>
                                            <select name="indicator" required class="select2 form-control" id="indicator">
                                                <option value=""></option>
                                                @foreach($indicators as $indicator)
                                                    <option @if($form->indicator == $indicator->id) selected @endif value="{{$indicator->id}}">Indicator {{$indicator->identifier}}</option>
                                                @endforeach
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-2">
                                        <fieldset class="form-group">
                                            <label for="start_row">Start Row #</label>
                                            <input name="start_row" type="number" value="{{$form->start_row}}" min="2" required class="form-control" id="start_row">
                                        </fieldset>
                                    </div>
                                </div>

                                <h5>Add Fields</h5>
                                <hr>
                                <div class="repeater-default">
                                    <div data-repeater-list="fields">
                                        @foreach($form->fields as $key=>$field)
                                            {{--                {{var_dump($field->id)}}--}}
                                            @php
                                                $field = (object)$field;
                                            @endphp
                                            <div data-repeater-item="" style="">
                                                <div class="row">
                                                    <div class="form-group mb-1 col-md-1">
                                                        <label for="label">#</label>
                                                        <br>
                                                        <input type="number" style="padding-left: 0.60rem;padding-right: 0.60rem;" min="1" value="{{$field->order}}" required class="form-control" name="order" id="order">
                                                    </div>
                                                    <div class="form-group mb-1 col-md-4">
                                                        <label for="label">Label Title</label>
                                                        <br>
                                                        <input type="text" value="{{$field->label}}" required class="form-control" name="label" id="label" placeholder="Eg. Student Number">
                                                    </div>
                                                    <div class="form-group mb-1 col-md-3">
                                                        <label for="input_type">Input Type</label>
                                                        <br>
                                                        <select class="form-control" name="input_type" onchange="select_option()" required id="input_type">
                                                            <option>Select Option</option>
                                                            <option @if($field->input_type == 'Text') selected @endif value="Text">Text</option>
                                                            <option @if($field->input_type == 'Email') selected @endif value="Email">Email</option>
                                                            <option @if($field->input_type == 'Date') selected @endif value="date">Date</option>
                                                            <option @if($field->input_type == 'Number') selected @endif value="Number">Number</option>
                                                            <option @if($field->input_type == 'Select') selected @endif value="Select">Select</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-1 col-md-3">
                                                        <label for="select_options">Select options <span class="text-mute"></span></label>
                                                        <br>
                                                        <input type="text" value="{{$field->select_options}}" placeholder="Eg. May, June, July" class="form-control select_options" name="select_options" id="select_options">
                                                    </div>
                                                    <div class="form-group col-md-1 text-center" style="margin-top: 1.9rem;">
                                                        <button type="button" class="btn btn-danger" data-repeater-delete=""> <i class="ft-x"></i></button>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-secondary square">Submit Fields</button>
                                        </div>
                                        {{--<div class="form-group overflow-hidden">--}}
                                        <div class="col-md-6 text-right">
                                            <span data-repeater-create="" class="btn btn-primary square">
                                                <i class="icon-plus4"></i> Add New Field
                                            </span>
                                        </div>
                                        {{--</div>--}}
                                    </div>
                                </div>



                                {{--<div class="col-md-12">--}}
                                {{--<div class="form-group mb-1 contact-repeater">--}}
                                {{--<div data-repeater-list="repeater-group">--}}
                                {{--<div class="input-group mb-1" data-repeater-item="" style="">--}}
                                {{--<input type="tel" placeholder="Column Title" class="form-control" id="example-tel-input">--}}
                                {{--<div class="input-group-append">--}}
                                {{--<span class="input-group-btn" id="button-addon2">--}}
                                {{--<button class="btn btn-danger" type="button" data-repeater-delete=""><i class="ft-x"></i></button>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<button type="button" data-repeater-create="" class="btn btn-primary">--}}
                                {{--<i class="icon-plus4"></i> Add Column--}}
                                {{--</button>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}

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
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    {{--<script src="{{asset('js/scripts/forms/form-repeater.js')}}" type="text/javascript"></script>--}}
    <script>
        (function(window, document, $) {
            'use strict';

            // Custom Show / Hide Configurations
            $('.contact-repeater,.repeater-default').repeater({
                show: function () {
                    $(this).slideDown();
                },
                hide: function(remove) {
                    if (confirm('Are you sure you want to remove this item?')) {
                        $(this).slideUp(remove);
                    }
                }
            });


        })(window, document, jQuery);

        function select_options() {
            var input_select_opts = $('.select_options').closest('.select_options');
            console.log(input_select_opts);
        }

        $('.select2').select2({
            placeholder: "Select Indicator",
            allowClear: true
        });
    </script>
@endpush