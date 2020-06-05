@extends('layouts.app')
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('report_submission.reports')}}">Reports</a>
                        </li>
                        <li class="breadcrumb-item active">
                            @if($indicator_info->set_milestone)
                                Web-form for DLR
                            @else
                                Milestone
                            @endif
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="mb-1 row ">
            <div class="col-md-12">
                <h5>{{$ace->name}} ({{$ace->acronym}}) - {{$indicator_info->title}}</h5>
            </div>
        </div>
        @php
             $miles = $indicator_info->getMilestones->where('ace_id','=',$ace->id);
        @endphp
            @foreach($miles as $milestone)
            <div class="card mb-2">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <p><strong>{{lang('Milestone',$lang)}} {{$milestone->milestone_no}}</strong></p>
                                <p><strong>{{lang('Description',$lang)}}</strong></p>
                                <p>{{$milestone->description}}</p>
                                {!! milestone_status($milestone->status) !!}
                            </div>

                            <div class="col-md-2">
                                <a class="btn btn-secondary" href="{{route('report_submission.milestone_details',
                        [\Illuminate\Support\Facades\Crypt::encrypt($report->id),$milestone->id])}}">
                                    @if($milestone->status <=1)
                                        {{lang('Provide Documents',$lang)}}
                                    @else
                                        {{lang('View Data',$lang)}}
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
