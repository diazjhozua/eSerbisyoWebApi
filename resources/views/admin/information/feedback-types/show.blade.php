@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/information/feedback-types/show.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', $type->name)

@section('content')

    @include('admin.information.feedbacks.respond')

    @section('reportRoute', route('admin.feedback-types.report.show', $type->id))
    @include('admin.information.feedbacks.reportSelectModal')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button> Type: {{ $type->name }}
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
            class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
    </div>

    <p class="font-weight-light">Created at: {{ $type->created_at }} -- Updated at: {{ $type->updated_at }}</p>
    <p class="font-weight-light"></p>

    <div class="row">
        {{-- Pending Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Feedback</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingFeedbackCount"> {{ $type->pending_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plus-circle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Noted Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Noted Feedback</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="notedFeedbackCount">{{ $type->noted_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-square fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Ignored Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Ignored Feedback</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $type->ignored_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trash-alt fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Positive Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Positive Feedback</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $type->feedbacks_count ? number_format(round($type->positive_count * 100 / $type->feedbacks_count),0,'.','') . '%' : '0%' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-smile-beam fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Neutral Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Neutral Feedback</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $type->feedbacks_count ? number_format(round($type->neutral_count * 100 / $type->feedbacks_count),0,'.','') . '%' : '0%' }} </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-meh fa-2x text-secondary-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Negative Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Negative Feedback</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $type->feedbacks_count ? number_format(round($type->negative_count * 100 / $type->feedbacks_count),0,'.','') . '%' : '0%' }} </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-angry fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Feedbacks about {{ $type->name }} (Total: {{ $type->feedbacks_count}} feedbacks)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Submitted by</th>
                            @if ($type->id == 0)
                                <th>Custom-Type</th>
                            @endif
                            <th>Polarity</th>
                            <th>Message</th>
                            <th>Admin Respond</th>
                            <th>Status</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Submitted by</th>
                            @if ($type->id == 0)
                                <th>Custom-Type</th>
                            @endif
                            <th>Polarity</th>
                            <th>Message</th>
                            <th>Admin Respond</th>
                            <th>Status</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($type->feedbacks as $feedback)
                            <tr>
                                <td>{{ $feedback->id }}</td>
                                <td>{{ $feedback->is_anonymous ? 'Anonymous User' :  $feedback->user->getFullNameAttribute() }}</td>
                                 @if ($type->id == 0)
                                    <td>{{ $feedback->custom_type }}</td>
                                @endif

                                <td>{{ $feedback->polarity}}</td>
                                <td>{{ $feedback->message}}</td>
                                @if (!empty($feedback->admin_respond))
                                    <td>{{ $feedback->admin_respond }}</td>
                                @else
                                    <td>Not yet responded</td>
                                @endif

                                <td>{{ $feedback->status}}</td>
                                <td>{{ $feedback->created_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-primary btn-sm" type="button" onclick="sendRespond({{ $feedback->id }})" data-toggle="tooltip" data-placement="top" title="Respond" {{$feedback->status != 'Pending' ? 'disabled' : ''}}>
                                                {{$feedback->status != 'Pending' ? 'Responded' : 'Respond'}}
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>

                        @empty
                            <p>No feedback yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
