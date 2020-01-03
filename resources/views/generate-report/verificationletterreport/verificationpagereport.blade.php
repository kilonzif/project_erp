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
                        <li class="breadcrumb-item"><a href="{{-- {{route('report_submission.reports')}} --}}"> verification Report</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

   <div class="content-body">
        <a href="{{ route('report_generation.verificationletter.verificationpage') }}" class="btn btn-secondary square mb-1">Go Back</a>


         <a href="{{route('report_generation.verificationletter.verificationpagereport',array_merge(['export'=>true], request()->only(['start', 'end','aces'])))}}" class="btn btn-primary square mb-1">
            <i class="fa fa-file-excel-o"></i> Excel</a>

       {{--   <a href="{{route('report_generation.verificationletter.verificationpagereport',array_merge(['export'=>true], request()->only(['start', 'end'])))}}" class="btn btn-primary square mb-1">
            <i class="fa fa-file-excel-o"></i> Excel</a> --}}


        <div class="row">
            <div class="col-12">
                <div class="card mb-1">
                    <h6 class="card-header p-1 card-head-inverse bg-teal" style="border-radius:0">
                        Status Report
                    </h6>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th style="width: 150px;">Start Date</th>
                                    <td>{{date('F d, Y',strtotime($start))}}</td>
                                    <th style="width: 150px;">End Date</th>
                                    <td>{{date('F d, Y',strtotime($end))}}</td>
                                </tr>
                            </table>

                               <table class="table table-bordered table-striped">
                             {{--  <thead> --}}
                                <tr>
                                    <th style="width: 10px;">Country</th>

                                    <th style="width: 10px;">Ace</th>

                                    <th style="width: 10px;">Letter Dated</th>

                                    <th style="width: 10px;">Date Dispatched</th>
                                    <th style="width: 10px;">Payment In Respect Of</th>

                                    <th style="width: 10px;">Amount Due(SDR)</th>

                                    <th style="width: 10px;">Total</th>



                                </tr>
                                                              {{--   </thead> --}}

<tbody>
@if($verificationletters->isNotEmpty())

                                @foreach($verificationletters->groupBy('country') as $country=>$letters)
                                           {{--  @foreach($report->verificationLetters as $key=>$letter) --}}
                                            <tr>
                                                <td rowspan="{{ $letters->count() }}">{{ $country }}</td>
                                                 @foreach($letters->groupBy('name') as $ace=>$ace_letters)
                                                     <td rowspan="{{ $ace_letters->count() }}">{{ $ace }}</td>

                                                     @foreach($ace_letters as $index=>$letter)

                                                     <td>{{ $letter->letter_dated }}</td>
                                                     <td>{{ $letter->date_dispatched }}</td>
                                                     <td>{{ $letter->payment }}</td>
                                                     <td>{{ $letter->amount_due }}</td>
                                                     @if($index==0)
                                                     <td rowspan="{{ $ace_letters->count() }}">{{ $ace_letters->sum('amount_due')}}</td>
                                                     @endif
                                                 </tr>
                                                    @endforeach


                                                 @endforeach
                                            </tr>
                                            {{-- @endforeach --}}
                                    @endforeach
@else

<tr>
<td colspan="7">    <h4 class="text-center danger mt-3 mb-3">No Report can be generated within the specified range</h4>
</td>
</tr>

@endif
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

