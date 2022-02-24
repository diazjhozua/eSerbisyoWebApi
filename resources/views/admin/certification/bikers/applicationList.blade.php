@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/certification/bikers/applicationList.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Bikers Application Request')

@section('content')

    @include('admin.certification.bikers.applicationFormModal')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Bikers Application Request
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Total: <span id="applicationCount">{{ $applications->count()}}</span> (Applications Request)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th></th>
                            <th>Name</th>
                            {{-- <th>Email</th> --}}
                            {{-- <th>Bike</th> --}}
                            <th>Bike Information</th>
                            <th>Reason for joining</th>
                            <th>Status</th>
                            {{-- <th>Admin Message</th> --}}
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th></th>
                            <th>Name</th>
                            {{-- <th>Email</th> --}}
                            {{-- <th>Bike</th> --}}
                            <th>Bike Information</th>
                            <th>Reason for joining</th>
                            <th>Status</th>
                            {{-- <th>Admin Message</th> --}}
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($applications as $application)
                            <tr>
                                <td>{{$application->id}}</td>
                                <td>
                                    <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                    src="{{ isset($application->user->file_path) ? $application->user->file_path :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$application->user->getFullNameAttribute()}} image">
                                </td>

                                <td>({{ $application->user->id }})  {{$application->user->getFullNameAttribute()}}</td>
                                {{-- <td>{{ $application->user->email }}</td> --}}

                                {{-- <td>
                                    <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                    src="{{ isset($application->credential_file_path) ? asset('storage/'.$application->credential_file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$application->user->getFullNameAttribute()}}'s bike">
                                </td> --}}

                                <td>
                                    <p><span class="font-weight-bold">Type: </span> {{ $application->bike_type }}</p>
                                    <p><span class="font-weight-bold">Color: </span> {{ $application->bike_color }}</p>
                                    <p><span class="font-weight-bold">Size: </span> {{ $application->bike_size }}</p>
                                </td>

                                <td>{{$application->reason}}</td>
                                <td>{{$application->status}}</td>
                                {{-- <td>{{ $application->admin_message != null ? $application->admin_message : 'Not yet responded'}}</td> --}}

                                <td>{{$application->created_at}}</td>

                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-item mb-1">
                                            <button class="btn btn-info btn-sm" type="button" onclick="viewRequest({{ $application->id }})" data-toggle="tooltip" data-placement="top" title="View Request" {{ $application->status == 'Pending' ? '' : 'disabled'}}>
                                                <span class="btnText btnVerifyTxt">Review Request</span>
                                                <i class="btnVerifyIcon fas fa-money-check ml-1"></i>
                                                <i class="btnVerifyLoadingIcon fa fa-spinner fa-spin" hidden></i>
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No bikers yet. Please approve pending bikers application</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
