@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/extended/form-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endpush
@push('other-styles')
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0"> Verification Letter Log  </h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active"><a href="">Verification Letter </a>
                        </li>

                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        {{--<p class="text-left">--}}
        {{--<a href="{{route("report_generation.verificationletter.create") }}" class="btn btn-secondary square text-left" >Add Log</a>--}}
        {{--</p>--}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{route('report_generation.verificationletter.report_save',[$report])}}" method="post">
                                @csrf
                                <input type="hidden" name="report" value="{{$report}}">
                                <h5>Add Verification Log</h5>

                                <div class="repeater-default">
                                    <div data-repeater-list="fields">

                                        <div data-repeater-item="" style="">
                                            <div class="row">
                                                <div class="form-group mb-1 col-md-3">
                                                    <label for="">Letter Dated</label>
                                                    <input type="date" class="form-control " id="date-mask" placeholder=" Date" name="letter_dated" required/>
                                                    @if ($errors->has('letter_dated'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('letter_dated') }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="form-group mb-1 col-md-3">
                                                    <label for="date_dispatched">Date dispatched</label>
                                                    <input type="date" class="form-control " id="date-mask" placeholder=" Date" name="date_dispatched" required/>
                                                    @if ($errors->has('date_dispatched'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('date_dispatched') }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="form-group mb-1 col-md-3">
                                                    <label for="">Payment in respect of</label>
                                                    <input type="text" autocomplete="yes" required class="form-control" name="payment" id="payment" placeholder="">
                                                    @if ($errors->has('payment'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('payment') }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="form-group mb-1 col-md-3">
                                                    <label for="amountdue"> Amount Due(SDR)</label>
                                                    <br>
                                                    <input type="number" required class="form-control" name="amount_due"  min="1" id="" placeholder=" " style="padding-left: 0.60rem;padding-right: 0.60rem;">
                                                    @if ($errors->has('amount_due'))
                                                        <p class="text-right">
                                                            <small class="warning text-muted">{{ $errors->first('amount_due') }}</small>
                                                        </p>
                                                    @endif
                                                </div>
                                                {{--<div class="form-group col-md-1 text-center " style="margin-top: 1.9rem;" >--}}
                                                    {{--<button type="button" class="btn btn-danger" data-repeater-delete="" style="width: 100px;"><i class="ft-x"></i></button>--}}
                                                {{--</div>--}}
                                            </div>
                                            {{--<div class="row">--}}
                                                {{--<div class="form-group mb-1 col-md-12">--}}
                                                    {{--<label for="">Comment</label>--}}
                                                    {{--<br>--}}
                                                    {{--<input type="text" required class="form-control" name="comment" id="comment" placeholder=" ">--}}
                                                    {{--@if ($errors->has('comment'))--}}
                                                        {{--<p class="text-right">--}}
                                                            {{--<small class="warning text-muted">{{ $errors->first('comment') }}</small>--}}
                                                        {{--</p>--}}
                                                    {{--@endif--}}
                                                {{--</div>--}}
                                                {{--<div class="form-group col-md-1 text-center " style="margin-top: 1.9rem;" >--}}
                                                    {{--<button type="button" class="btn btn-danger" data-repeater-delete="" style="width: 100px;"> Remove--}}{{-- <i class="ft-x"></i> --}}{{--</button>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<hr>--}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-secondary square">Submit Fields</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered all_logs">
                            <thead>
                            <tr>
                                <th style="width: 10px;">Letter Dated</th>
                                <th style="width: 10px;">Date Dispatched</th>
                                <th style="width: 10px;">Payment in respect of</th>
                                <th style="width: 10px;">Amount Due</th>
                                <th style="width: 10px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($letters as $letter)
                                <tr>
                                    <td>{{date('d-M-Y',strtotime($letter->letter_dated))}}</td>
                                    <td>{{date('d-M-Y',strtotime($letter->date_dispatched))}}</td>
                                    <td>{{$letter->payment}}</td>
                                    <td>{{number_format($letter->amount_due)}}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('report_generation.verificationletter.edit', [Crypt::encrypt($letter->id)]) }}" class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit "><i class="ft-edit-3"></i></a>
                                            <a href="{{ route('report_generation.verificationletter.delete', [Crypt::encrypt($letter->id)]) }}" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete "><i class="ft-trash-2"></i></a>
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

@endsection
@push('vendor-script')
@endpush
@push('vendor-script')
    <script src="{{asset('vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>

    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script src="{{asset('js/scripts/forms/extended/form-inputmask.js')}}" type="text/javascript"></script>
    <script>
        // $('.select2').select2({
        //     placeholder: "Select a Unit of Measure",
        //     allowClear: true
        // });
        $('.all_logs').dataTable();
    </script>
@endpush