@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/information/androids/index.js')}}"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('246912fa37725a18907b', {
            cluster: 'ap1',
            authEndpoint: '/broadcasting/auth',
            forceTLS: true,
            auth: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }
        });

        var channel = pusher.subscribe('private-new-report');
        channel.bind('new-report', function(data) {
            alert(JSON.stringify(data));
        });

        channel.bind('pusher:subscription_succeeded', function(members) {
            toastr.info('You can now receive a report real time')
        });

    </script>

@endsection

{{-- Title Page --}}
@section('title', 'Androids')

@section('content')

    {{-- Included Modals --}}
    @include('admin.information.androids.formModal')

    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Android Versions
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" onclick="createAndroid()">
        Create
    </button>

    <p class="mt-3">These are the list of android versions that resident will download to use the application</p>

@endsection






