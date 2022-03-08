@extends('layouts.admin')

@section('page-js')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('js/admin/information/inquiries/index.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Inquiries')

@section('content')

    {{-- Included Modals --}}
    @include('admin.information.inquiries.respond')

    @include('admin.information.inquiries.reportSelectModal')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inquiries
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
                class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif
    </div>

    <span>Inquiries statistic within this month</span>
    <div class="row">
        {{-- Pending Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Inquiry</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingFeedbackCount">{{ $inquiriesData->pending_count }}</div>
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
                                Noted Inquiry</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="notedFeedbackCount">{{ $inquiriesData->noted_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-square fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p class="mt-3">These are the list of inquiries sent by the residents of barangay Cupang. Please respond to the following pending inquiries</p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: {{ $inquiries->count() }})</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Submitted by</th>
                            <th>About</th>
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
                            <th>About</th>
                            <th>Message</th>
                            <th>Admin Respond</th>
                            <th>Status</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($inquiries as $inquiry)
                            <tr>
                                <td>{{ $inquiry->id }}</td>
                                <td>{{ $inquiry->user->getFullNameAttribute()   }}</td>
                                <td>{{ $inquiry->about}}</td>
                                <td>{{ $inquiry->message}}</td>
                                @if (!empty($inquiry->admin_message))
                                    <td>{{ $inquiry->admin_message }}</td>
                                @else
                                    <td>Not yet responded</td>
                                @endif

                                <td>{{ $inquiry->status}}</td>
                                <td>{{ $inquiry->created_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-primary btn-sm" type="button" onclick="sendRespond({{ $inquiry->id }})" data-toggle="tooltip" data-placement="top" title="Respond" {{$inquiry->status != 'Pending' ? 'disabled' : ''}}>
                                                {{$inquiry->status != 'Pending' ? 'Responded' : 'Respond'}}
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
