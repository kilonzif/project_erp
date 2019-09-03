


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
                        <h4 class="card-title">Project Details</h4>
                         <h5 class="card-title"></h5>
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
                            
                                
                               
                                     @foreach($projects as $project)
                                    
                                   <div class="row">

                                            <div class="col-md-4">
                                                <dl>
                        <dt>Projects Title:</dt>
                        <dd>{{$project->title}}</dd>
                        <dt>Project Coordinator:</dt>
                        <dd>{{$project->project_coordinator}}
                        </dd>
                        
                        <dt>Total Grant($):</dt>
                        <dd>{{$project->total_grant}}</dd>
                      </dl>
                                            </div>

                                            <div class="col-md-4">
                                                <dl>
                        <dt>Grant Id:</dt>
                        <dd>{{$project->grant_id}}</dd>
                        <dt>Status:</dt>
                        <dd>{{$project->status}}
                        </dd>
                        
                        
                      </dl>
                                            </div>

                                            <div class="col-md-4">
                                                <dl>
                                                    <dt>Project Start Date:</dt>
                        <dd>{{date('M d, Y',strtotime($project->start_date))}}</dd>
                        <dt>Project End Date:</dt>
                        <dd>{{date('M d, Y',strtotime($project->end_date))}}</dd>
                        
                      </dl>
                                            </div>

                                        </div>
                                    
                                    
                                    
                                     @endforeach
                                    
                                    
                               

                               
                                   
                                       
                                         
                                   
                               
                        </div>
                    </div>
                

</div>
 

 <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-bordered all_indicators">
                                <thead>
                                <tr>
                                    <th >Indicator Title</th>
                                    <th style="">Identifier</th>
                                   
                                    <th style="">Order Number</th>
                                    <th style="">Unit Measure</th>
                                    
                                    <th style="">Project Id</th>
                                   <th style="">Status</th>
                                   
                                </tr>
                                </thead>

                               <tbody>
                                     @foreach($indicators as $indicator)
                                        <tr>
                                            
                                            <td>{{$indicator->title}}</td>
                                            <td>{{$indicator->identifier}}</td>
                                             <td>{{$indicator->order_no}}</td>
                                             <td>{{$indicator->unit_measure}}</td>
                                             <td>{{$indicator->project_id}}</td>
                                             <td>{{$indicator->status}}</td>
                                            
                                        </tr>
                                    @endforeach  
                                </tbody>
                            </table>
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