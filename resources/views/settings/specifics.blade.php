@extends('layouts.app')
@push('vendor-styles')
@endpush
@push('other-styles')
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">Specifics</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Settings
                        </li>
                        <li class="breadcrumb-item active">Specifics
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        {{--<div class="row">--}}
            {{--<div class="col-12">--}}
                {{--<div class="card">--}}
                    {{--<div class="card-header">--}}
                        {{--<h4 class="card-title">All Reports</h4>--}}
                        {{--<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>--}}
                        {{--<div class="heading-elements">--}}
                            {{--<ul class="list-inline mb-0">--}}
                                {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-content">--}}
                        {{--<div class="card-body">--}}
                            {{----}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}




 <div class="card">
                <div class="card-header">
                  <h4 class="card-title" id="basic-layout-form">Specifics  Info</h4>
                  <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
                      <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                     
                    </ul>
                  </div>
                </div>
                <div class="card-content collapse show">
                  <div class="card-body">
                    
                    <form class="form">
                      <div class="form-body">
                        <div class="row">
                          <div class="col-md-9">
                            <div class="form-group">
                              <label for="projectinput1">Title</label>
                              <input type="text" id="projectinput1" class="form-control" placeholder="Title"
                              name="fname">
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="projectinput2">Order Number</label>
                              <input type="text" id="projectinput2" class="form-control" placeholder="Order Number"
                              name="lname">
                            </div>
                          </div>
                        </div>
                        
                      </div>
                      <div class="">
                        <button type="submit" class="btn btn-primary">
                           Submit
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>











































    </div>
@endsection
@push('vendor-script')
@endpush
@push('end-script')
@endpush