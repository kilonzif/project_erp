@extends('layouts.app')
@push('end-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('wysihtml5/bootstrap3-wysihtml5.min.css')}}">
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('faqs')}}">FAQS</a>
                        </li>
                        <li class="breadcrumb-item active">Add New FAQ
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #4a8ccb47">Add New FAQ</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('faq.save') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="question">{{ __('Question') }}</label>

                                    <div>
                                        <input id="question" type="text" class="form-control{{ $errors->has('question') ? ' is-invalid' : '' }}"
                                               name="question" value="{{ old('question') }}" required autofocus>

                                        @if ($errors->has('question'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('question') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">{{ __('Description') }}</label>

                                    <div>
                                    <textarea name="description"  id="editor1" cols="30" rows="7"
                                              class="textarea form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{ old('description') }}</textarea>
                                        <script src="{{asset('ckeditor/ckeditor.js')}}" type="text/javascript"></script>
                                        @if ($errors->has('description'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-1">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">{{ __('Type of Question') }}</label>
                                    <div>
                                        <select id="category" class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}"
                                               name="category"  required >
                                            <option value="General FAQs">General FAQs</option>
                                            <option value="System FAQs">System FAQs</option>
                                            <option value="ACE FAQs">ACE FAQs</option>
                                        </select>

                                        @if ($errors->has('category'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('category') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="d-inline-block custom-control custom-checkbox mr-1">
                                        <input type="checkbox" class="custom-control-input" checked value="1" name="active" id="active">
                                        <label class="custom-control-label" for="active">Set FAQ to visible</label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-1">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="">
                                        <button type="submit" class="btn btn-success mr-2">
                                            {{ __('Save') }}
                                        </button>
                                        <a href="{{route('faqs')}}" class="btn btn-secondary left">
                                            {{ __('Cancel') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('end-script')
    {{--<script src="{{asset('ckeditor/ckeditor.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('wysihtml5/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>
    <script>
        $(function () {
            CKEDITOR.replace('editor1');
        });
    </script>
@endpush
