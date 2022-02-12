@extends('layouts.admin')

@section('page-js')
    <script>
        $('#certificate').addClass('active')
    </script>
@endsection

{{-- Title Page --}}
@section('title', 'Certificates')

@section('content')

    {{-- Included Modals --}}

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Certificates
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        {{-- @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
                class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif --}}
    </div>

    <p class="text-justify">
        These are the list of the available certificates that the residents's can avail. You can modify the price, and edit requirements of a specific certificate.
    </p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="typeCount">{{ $certificates->count() }}</span>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Certificate</th>
                            <th>Price</th>
                            <th>Generated Certificate</th>
                            <th>Delivery Availability</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Certificate</th>
                            <th>Price</th>
                            <th>Generated Certificate</th>
                            <th>Delivery Availability</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($certificates as $certificate)
                            <tr>
                                <td>{{ $certificate->id }}</td>
                                <td>{{ $certificate->name }}</td>
                                <td>{{ '₱'.number_format($certificate->price, 2) }}</td>
                                <td>{{ $certificate->certificate_forms_count }}</td>
                                <td>{{ $certificate->is_open_delivery == 1 ? 'Open for delivery' : 'Not available for delivery' }}</td>
                                <td>{{ $certificate->updated_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.certificates.show', $certificate->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No certificates created</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection





