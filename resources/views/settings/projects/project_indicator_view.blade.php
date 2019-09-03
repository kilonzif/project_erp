


@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/extended/form-extended.css')}}">

     <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endpush
@push('other-styles')

    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-tooltip.css')}}">
@endpush


@section('content')
<div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Projects</h4>
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
                                    <th >Projects Title</th>
                                    <th style="">Coordinator</th>
                                   
                                    <th style="">Total Grant</th>
                                    <th style="">Grant Id</th>
                                    
                                    <th style="">Status</th>
                                   <th style="">Start Date</th>
                                   <th style="">End Date</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            
                                            <td>

                                            {{$project->title}}

                                            </td>
                                            
                                             <td>
                                                {{$project->coordinator}}
                                                
                                            </td>
                                            
                                            <td>
                                                
                                                {{$project->total_grant}}
                                            </td>

                                            <td>
                                                
                                                {{$project->grant_id}}
                                            </td>

                                            

                                            <td>
                                                {{$project->status}}
                                                
                                            </td>



                                            <td>
                                                {{date('M d, Y',strtotime($project->start_date))}}
                                            </td>


                                            <td>
                                                {{date('M d, Y',strtotime($project->end_date))}}
                                            </td>



                                        </tr>

                                    @endforeach
                                </tbody>
                            </table>
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
        $('.select2').select2({
            placeholder: "Select a Unit of Measure",
            allowClear: true
        });
        $('.all_indicators').dataTable();
    </script>
@endpush