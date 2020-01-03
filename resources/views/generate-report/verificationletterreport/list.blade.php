@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/selects/select2.min.css')}}">--}}

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
                        <li class="breadcrumb-item"><a href=""> Verification Status Report</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>



    <div class="content-body">
        <a href="{{route("report_generation.verificationletter.generate") }}
" class="btn btn-secondary square mb-1">Go Back</a>
        <div class="row">
            <div class="col-12">
                <div class="card mb-1">
                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                        Verification Status Report
                    </h6>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="container">




{{-- <div class="row">

<div style="width: 10px;">
    <dt> Ace</dt>
</div>

<div style="width: 10px;">
    <dt> Letter Dated</dt>
</div>

<div style="width: 10px;">
<dt> Date Dispatched </dt>
</div>

 <div style="width: 10px;">
<dt>Payment In Respect Of  </dt>
</div>

 <div style="width: 10px;">
<dt> Amount Due(SDR)  </dt>
</div>


 <div style="width: 10px;">
<dt>Total </dt>
</div>


<div style="width: 10px;">
<dt> Comments </dt>
</div>

  </div>
 --}}


<table class="table table-bordered table-striped">
                              <thead>
                                <tr>
                                      <th style="width: 10px;">Country</th>
                                    <th style="width: 10px;">Ace</th>

                                    <th style="width: 10px;">Letter Dated</th>

                                    <th style="width: 10px;">Date Dispatched</th>
                                    <th style="width: 10px;">Payment In Respect Of</th>

                                    <th style="width: 10px;">Amount Due(SDR)</th>

                                    <th style="width: 10px;">Total</th>


                                </tr>
                                                                </thead>

<tbody>

                                @foreach($aces as $ace)
                                            @foreach($ace->verificationLetters as $key=>$letter)
                                            <tr>
                                                 @if($key == 0)
                                                <td rowspan="{{ $ace->verificationLetters->count() }}">{{ $ace->university->country->country}}</td>
                                                @endif

                                                @if($key == 0)
                                                <td rowspan="{{ $ace->verificationLetters->count() }}">{{ $ace->name }}</td>
                                                @endif
                                                    <td>{{ $letter->letter_dated }}</td>
                                                    <td>{{ $letter->date_dispatched }}</td>
                                                    <td>{{ $letter->payment }}</td>
                                                    <td>{{ $letter->amount_due }}</td>
                                                    @if($key == 0)
                                                <td rowspan="{{ $ace->verificationLetters->count() }}">{{ $ace->verificationLetters->sum('amount_due') }}</td>
                                                @endif
                                                {{-- @if($key == 0)
                                                <td rowspan="{{ $ace->verificationLetters->count() }}">{{ $letter->comment }}</td>
                                                @endif --}}
                                            </tr>
                                            @endforeach
                                    @endforeach










                                </tbody>

                            </table>





                               {{--  <h2 class="text-center danger mt-3 mb-3">No Report can be generated within the specified range</h2> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    {{--    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>--}}
@endpush
@push('end-script')
    <script>
        $('#verifications').dataTable({
            "columnDefs": [{
                "visible": false,
                // "targets": -1
            }]
            // dom: 'Bfrtip',
            // buttons: [
            //     'excel', 'print'
            // ]
        });
    </script>
@endpush