<style>
    body {font-family: Arial;}

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        -webkit-animation: fadeEffect 1s;
        animation: fadeEffect 1s;
    }

    /* Fade in tabs */
    @-webkit-keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
    }

    @keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
    }
</style>

@extends('layouts.app')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">FAQS
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col-md-8 offset-2">
                <div style="padding: 20px; display:inline">
                    <button class="tablinks" onclick="openFaq(event, 'all')" style="padding: 20px;">All FAQs</button>
                    <button class="tablinks" onclick="openFaq(event, 'general')" style="padding: 20px;">General FAQs</button>
                    <button class="tablinks" onclick="openFaq(event, 'aces')" style="padding: 20px;">Aces FAQs</button>
                    <button class="tablinks" onclick="openFaq(event, 'system')" style="padding: 20px;">System FAQs</button>
                </div>

            </div>

        <div class="col-md-8 offset-2">
            <div id="all" class="tabcontent col-md-12">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="content-header row grey-blue">
                            <div class="content-header-center text-center col-12 mb-1">
                                <h4 class="content-header-title" style="text-transform: none">Frequently Asked Questions</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="content-body">
                            <!-- HTML Markup -->
                            @if(sizeof($allfaqs) > 0)
                                @foreach($allfaqs as $faq)
                                    <div class="card mb-1">
                                        <div class="card-header">
                                            <h4 class="card-title" style="font-weight: 400; text-transform: none">{{$faq->question}}</h4>
                                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                            <div class="heading-elements">
                                                <ul class="list-inline mb-0">
                                                    <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-content collapse">
                                            <div class="card-body">
                                                <div class="card-text">
                                                    {!! $faq->answer !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h1 class="text-center mt-4" style="color: #c9c9c9;">FAQs are yet to be uploaded.</h1>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div id="general" class="tabcontent col-md-12">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="content-header row grey-blue">
                            <div class="content-header-center text-center col-12 mb-1">
                                <h4 class="content-header-title" style="text-transform: none">Frequently Asked Questions</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="content-body">
                            <!-- HTML Markup -->
                            @if(sizeof($general) > 0)
                                @foreach($general as $general)
                                    <div class="card mb-1">
                                        <div class="card-header">
                                            <h4 class="card-title" style="font-weight: 400; text-transform: none">{{$general->question}}</h4>
                                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                            <div class="heading-elements">
                                                <ul class="list-inline mb-0">
                                                    <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-content collapse">
                                            <div class="card-body">
                                                <div class="card-text">
                                                    {!! $general->answer !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h1 class="text-center mt-4" style="color: #c9c9c9;">FAQs are yet to be uploaded.</h1>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div id="aces" class="tabcontent col-md-12">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="content-header row grey-blue">
                            <div class="content-header-center text-center col-12 mb-1">
                                <h4 class="content-header-title" style="text-transform: none">Frequently Asked Questions</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="content-body">
                            <!-- HTML Markup -->
                            @if(sizeof($aces) > 0)
                                @foreach($aces as $aces)
                                    <div class="card mb-1">
                                        <div class="card-header">
                                            <h4 class="card-title" style="font-weight: 400; text-transform: none">{{$aces->question}}</h4>
                                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                            <div class="heading-elements">
                                                <ul class="list-inline mb-0">
                                                    <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-content collapse">
                                            <div class="card-body">
                                                <div class="card-text">
                                                    {!! $aces->answer !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h1 class="text-center mt-4" style="color: #c9c9c9;">FAQs are yet to be uploaded.</h1>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div id="system" class="tabcontent col-md-12">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="content-header row grey-blue">
                            <div class="content-header-center text-center col-12 mb-1">
                                <h4 class="content-header-title" style="text-transform: none">Frequently Asked Questions</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="content-body">
                            <!-- HTML Markup -->
                            @if(sizeof($system) > 0)
                                @foreach($system as $system)
                                    <div class="card mb-1">
                                        <div class="card-header">
                                            <h4 class="card-title" style="font-weight: 400; text-transform: none">{{$system->question}}</h4>
                                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                            <div class="heading-elements">
                                                <ul class="list-inline mb-0">
                                                    <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-content collapse">
                                            <div class="card-body">
                                                <div class="card-text">
                                                    {!! $system->answer !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h1 class="text-center mt-4" style="color: #c9c9c9;">FAQs are yet to be uploaded.</h1>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>





@endsection

    <script>
        function openFaq(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>



