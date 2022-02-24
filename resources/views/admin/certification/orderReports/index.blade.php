@extends('layouts.admin')

@section('page-js')
    <script src="{{ asset('js/admin/certification/orderReports/index.js')}}"></script>
@endsection


{{-- Title Page --}}
@section('title', 'Order Reports')

@section('content')

    @include('admin.certification.orderReports.respond')
    @include('admin.certification.orderReports.reportSelectModal')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order Reports
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
                class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif
    </div>

    <p class="text-justify">
        These are the list of the submitted reports within the order.
    </p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="typeCount">{{ $orderReports->count() }}</span>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Order ID</th>
                            <th>Report Message</th>
                            <th>Admin Respond</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Order ID</th>
                            <th>Report Message</th>
                            <th>Admin Respond</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($orderReports as $orderReport)
                            <tr>
                                <td>{{ $orderReport->id }}</td>
                                <td>{{ $orderReport->user->getFullNameAttribute() }} (#{{ $orderReport->user_id }})</td>
                                <td>{{ $orderReport->user->user_role->role }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $orderReport->order_id) }}">
                                        From Transaction #{{ $orderReport->order_id }}
                                    </a>
                                </td>

                                <td>{{ $orderReport->body }}</td>

                                <td>
                                    @if ($orderReport->status == 'Noted')
                                        <div class="p-2 bg-success text-white rounded-pill text-center">
                                    @elseif ($orderReport->status == 'Pending')
                                        <div class="p-2 bg-warning text-white rounded-pill text-center">
                                    @endif
                                        {{ $orderReport->status }}
                                    </div>
                                </td>

                                <td>
                                    @if ($orderReport->admin_message == null)
                                        Not yet responded to this report
                                    @else
                                        {{ $orderReport->admin_message }}
                                    @endif

                                    </div>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($orderReport->created_at)->format('F d, Y') }}
                                </td>
                                <td>
                                    @if ($orderReport->status == 'Noted')
                                        Already responded to this report
                                    @else
                                        <ul class="list-inline m-0">
                                            <li class="list-inline-item mb-1">
                                                <button class="btn btn-primary btn-sm" type="button" onclick="sendRespond({{ $orderReport->id }})" data-toggle="tooltip" data-placement="top" title="Respond">
                                                    Respond
                                                </button>
                                            </li>
                                        </ul>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <p>No data</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
