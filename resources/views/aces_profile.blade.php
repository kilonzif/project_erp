@extends('layouts.app')
@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endpush

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
                                    <td><strong>ACE</strong><br>{{$ace->name}}</td>
                                    <td><strong>Acronym</strong><br>{{$ace->acronym}}</td>
                                    <td><strong>Institution</strong><br>{{$ace->university->name}}</td>
                                    <td><strong>Contact</strong><br>{{$ace->contact}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong><br>{{$ace->email}}</td>
                                    <td><strong>Grant Amount1</strong><br>{{$ace->grant1}} - {{$currency1->name}}</td>
                                    <td><strong>Grant Amount2</strong><br>@if($currency2){{$ace->grant2}} - {{$currency2->name}}@endif</td>
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
                            <table class="table table-striped table-bordered contacts_table" id="contacts_table">
                                <thead>
                                <tr>
                                    <th>Role/Position</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($positions as $position)
                                    @php $count = 0; @endphp
                                    @foreach($contacts as $key=>$contact)
                                        @if($position->id == $contact->position_id)
                                            @php $count++; @endphp
                                            <tr>
                                                <td>
                                                    @php
                                                        $title = \App\Position::where('id',$contact->position_id)->first();
                                                    @endphp

                                                    {{$title->position_title}}
                                                </td>
                                                <td>{{$contact->person_title}} {{$contact->mailing_name}}</td>
                                                <td>{{$contact->gender}}</td>
                                                <td>{{$contact->mailing_email}}</td>
                                                <td>{{$contact->mailing_phone}}</td>
                                            </tr>
                                        @endif

                                    @endforeach

                                    @if($count == 0)
                                        <tr>
                                            <td>
                                                {{ $position->position_title }}
                                            </td>
                                            <td> ---</td>
                                            <td> ---</td>
                                            <td> ---</td>
                                            <td> ---</td>
                                        </tr>
                                    @endif
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
                        <h4 class="card-title">Institutional Readiness</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="inst_readiness">
                            <tr>
                                <td><strong>Requirement</strong></td>
                                <td><strong>Files / URL links / Comments</strong></td>
                                {{--<td><strong>URL links</strong></td>--}}
                                {{--<td><strong>Comments</strong></td>--}}
                                <td><strong>Submission Date</strong></td>

                            </tr>
                            <tbody>
                            @foreach($labels as $requirement)
                                @php $count = 0; @endphp
                                @foreach($indicator_ones as $key=>$data)
                                    @if($requirement == $data->requirement)
                                        @php $count++; @endphp
                                        <tr>
                                            <td>
                                                {{$requirement}}
                                            </td>
                                            <td>
                                                @if($data->file_one !="")
                                                    <strong>File 1</strong> -
                                                    <a href="{{asset('indicator1/'.$data->file_one)}}" target="_blank">
                                                        <span class="fa fa-file"></span> Download
                                                    </a>
                                                    <br>
                                                @endif
                                                @if($data->file_two !="")
                                                    <strong>File 2</strong> -
                                                    <a href="{{asset('indicator1/'.$data->file_two)}}" target="_blank">
                                                        <span class="fa fa-file"></span> Download
                                                    </a>
                                                    <br><br>
                                                @endisset
                                                @isset($data->url)
                                                    <strong>URL : </strong>{{$data->url}}
                                                        <br><br>
                                                @endisset
                                                @if(!empty($data->comments))
                                                    <strong>Comment : </strong><br>
                                                    {{$data->comments}}<br>
                                                @endif
                                            </td>
                                            <td>
                                                @isset($data->submission_date)
                                                    {{$data->submission_date}}
                                                @endisset
                                            </td>
                                        </tr>
                                    @endif

                                @endforeach

                                @if($count == 0)
                                    <tr>
                                        <td>
                                            {{$requirement}}
                                        </td>
                                        <td> ---</td>
                                        <td> ---</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
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
                                <table class="table table-striped table-bordered" id="sectoral_board">
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
                                    <h2 class="text-center mt-4" style="color: #c9c9c9;">Board Members are yet to be
                                        uploaded.</h2>
                                </tr>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
@push('vendor-script')
    <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script>
        $('#sectoral_board').dataTable( {
            "ordering": false
        } );
        $('#contacts_table').dataTable( {
            "ordering": false
        } );
        $('#inst_readiness').dataTable( {
            "ordering": false
        } );

        </script>
    @endpush
