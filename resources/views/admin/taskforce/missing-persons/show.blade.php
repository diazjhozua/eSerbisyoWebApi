@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/taskforce/missing-persons/show.js')}}"></script>

@endsection

{{-- Title Page --}}
@section('title', $missing_person->name . ' Missing missing_person')

@section('content')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button> <span class="reportType">{{ $missing_person->report_type }}</span>:<span id="reportName"> {{ $missing_person->name }}
            (#{{ $missing_person->id}})</span>
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>

            {{-- Edit --}}
            <button class="btn btn-primary btn-sm" onclick="editReport({{ $missing_person->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                <i class="fas fa-edit"></i>
            </button>

            {{-- Delete --}}
            <button class="btn btn-danger btn-sm" type="button" onclick="deleteReport({{ $missing_person->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                <i class="fas fa-trash-alt"></i>
            </button>

            {{-- Change Statuus --}}
            <button class="btn btn-info btn-sm" type="button" onclick="deleteReport({{ $missing_person->id }})" data-toggle="tooltip" data-placement="top" title="Status">
                Change Status
            </button>

        </h1>

        <a href="" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" target="_blank">
            <i class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
    </div>


    <div class="information">
        <div class="row">
            <div class="col mt-3">
                {{-- Missing Report Information --}}
                <div class="card text-black mb-3">
                    <img class="mx-auto d-block" style="height:200px; max-height: 200px; max-width:200px; width: 200px;" id="currentImage" src="{{ asset('storage/'.$missing_person->file_path) }}" alt="Card image cap">
                    <div class="card-body">

                        <p class="card-text"><strong>Submitted By: {{ $missing_person->user->getFullNameAttribute() }} #{{ $missing_person->user->id }} - ({{ $missing_person->user->user_role->role }}) </strong></p>
                        <p class="card-text"><small class="text-muted">Submitted at: <span id="currentCreatedAt"> {{ $missing_person->created_at }}</span> || Updated at: <span id="currentUpdatedAt">{{ $missing_person->updated_at }}</span></small></p>

                        <div class="form-group">
                            <label>Status</label>
                            @if ($missing_person->status == 'Pending')
                                <div class="p-2 bg-info text-white rounded-pill text-center">
                            @elseif ($missing_person->status == 'Approved')
                                <div class="p-2 bg-dark text-white rounded-pill text-center">
                            @elseif ($missing_person->status == 'Resolved')
                                <div class="p-2 bg-success text-white rounded-pill text-center">
                            @elseif ($missing_person->status == 'Denied')
                                <div class="p-2 bg-danger text-white rounded-pill text-center">
                            @endif
                                {{ $missing_person->status }}
                            </div>
                        </div>


                        <form name="reportForm" id="reportForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                            <div id="formMethod"></div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        {{-- Name --}}
                                        <div class="form-group">
                                            <label for="title">Missing Person Name</label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{ $missing_person->name }}"disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        {{-- Report Type --}}
                                        <label for="report_type">Type</label>
                                        <select class="custom-select" id="report_type" name="report_type" disabled>
                                            <option value="{{ $missing_person->report_type }}" selected>{{ $missing_person->report_type }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 mt-lg-0">
                                    <div class="col-sm-8">
                                        {{-- Unique Sign --}}
                                        <div class="form-group">
                                            <label for="unique_sign">Unique Sign</label>
                                            <input type="text" class="form-control" name="unique_sign" id="unique_sign" value="{{ $missing_person->unique_sign }}"disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        {{-- Age --}}
                                        <div class="form-group">
                                            <label for="age">Age</label>
                                            <input type="number" class="form-control" name="age" id="age" value="{{ $missing_person->age }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3 mt-lg-0">
                                    <div class="col-sm-8">
                                        {{-- Height --}}
                                        <div class="form-group">
                                            <label for="title">Height</label>
                                            <input type="number" step="any" class="form-control" name="height" id="height" value="{{ $missing_person->height }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        {{-- Height Unit --}}
                                        <label for="height_unit">Unit</label>
                                        <select class="custom-select" id="height_unit" name="height_unit" value="{{ $missing_person->height_unit}}" disabled>
                                            <option value="{{ $missing_person->height_unit }}" selected>{{ $missing_person->height_unit }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 mt-lg-0">
                                    <div class="col-sm-8">
                                        {{-- Weight --}}
                                        <div class="form-group">
                                            <label for="weight">Weight</label>
                                            <input type="number" step="any" class="form-control" name="weight" id="weight" value="{{ $missing_person->weight }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        {{-- Weight Unit --}}
                                        <label for="weight_unit">Unit</label>
                                        <select class="custom-select" id="weight_unit" name="weight_unit" value="{{ $missing_person->weight_unit}}" disabled>
                                            <option value="{{ $missing_person->weight_unit }}" selected>{{ $missing_person->weight_unit }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 mt-lg-0">
                                    <div class="col-sm-6">
                                        {{-- Eye Color --}}
                                        <div class="form-group">
                                            <label for="eyes">Eye Color</label>
                                            <input type="text" class="form-control" name="eyes" id="eyes" value="{{ $missing_person->eyes }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        {{-- Hair Color --}}
                                        <div class="form-group">
                                            <label for="hair">Hair Color</label>
                                            <input type="text" class="form-control" name="hair" id="hair" value="{{ $missing_person->hair }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                {{-- Contact Information --}}
                                <div class="form-group">
                                    <label for="contact_information">Contact Information</label>
                                    <textarea class="form-control" name="contact_information" id="contact_information" rows="2" disabled>{{ $missing_person->contact_information }}</textarea>
                                </div>

                                {{-- Last seen --}}
                                <div class="form-group">
                                    <label for="last_seen">Last seen</label>
                                    <textarea class="form-control" name="last_seen" id="last_seen" rows="1" disabled>{{ $missing_person->last_seen }}</textarea>
                                </div>

                                {{-- Important Information --}}
                                <div class="form-group">
                                    <label for="important_information">Important Information</label>
                                    <textarea class="form-control" name="important_information" id="important_information" rows="5" disabled>{{ $missing_person->important_information }}</textarea>
                                </div>

                                <div class="editContainer" hidden>
                                    {{-- Missing Person Picture --}}
                                    <div class="row mt-3 mt-lg-0">
                                        <div class="col-sm-8">
                                        {{-- Picture Upload --}}
                                        <div class="form-group">
                                            <label>Select picture (JPG/PNG)</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="picture" name="picture">
                                                <label class="custom-file-label" for="picture">Choose file</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4" id="currentPictureDiv">
                                        <small class="form-text text-muted">Current Picture</small>
                                        <img id="imgCurrentPicture" style="height:100px; max-height: 100px; max-width:100px; width: 100px;" src="" class="rounded" alt="">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btnFormSubmit">
                                    <i class="btnFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                                    <span class="btnFormTxt"></span>
                                </button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow mt-2 mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">People who comment on this announcement <span id="commentsCount">({{ $missing_person->comments->count() }})</span></h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTableComment" height="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Comment</th>
                                        <th>Commented_at</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Comment</th>
                                        <th>Commented_at</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($missing_person->comments as $comment)
                                        <tr>
                                            <td>
                                                 <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                                src="{{ isset($comment->user->file_path) ? asset('storage/'.$comment->user->file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$comment->user->getFullNameAttribute()}} image">
                                            </td>

                                            <td>{{$comment->user->getFullNameAttribute()}} #{{ $comment->user->id }}</td>
                                            <td>{{$comment->user->user_role->role }}</td>
                                            <td>{{$comment->user->email }}</td>
                                            <td>{{$comment->body}}</td>
                                            <td> {{ $comment->created_at }}
                                            </td>
                                        </tr>
                                    @empty
                                        <p>No likes added</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
