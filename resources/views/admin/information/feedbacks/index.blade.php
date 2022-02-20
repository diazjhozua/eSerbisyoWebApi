@extends('layouts.admin')

@section('page-js')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('js/admin/information/feedbacks/index.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Feedbacks')

@section('content')

    {{-- Included Modals --}}
    @include('admin.information.feedbacks.respond')
   
    @include('admin.information.feedbacks.reportSelectModal')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Feedbacks
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
                class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif
    </div>


    <span>Feedbacks statistic within this month</span>
    <div class="row">
        {{-- Pending Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Feedback</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingFeedbackCount">{{ $feedbacksData->pending_count }}</div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="notedFeedbackCount">{{ $feedbacksData->noted_count }}</div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $feedbacksData->ignored_count }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $feedbacksData->feedbacks_count ? number_format(round($feedbacksData->positive_count * 100 / $feedbacksData->feedbacks_count),0,'.','') . '%' : '0%' }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $feedbacksData->feedbacks_count ? number_format(round($feedbacksData->neutral_count * 100 / $feedbacksData->feedbacks_count),0,'.','') . '%' : '0%' }} </div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $feedbacksData->feedbacks_count ? number_format(round($feedbacksData->negative_count * 100 / $feedbacksData->feedbacks_count),0,'.','') . '%' : '0%' }} </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-angry fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <p class="mt-3">These are the list of feedbacks sent by the residents of barangay Cupang. Please respond to the following pending reports.</p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: {{ $feedbacks->count() }})</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Submitted by</th>
                            <th>Type</th>
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
                            <th>Type</th>
                            <th>Polarity</th>
                            <th>Message</th>
                            <th>Admin Respond</th>
                            <th>Status</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($feedbacks as $feedback)
                            <tr>
                                <td>{{ $feedback->id }}</td>
                                <td>{{ $feedback->is_anonymous ? 'Anonymous User' :  $feedback->user->getFullNameAttribute() }}</td>
                                @if ($feedback->type_id != 0)
                                    <td><a href="{{ route('admin.feedback-types.show', $feedback->type_id) }}">{{ $feedback->type->name }}</a></td>
                                @else
                                    <td>Others/Deleted- {{ $feedback->custom_type }}</td>
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






