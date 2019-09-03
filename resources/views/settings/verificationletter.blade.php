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
            <h3 class="content-header-title mb-0">Verification </h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route("report_generation.verificationletter.list") }}">Verification  Log</a>
                        </li>
                        <li class="breadcrumb-item active">Create Log
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <p class="text-left">
            <a href="{{route("report_generation.verificationletter.list") }}" class="btn  btn-secondary square"> Verification  Log List</a>
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
                            <form action="{{route('report_generation.verificationletter.save')}}

                            " method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <fieldset class="form-group">
                                            {{-- <label for="project">Select Ace</label>
                                            <select name="project" required class="select2 form-control" id="project">
                                                @foreach($projects as $project)
                                                    <option value="{{$project->id}}">Indicator {{$project->title}}</option>
                                                @endforeach
                                            </select> --}}
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="form-group">
                                           {{--  <label for="basicInputFile">Select Indicator</label> --}}
                                            {{-- <select name="indicator" required class="select2 form-control" id="indicator">
                                                <option value=""></option>
                                                @foreach($indicators as $indicator)
                                                    <option value="{{$indicator->id}}">Indicator {{$indicator->identifier}}</option>
                                                @endforeach
                                            </select> --}}
                                        </fieldset>
                                    </div>
                                    <div class="col-md-2">
                                        <fieldset class="form-group">
                                           {{--  <label for="start_row">Start Row #</label>
                                            <input name="start_row" type="number" min="2" required class="form-control" id="start_row"> --}}
                                        </fieldset>
                                    </div>
                                </div>

                                <h5>Add Fields</h5>

                                <div class="repeater-default">
                                    <div data-repeater-list="fields">

                                        <div data-repeater-item="" style="">
                                            <div class="row">


                                                {{-- <div class="form-group mb-1 col-md-3">
                                                    <label for="input_type">Aces </label>
                                                    <br>
                                                    <select class="form-control" name="input_type" onchange="select_option()" required id="input_type">
                                                        <option>Select Option</option>
                                                        <option>Text</option>
                                                        <option>Email</option>
                                                        <option>Date</option>
                                                        <option>Number</option>
                                                        <option>Select</option>
                                                    </select>
                                                </div> --}}
                                                 {{--  <div class="form-group mb-1 col-md-6">
                                                    <label for="">Aces </label>
                                                    <br>
                                                    <select class="form-control" required name="ace_id" id="" required >
                                                <option value="">Choose Ace</option>
                                                @foreach($aces as $ace)
                                                    <option value="{{$ace->id}}">{{$ace->university->name}}
</option>
                                                @endforeach
                                            </select>
                                                </div> --}}

                                                 <div class="form-group mb-1 col-md-12">
                                                    <label for="">Aces </label>
                                                    <br>
                                                    <select class="form-control" required name="ace_id" id="" required >
                                                <option value="">Choose Ace</option>
                                                @foreach($aces as $ace)
                                                    <option value="{{$ace->id}}">{{$ace->university->name}}
</option>
                                                @endforeach
                                            </select>
                                                </div>







