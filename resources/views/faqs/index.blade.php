@extends('layouts.app')

@push('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endpush
@push('end-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/dual-listbox.css')}}">
@endpush

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

    <div class="content-body">
        <a href="{{route('faq.new')}}" class="btn btn-primary mb-1 right"><i class="ft-plus"></i> Add New</a>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Frequently Asked Questions</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0 faqs-list">
                                    <thead>
                                    <tr>
                                        <th width="30px">No.</th>
                                        <th>Question</th>
                                        <th width="120px">Faq Category</th>
                                        <th width="150px">Created On</th>
                                        <th width="150px">Added By</th>
                                        <th width="50px">Status</th>
                                        <th width="100px">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $count=0;@endphp
                                    @foreach($faqs as $faq)
                                        @php $count++; @endphp
                                        <tr>
                                            <td>{{$count}}</td>
                                            <td>{{$faq->question}}</td>
                                            <td>{{$faq->category}}</td>
                                            <td>{{date('M d, Y',strtotime($faq->created_at))}}</td>
                                            <td>{{$faq->user->email}}</td>
                                            <td>
                                                @php
                                                    $type = "success";
                                                    $text = "Active";
                                                    if ($faq->status == false) {$type = "danger"; $text = "Inactive";};
                                                @endphp<span class="badge badge-{{$type}}">{{$text}}</span>
                                            </td>
                                            <td>
                                                <a href="{{route('faq.edit',[$faq->id])}}" class="btn btn-primary btn-sm btn-flat" style="margin-right: 7px;">
                                                    <i class="ft-edit"></i>
                                                </a>
                                                <button class="btn btn-danger btn-sm btn-flat" onclick="trashRequest({{$faq->id}})" type="button">
                                                    <i class="ft-trash"></i>
                                                </button>
                                                <form id="delete-faq-{{$faq->id}}"
                                                      action="{{ route('faq.delete',[$faq->id]) }}"method="POST" style="display: none;">
                                                    @csrf {{method_field('DELETE')}}
                                                    <input type="hidden" name="id" value="{{$faq->id}}">
                                                </form>
                                            </td>
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
    </div>
@endsection

@push('vendor-script')
    <script src="{{asset('app-assets/vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
@endpush
@push('end-script')
    <script>
        $('.faqs-list').dataTable(
            {
                "columnDefs": [
                    { "orderable": false, "targets": 4 }
                ],
                "order": [[ 1, "asc" ]]
            }
        );
    </script>
    <script>
        function trashRequest(key) {
            if (confirm("Data will be lost after Deletion. Delete?")){
                $('form#delete-faq-'+key).submit();
            }
        }
    </script>
@endpush