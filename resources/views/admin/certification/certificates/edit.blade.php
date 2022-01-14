
@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/taskforce/missing-items/show.js')}}"></script>

@endsection


{{-- Title Page --}}
@section('title', $certificate->name)

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i>
            </button> <span id="currentReportType">{{ $certificate->name }}</span>:<span id="currentName"> {{ $missing_item->item }}
            (#{{ $missing_item->id}})</span>
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>

            {{-- Edit --}}
            <button class="btn btn-primary btn-sm" onclick="editReport({{ $missing_item->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                <i class="fas fa-edit"></i>
            </button>

            {{-- Delete --}}
            <button class="btn btn-danger btn-sm" type="button" onclick="deleteReport({{ $missing_item->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                <i class="fas fa-trash-alt"></i>
            </button>

            {{-- Change Statuus --}}
            <button class="btn btn-info btn-sm" type="button" onclick="changeStatusReport({{ $missing_item->id }})" data-toggle="tooltip" data-placement="top" title="Status">
                Change Status
            </button>
        </h1>
    </div>

@endsection
