@extends('layouts.app')
@push('vendor-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">DLR Report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <h5>Generate Report</h5>

        <form action="{{route('report_generation.dlrs')}}" method="GET">
            @csrf
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="ace">Select ACE<span class="required">*</span></label>
                                    <select name="ace" id="ace" class="form-control" required>
                                        <option value="">Choose ACE</option>
                                        @foreach($aces as $ace)
                                            <option {{(old('ace')==$ace->id)?"selected":""}} value="{{$ace->id}}">{{$ace->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('ace'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('ace') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="reporting_year">Reporting Year<span class="required">*</span></label>
                                    <select name="reporting_year" id="reporting_year" class="form-control" required>
                                        <option value="">Choose Year</option>
                                        <option value="2019" {{(old('reporting_year')=="2019")?"selected":""}}>2019</option>
                                        <option value="2020" {{(old('reporting_year')=="2020")?"selected":""}}>2020</option>
                                        <option value="2021" {{(old('reporting_year')=="2021")?"selected":""}}>2021</option>
                                        <option value="2022" {{(old('reporting_year')=="2022")?"selected":""}}>2022</option>
                                        <option value="2023" {{(old('reporting_year')=="2023")?"selected":""}}>2023</option>
                                        <option value="2024" {{(old('reporting_year')=="2024")?"selected":""}}>2024</option>
                                    </select>
                                    @if ($errors->has('reporting_year'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('reporting_year') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dlr">DLR<span class="required">*</span></label>
                                    <select name="dlr" id="dlr" class="form-control" required>
                                        <option value="">Choose DLR</option>
                                        @foreach($options as $key => $name)
                                            <option {{(old('dlr')==$key)?"selected":""}} value="{{$key}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('dlr'))
                                        <p class="text-right">
                                            <small class="warning text-muted">{{ $errors->first('dlr') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" name="generate" value="1" class="btn btn-secondary square">
                                    <i class="ft-list mr-sm-1"></i>{{__('Generate')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if(isset($indicator_details))
            <div id="action-loader" style="padding: 1rem 0; margin: 2rem 0;">
                @include('generate-report.dlrs-result-list')
            </div>
        @endif
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script>
        function loadFields() {
            let dlr = $('#dlr').val();
            let reporting_year = $('#reporting_year').val();
            let ace = $('#ace').val();
            // alert($selected);
            let path = "{{route('report_generation.dlrs.result')}}"
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                url: path,
                type: 'GET',
                data: {dlr:dlr,reporting_year:reporting_year,ace:ace},
                beforeSend: function(){
                    $('#action-loader').block({
                        message: '<div class="ft-loader icon-spin font-large-1"></div>',
                        // timeout: 2000, //unblock after 2 seconds
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
                    });
                    $('#action-card').empty();
                },
                success: function(data){
                    // console.log(data);
                    $('#action-card').html(data.theView);
                },
                complete:function(){
                    $('#action-loader').unblock();
                    $.getScript("{{asset('vendors/js/tables/datatable/datatables.min.js')}}")
                }
                ,
                error: function (data) {
                    console.log(data)
                }
            });
        }

        $('.dlr-info').DataTable({
            // responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'print','colvis'
            ]
        });
    </script>
@endpush