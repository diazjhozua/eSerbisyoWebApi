@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/certification/bikers/index.js')}}"></script>
@endsection

@section('page-css')
    <style>
        .picture-box img {
            text-align: center;
            width: 200px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 0px;
        }
    </style>
@endsection

{{-- Title Page --}}
@section('title', 'Bikers')

@section('content')

    @include('admin.certification.bikers.demoteModal')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Bikers
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Total: <span id="bikerCount">{{ $bikers->count()}}</span> (Bikers)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Bike</th>
                            <th>Bike Information</th>
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Bike</th>
                            <th>Bike Information</th>
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($bikers as $biker)
                            <tr>
                                <td>{{$biker->id}}</td>
                                <td>
                                    <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                    src="{{ isset($biker->file_path) ? asset('storage/'.$biker->file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$biker->getFullNameAttribute()}} image">
                                </td>

                                <td>{{$biker->getFullNameAttribute()}}</td>
                                <td>{{$biker->email}}</td>

                                <td>
                                    <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                    src="{{ isset($biker->latest_biker_request->credential_file_path) ? asset('storage/'.$biker->latest_biker_request->credential_file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$biker->getFullNameAttribute()}}'s bike">
                                </td>

                                <td>
                                    <p><span class="font-weight-bold">Type: </span> {{ $biker->bike_type }}</p>
                                    <p><span class="font-weight-bold">Color: </span> {{ $biker->bike_color }}</p>
                                    <p><span class="font-weight-bold">Size: </span> {{ $biker->bike_size }}</p>
                                </td>

                                <td>{{$biker->created_at}}</td>

                                <td>
                                    <ul class="list-inline m-0">

                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.bikers.profile', $biker->id) }}">
                                                View Transaction <i class="fas fa-eye"></i>
                                            </a>
                                        </li>

                                        <li class="list-item mb-1">
                                            <button class="btn btn-danger btn-sm btnDemote" onclick="demote({{$biker->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Demote Biker">
                                                <span class="btnText btnDemoteTxt">Demote</span>
                                                <i class="btnDemoteIcon fas fa-arrow-down ml-1"></i>
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
