
@extends('layouts.app')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
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
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-underline no-hover-bg nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="active-tab32" data-toggle="tab" href="#active32" aria-controls="active32" role="tab" aria-selected="true">All FAQs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="link-tab32" data-toggle="tab" href="#link32" aria-controls="link32" role="tab" aria-selected="false">General FAQs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="linkOpt-tab2" data-toggle="tab" href="#linkOpt2" aria-controls="linkOpt2">Aces FAQs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="linkOpt-tab22" data-toggle="tab" href="#linkOpt22" aria-controls="linkOpt22">System FAQs</a>
                                </li>
                            </ul>
                            <div class="tab-content px-1 pt-1">
                                <div class="tab-pane active in" id="active32" aria-labelledby="active-tab32" role="tabpanel">
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
                                <div class="tab-pane" id="link32" aria-labelledby="link-tab32" role="tabpanel">
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
                                <div class="tab-pane" id="linkOpt2" aria-labelledby="linkOpt-tab2" role="tabpanel">
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
                                <div class="tab-pane" id="linkOpt22" aria-labelledby="linkOpt-tab22" role="tabpanel">
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

        </div>
    </section>

@endsection



