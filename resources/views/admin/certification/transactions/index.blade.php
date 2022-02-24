@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script>
        $('#userTransaction').addClass('active');
    </script>
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
@section('title', 'User Transactions')


@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> User transactions
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <p class="mt-4 mb-0">
        These are the list of users with transactions sorted by the highest order count. Use this page to view and inspect the following transactions they made using the
        e-Serbisyo android application.
    </p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Total: <span id="usersCount">{{ $users->count()}}</span> (Users)</h6>
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
                            <th>Orders Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Orders Count</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>
                                    <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                    src="{{ isset($user->file_path) ? $user->file_path :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$user->getFullNameAttribute()}} image">
                                </td>

                                <td>{{$user->getFullNameAttribute()}}</td>
                                <td>{{$user->email}}</td>

                                <td>{{$user->orders_count}}</td>

                                <td>
                                    <ul class="list-inline m-0">

                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.transactions.show', $user->id) }}">
                                                View Transaction <i class="fas fa-eye"></i>
                                            </a>
                                        </li>

                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No users yet.n</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
