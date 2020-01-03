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
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="">Edit Verification  form</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Verification  Form
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
                    <div class="card-content">
                        <div class="card-body">
                            {{--<h5>Select Indicator</h5>--}}
                            <form action="{{route('report_generation.verificationletter.update',['id' => $verifications->id])}}" method="post">
                                @csrf
                                <h5>Edit Verification Log</h5>
                                <div class="row">
                                    <div class="form-group mb-1 col-md-3">
                                        <label for="">Letter Dated</label>
                                        <input type="date" class="form-control" value="{{(old('letter_dated'))? old('letter_dated') : $verifications->letter_dated}}" id="date-mask" placeholder=" Date" name="letter_dated" required/>
                                        @if ($errors->has('letter_dated'))
                                            <p class="text-right">
                                                <small class="warning text-muted">{{ $errors->first('letter_dated') }}</small>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="form-group mb-1 col-md-3">
                                        <label for="date_dispatched">Date dispatched</label>
                                        <input type="date" class="form-control" value="{{(old('date_dispatched'))? old('date_dispatched') : $verifications->date_dispatched}}" id="date-mask" placeholder=" Date" name="date_dispatched" required/>
                                        @if ($errors->has('date_dispatched'))
                                            <p class="text-right">
                                                <small class="warning text-muted">{{ $errors->first('date_dispatched') }}</small>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="form-group mb-1 col-md-3">
                                        <label for="">Payment in respect of</label>
                                        <input type="text" autocomplete="yes" value="{{(old('payment'))? old('payment') : $verifications->payment}}" required class="form-control" name="payment" id="payment" placeholder="">
                                        @if ($errors->has('payment'))
                                            <p class="text-right">
                                                <small class="warning text-muted">{{ $errors->first('payment') }}</small>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="form-group mb-1 col-md-3">
                                        <label for="amountdue"> Amount Due(SDR)</label>
                                        <br>
                                        <input type="number" required class="form-control" value="{{(old('amount_due'))? old('amount_due') : $verifications->amount_due}}" name="amount_due"  min="1" id="" placeholder=" " style="padding-left: 0.60rem;padding-right: 0.60rem;">
                                        @if ($errors->has('amount_due'))
                                            <p class="text-right">
                                                <small class="warning text-muted">{{ $errors->first('amount_due') }}</small>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-secondary square">Update</button>
                                    </div>
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