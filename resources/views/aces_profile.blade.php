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
                    <div class="card-header bg-dark bg-darken-4 white">
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
                                    <td style="width: 50px;"><strong>Acronym</strong><br>{{$ace->acronym}}</td>
                                    <td><strong>Institution</strong><br>{{$ace->university->name}}</td>
                                    <td style="width: 50px;"><strong>Contact</strong><br>{{$ace->contact}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong><br>{{$ace->email}}</td>
                                    <td><strong>Grant Amount</strong><br>{{$ace->dlr}}</td>
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
                    <div class="card-content collapse show">
                        <div class="card-body">
                    <h3 class="card-title">Ace Contacts</h3>
                    <table class="table table-striped table-bordered all_indicators">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Title</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td>{{$contact->contact_name}}</td>
                                <td>{{$contact->email}}</td>
                                <td>{{$contact->contact_phone}}</td>
                                <td>{{$contact->contact_title}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