{{--
                                                 @foreach($aces as $ace)

                                                {{$ace->name}}
                                                {{$ace->university->name}}
                                                {{$ace->university->country->country}}


                                                @endforeach --}}







                                               <div class="form-group mb-1 col-md-6">
                                                <fieldset>
                                                    <label for="">Letter Dated</label>
                                                    <br>
                                                     <input type="date" class="form-control " id="date-mask" placeholder=" Date" name="letter_dated" required
                                                />


                                                 @if ($errors->has('letter_dated'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('letter_dated') }}</small>
                                                </p>
                                            @endif
                                                    </fieldset>
                                                </div>

                                                <div class="form-group mb-1 col-md-6">
                                                    <fieldset>
                                                    <label for="date_dispatched">Date dispatched</label>
                                                    <br>
                                                     <input type="date" class="form-control " id="date-mask" placeholder=" Date" name="date_dispatched" required
                                                />
                                                    @if ($errors->has('date_dispatched'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('date_dispatched') }}</small>
                                                </p>
                                            @endif
                                                    </fieldset>
                                                </div>
















                                                 {{-- <div class="form-group mb-1 col-md-1">
                                                    <label for="label">DLRPaymentFile</label>
                                                    <br>
                                                    <input type="number" style="padding-left: 0.60rem;padding-right: 0.60rem;" min="1" required class="form-control" name="order" id="order">
                                                </div> --}}

                                               {{--  <div class="form-group mb-1 col-md-1">
                                                    <label for="label">Variance</label>
                                                    <br>
                                                    <input type="number" style="padding-left: 0.60rem;padding-right: 0.60rem;" min="1" required class="form-control" name="order" id="order">
                                                </div> --}}



                                                {{-- <div class="form-group col-md-1 text-center" style="margin-top: 1.9rem;">
                                                    <button type="button" class="btn btn-danger" data-repeater-delete=""> <i class="ft-x"></i></button>
                                                </div> --}}



                                            </div>



{{-- sefnsfsfdfrgrgrgergergrerergergergergergergergergergergergergergergreg --}}


<div class="row">

<div class="form-group mb-1 col-md-6">
                                                    <label for="">Payment </label>
                                                    <br>
                                                    <input type="text" required class="form-control" name="payment" id="payment" placeholder="">







                                                     @if ($errors->has('payment'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('payment') }}</small>
                                                </p>
                                            @endif
                                                </div>





                                                <div class="form-group mb-1 col-md-6">
                                                    <label for="amountdue"> Amount Due(SDR)</label>
                                                    <br>
                                                    <input type="number" required class="form-control" name="amount_due"  min="1" id="" placeholder=" " style="padding-left: 0.60rem;padding-right: 0.60rem;">

                                                    @if ($errors->has('amount_due'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('amount_due') }}</small>
                                                </p>
                                            @endif
                                                </div>



 </div>




                                            <div class="row">








<div class="form-group mb-1 col-md-12">
                                                    <label for="">Comment</label>
                                                    <br>
                                                    <input type="text" required class="form-control" name="comment" id="comment" placeholder=" ">







                                                     @if ($errors->has('comment'))
                                                <p class="text-right">
                                                    <small class="warning text-muted">{{ $errors->first('comment') }}</small>
                                                </p>
                                            @endif
                                                </div>

{{--           --}}















{{--
                                               <div class="form-group mb-1 col-md-2">
                                                    <label for="label">Variance</label>
                                                    <br>
                                                    <input type="number" style="padding-left: 0.60rem;padding-right: 0.60rem;" min="1" required class="form-control" name="order" id="order">
                                                </div> --}}

                                               {{-- <div class="form-group mb-1 col-md-2">
                                                    <label for="label">DlrPaymentFile</label>
                                                    <br>
                                                    <input type="number" style="padding-left: 0.60rem;padding-right: 0.60rem;" min="1" required class="form-control" name="order" id="order">
                                                </div> --}}

                                                 {{-- <div class="form-group mb-1 col-md-1">
                                                    <label for="label">DLRPaymentFile</label>
                                                    <br>
                                                    <input type="number" style="padding-left: 0.60rem;padding-right: 0.60rem;" min="1" required class="form-control" name="order" id="order">
                                                </div> --}}

                                               {{--  <div class="form-group mb-1 col-md-1">
                                                    <label for="label">Variance</label>
                                                    <br>
                                                    <input type="number" style="padding-left: 0.60rem;padding-right: 0.60rem;" min="1" required class="form-control" name="order" id="order">
                                                </div> --}}




                                                <div class="form-group col-md-1 text-center " style="margin-top: 1.9rem;" >
                                                    <button type="button" class="btn btn-danger" data-repeater-delete="" style="width: 100px;"> Remove{{-- <i class="ft-x"></i> --}}</button>
                                                </div>


                                            </div>

                                            <hr>
                                        </div>
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