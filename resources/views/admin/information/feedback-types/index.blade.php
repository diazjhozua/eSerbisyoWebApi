@extends('layouts.admin')


@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/information/feedback-types/index.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Feedback Types')

@section('content')

    {{-- Included Modals --}}

    {{-- Create/Edit --}}
    @include('admin.types.form')
    @include('admin.information.feedback-types.report')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Feedback Types
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
                class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif
    </div>

    <p class="text-justify">Feedback type can be classified as a topic where registered residents can choose what topic they are going to give feedback.
        For instance: If you want to see the overall feedbacks on the Covid-19 taskforce of the barangay, create a feedback type named "Covid-19 Task Force" to see
        the residents feedback (When you delete a specific type of feedback, all of the feedbacks
        related to that type would be transfer in the "Others" section)</a>.
    </p>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" onclick="createType()">
        Create
    </button>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="typeCount">{{ $types->count() }}</span>)</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Feedbacks Count</th>
                            <th>Positive</th>
                            <th>Neutral</th>
                            <th>Negative</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Feedbacks Count</th>
                            <th>Positive</th>
                            <th>Neutral</th>
                            <th>Negative</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>

                    <tbody>
                        @forelse ($types as $type)
                            <tr>
                                <td>{{ $type->id }}</td>
                                <td>{{ $type->name }}</td>
                                <td>{{ $type->feedbacks_count}}</td>
                                <td>{{ $type->feedbacks_count ? number_format(round($type->positive_count * 100 / $type->feedbacks_count),0,'.','') . '%' : '0%' }}</td>
                                <td>{{ $type->feedbacks_count ? number_format(round($type->neutral_count * 100 / $type->feedbacks_count),0,'.','') . '%' : '0%' }}</td>
                                <td>{{ $type->feedbacks_count ? number_format(round($type->negative_count * 100 / $type->feedbacks_count),0,'.','') . '%' : '0%' }}</td>
                                <td>{{ $type->updated_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.feedback-types.show', $type->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
                                        @if ($type->id !== 0)
                                            <li class="list-inline-item mb-1">
                                                <button class="btn btn-primary btn-sm editBtn" onclick="editType({{ $type->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </li>
                                            <li class="list-inline-item mb-1">
                                                <button class="btn btn-danger btn-sm deleteBtn" type="button" onclick="deleteType({{ $type->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No feedback types created</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection





