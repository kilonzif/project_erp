@extends('layouts.app')

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item active">Ace Profile
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
                    <div class="card-header bg-dark bgsize-darken-4 white">
                        <h4 class="card-title">{{$ace->name}}</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td ><strong>ACE</strong><br>{{$ace->name}}</td>
                                    <td ><strong>Acronym</strong><br>{{$ace->acronym}}</td>
                                    <td><strong>Institution</strong><br>{{$ace->university->name}}</td>
                                    <td><strong>Contact</strong><br>{{$ace->contact}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong><br>{{$ace->email}}</td>
                                    <td><strong>Grant Amount1</strong><br>{{$ace->grant1}} - {{$currency1->name}}</td>
                                    <td><strong>Grant Amount2</strong><br>{{$ace->grant2}} - {{$currency2->name}}</td>
                                    <td><strong>Field</strong><br>{{$ace->field}}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary bg-primary-4 white">
                        <h3 class="card-title">Ace Contacts</h3>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <table class="table table-striped table-bordered all_indicators">
                                <thead>
                                <tr>

                                    <th>Title</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--$contact_positions--}}
                                @foreach($contacts as $key=>$contact)
                                    @if(in_array($contact->contact_title,$contact_positions))
                                    <tr>
                                        <td>{{$contact->contact_title}}
                                            @if($contact->contact_status==0)
                                                <strong>-Former</strong>
                                            @else
                                                <strong>-Current</strong>
                                            @endif
                                        </td>
                                        <td>{{$contact->contact_name}}</td>
                                        <td>{{$contact->email}}</td>
                                        <td>{{$contact->contact_phone}}</td>

                                    </tr>
                                    @endif

                                    <tr>
                                        <td>@if($contact->title !=$contact_positions[$key])
                                                {{$contact_positions[$key]}}@endif</td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary bg-primary-4 white">
                        <h4 class="card-title">Institutional READINESS</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td><strong>Requirement</strong></td>
                                <td><strong>Files</strong></td>
                                <td><strong>URL links</strong></td>
                                <td><strong>Comments</strong></td>
                                <td><strong>Submission Date</strong></td>

                            </tr>
                            @foreach($indicator_ones as $key=>$data)

                                @if(in_array($data->requirement,$labels))

                                    <tr>
                                        <td>
                                            {{$data->requirement}}
                                        </td>
                                        <td>
                                            @if($data->file_one !="")
                                                <strong>File 1</strong>
                                                <a href="{{asset('indicator1/'.$data->file_one)}}" target="_blank">
                                                    <span class="fa fa-file"></span>   Download uploaded file
                                                </a>
                                                <br>
                                            @endif
                                            @if($data->file_two !="")
                                                <strong>File 2</strong>
                                                <a href="{{asset('indicator1/'.$data->file_two)}}" target="_blank">
                                                    <span class="fa fa-file"></span>   Download uploaded file
                                                </a>
                                            @endisset
                                        </td>
                                        <td>
                                            @isset($data->url)
                                                {{$data->url}}
                                            @endisset

                                        </td>
                                        <td>
                                            @if(!empty($data->comments))
                                                {{$data->comments}}
                                            @endif

                                        </td>
                                        <td>
                                            @isset($data->submission_date)
                                                {{$data->submission_date}}
                                            @endisset
                                        </td>
                                    </tr>
                                @endif
                                    <tr>
                                        <td>@if($data->requirement !=$labels[$key])
                                            {{$labels[$key]}}@endif</td>
                                    </tr>

                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary bg-primary-4 white">
                        <h3 class="card-title">Sectoral Advisory Board Members</h3>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @if(sizeof($board_members) > 0)
                                <table class="table table-striped table-bordered all_indicators">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Title</th>
                                        <th>Phone</th>
                                        <th>Email</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($board_members as $member)
                                        <tr>
                                            <td>{{$member->name}}</td>
                                            <td>{{$member->title}}</td>
                                            <td>{{$member->phone}}</td>
                                            <td>{{$member->email}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <tr>
                                    <h2 class="text-center mt-4" style="color: #c9c9c9;">Board Members are yet to be uploaded.</h2>
                                </tr>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
