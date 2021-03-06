
@extends('layouts.app')

@section('content')
    <style>
        .nav-vertical .nav-left.nav-tabs li.nav-item a.nav-link{
            min-width: 12rem;
        }
        .nav-vertical .nav-left.nav-tabs.nav-border-left li.nav-item a.nav-link{
            color:#555;
        }
        .nav-vertical .nav-left.nav-tabs.nav-border-left li.nav-item a.nav-link.active {
            border-left: 3px solid #00B5B8;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            color:#00B5B8;
        }
    </style>
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Ace-Impact</a>
                        </li>
                        <li class="breadcrumb-item active">FAQS
                        </li>
                    </ol>
                    <h4>Frequently Asked Questions</h4>
                </div>

            </div>
        </div>
    </div>

    <section id="justified-bottom-border">
        <div class="row match-height">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="nav-vertical">
                            <ul class="nav nav-tabs nav-left nav-border-left" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="active-tab32" data-toggle="tab" href="#active32" aria-controls="active32" role="tab" aria-selected="true">General FAQs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="link-tab32" data-toggle="tab" href="#link32" aria-controls="link32" role="tab" aria-selected="false">Reporting FAQs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="linkOpt-tab2" data-toggle="tab" href="#linkOpt2" aria-controls="linkOpt2">System FAQs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="linkOpt-tab22" data-toggle="tab" href="#linkOpt22" aria-controls="linkOpt22">Verification FAQs</a>
                                </li>
                            </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content px-1">
                                <div class="tab-pane active in" id="active32" aria-labelledby="active-tab32" role="tabpanel">
                                    @if(sizeof($general) > 0)
                                        @foreach($general as $general_faq)
                                            <div class="card mb-1">
                                                <div class="card-header">
                                                    <h4 style="font-weight: bold">{{$general_faq->question}}</h4>
                                                </div>
                                                <div class="card-content">
                                                        <div class="card-body">
                                                            {!! $general_faq->answer !!}
                                                        </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <h1 class="text-center mt-4" style="color: #c9c9c9;">FAQs are yet to be uploaded.</h1>
                                    @endif
                                 </div>
                                <div class="tab-pane" id="link32" aria-labelledby="link-tab32" role="tabpanel">
                                    @if(sizeof($reporting) > 0)
                                        @foreach($reporting as $reporting_faq)
                                            <div class="card mb-1">
                                                <div class="card-header">
                                                    <h4 style="font-weight: bold">{{$reporting_faq->question}}</h4>
                                                </div>
                                                <div class="card-content">
                                                        <div class="card-body">
                                                            {!! $reporting_faq->answer !!}
                                                        </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <h1 class="text-center mt-4" style="color: #c9c9c9;">FAQs are yet to be uploaded.</h1>
                                    @endif
                                    </div>
                                <div class="tab-pane" id="linkOpt2" aria-labelledby="linkOpt-tab2" role="tabpanel">
                                    @if(sizeof($system) > 0)
                                        @foreach($system as $system_faq)
                                            <div class="card mb-1">
                                                <div class="card-header">
                                                    <h4 style="font-weight: bold;">{{$system_faq->question}}</h4>
                                                </div>
                                                <div class="card-content">
                                                        <div class="card-body">
                                                            {!! $system_faq->answer !!}
                                                        </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <h1 class="text-center mt-4" style="color: #c9c9c9;">FAQs are yet to be uploaded.</h1>
                                    @endif
                                </div>
                                <div class="tab-pane" id="linkOpt22" aria-labelledby="linkOpt-tab22" role="tabpanel">
                                    @if(sizeof($verification) > 0)
                                        @foreach($verification as $verification_faq)
                                            <div class="card mb-1">
                                                <div class="card-header">
                                                    <h4 style="font-weight: bold">{{$verification_faq->question}}</h4>
                                                </div>
                                                <div class="card-content">
                                                        <div class="card-body">
                                                            {!! $verification_faq->answer !!}
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
    </section>

@endsection



