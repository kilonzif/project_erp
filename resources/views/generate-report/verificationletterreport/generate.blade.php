


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
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item active"><a href="">Verification Letter </a>
                        </li>

                    </ol>
                </div>
            </div>
        </div>
    </div>











        <div class="content-body">
        <p class="text-left">
            <a href="{{route("report_generation.verificationletter.create") }}" class="btn btn-secondary square text-left" >Add Log</a>
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
                        <div class="card-body">
                            <table class="table table-striped table-bordered all_indicators">
                                <thead>
                                <tr>
                                    <th style="width: 10px;">Aces</th>
                                    <th style="width: 10px;">Letter Dated</th>

                                    <th style="width: 10px;">Date Dispatched</th>


                                    <th style="width: 10px;">Payment</th>
                                   <th style="width: 10px;">Amount Due</th>
                                    <th style="width: 10px;"> Total</th>
                                    <th style="width: 100%">Comment</th>
                                     <th style="width: 10px">Action</th>

                                </tr>
                                </thead>

                               <tbody>
                                     @foreach($VerificationLetter as $VerificationLetter)
                                     <tr>
                                        <tr>
                                            <td>{{$VerificationLetter->name}}</td>

                                            <td>{{$VerificationLetter->letter_dated}}</td>

                                             <td>{{$VerificationLetter->date_dispatched}}</td>

                                            <td>{{$VerificationLetter->amount_due}}</td>

                                            <td>{{$VerificationLetter->payment}}</td>
                                             <td>{{$VerificationLetter->total}}</td>

                                             <td>{{$VerificationLetter->comment}}</td>

                                             <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">


                                                    <a href="{{ route('report_generation.verificationletter.edit', [Crypt::encrypt($VerificationLetter->id)]) }}" class="btn btn-s btn-secondary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit "><i class="ft-edit-3"></i></a>
                                                    <a href="{{ route('report_generation.verificationletter.delete', [Crypt::encrypt($VerificationLetter->id)]) }}" class="btn btn-s btn-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete "><i class="ft-trash-2"></i></a>






                                                </div>
                                            </td>


                                        </tr>


                                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>



</div>


<p class="text-left">
            <a href="{{route("report_generation.verificationletter.verificationpage") }}" class="btn btn-secondary square text-left" >generate</a>
        </p>
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
        $('.select2').select2({
            placeholder: "Select a Unit of Measure",
            allowClear: true
        });
        $('.all_indicators').dataTable();
    </script>
@endpush