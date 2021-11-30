@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/taskforce/missing-items/show.js')}}"></script>

@endsection

{{-- Title Page --}}
@section('title', $missing_item->name . ' Missing Report')

@section('content')

    {{-- Change Status Form Modal --}}
    @include('admin.taskforce.inc.changeStatusFormModal')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i>
            </button> <span id="currentReportType">{{ $missing_item->report_type }}</span>:<span id="currentName"> {{ $missing_item->item }}
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

            {{-- View Credentials --}}
            <a id="currentCredential" href="{{ route('admin.viewFiles', [ 'folderName' => 'credentials', 'fileName' => $missing_item->credential_name ]) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" target="_blank">
                View Credentials
            </a>

        </h1>

        <a href="" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" target="_blank">
            <i class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
    </div>


    <div class="information">
        <div class="row">
            <div class="col-md-6 col-sm-6 mt-3">
                {{-- Missing Report Information --}}
                <div class="card text-black mb-3">
                    <img class="mt-2 mx-auto d-block" style="height:200px; max-height: 200px; max-width:200px; width: 200px;" id="currentImage" src="{{ asset('storage/'.$missing_item->file_path) }}" alt="Card image cap">
                    <div class="card-body">

                        <p class="card-text"><strong>Submitted By: {{ $missing_item->user->getFullNameAttribute() }} #{{ $missing_item->user->id }} - ({{ $missing_item->user->user_role->role }}) </strong></p>
                        <p class="card-text"><strong>Contact User: <span id="currentContactName">{{ $missing_item->contact->getFullNameAttribute() }}</span> #<span id="currentContactID">{{ $missing_item->contact->id }}</span> - (<span id="currentContactRole">{{ $missing_item->contact->user_role->role }}</span>) </strong></p>
                        <p class="card-text"><strong>Latest Admin Message: </strong></p>
                        <p class="card-text"><small class="text-black" id="currentAdminMessage">{{ $missing_item->admin_message }}</small></p>
                        <p class="card-text"><small class="text-muted">Submitted at: <span id="currentCreatedAt"> {{ $missing_item->created_at }}</span> || Updated at: <span id="currentUpdatedAt">{{ $missing_item->updated_at }}</span></small></p>

                        <div class="form-group">
                            <label>Status</label>
                            @if ($missing_item->status == 'Pending')
                                <div id="currentStatus" class="p-2 bg-info text-white rounded-pill text-center">
                            @elseif ($missing_item->status == 'Approved')
                                <div id="currentStatus" class="p-2 bg-dark text-white rounded-pill text-center">
                            @elseif ($missing_item->status == 'Resolved')
                                <div id="currentStatus" class="p-2 bg-success text-white rounded-pill text-center">
                            @elseif ($missing_item->status == 'Denied')
                                <div id="currentStatus" class="p-2 bg-danger text-white rounded-pill text-center">
                            @endif
                                {{ $missing_item->status }}
                            </div>
                        </div>


                        <form name="reportForm" id="reportForm" action="" method="POST" enctype="multipart/form-data" novalidate>

                            <div id="formMethod"></div>

                            <div class="row">
                                <div class="col-sm-8">
                                    {{-- Name --}}
                                    <div class="form-group">
                                        <label for="item">Missing Item Name</label>
                                        <input type="text" class="form-control" name="item" id="item" value="{{ $missing_item->item }}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    {{-- Report Type --}}
                                    <label for="report_type">Type</label>
                                    <select class="custom-select" id="report_type" name="report_type" disabled>
                                        <option value="{{ $missing_item->report_type }}" selected>{{ $missing_item->report_type }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="contact_user_id">Contact (USER ID) - (The specified user_id will receive notification within the application)</label>
                                <input type="number" class="form-control" name="contact_user_id" id="contact_user_id" value="{{ $missing_item->contact_user_id }}" disabled>
                            </div>

                            {{-- Contact Information --}}
                            <div class="row mt-3 mt-lg-0">
                                <div class="col-sm-6">
                                    {{-- Email --}}
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" name="email" id="email" value="{{ $missing_item->email }}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    {{-- Phone No --}}
                                    <div class="form-group">
                                        <label for="phone_no">Phone No</label>
                                        <input type="text" class="form-control" name="phone_no" id="phone_no" value="{{ $missing_item->phone_no }}" disabled>
                                    </div>
                                </div>
                            </div>

                            {{-- Last seen --}}
                            <div class="form-group">
                                <label for="last_seen">Last seen</label>
                                <textarea class="form-control" name="last_seen" id="last_seen" rows="1" disabled>{{ $missing_item->last_seen }}</textarea>
                            </div>

                            {{-- Description --}}
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="5" disabled>{{ $missing_item->description }}</textarea>
                            </div>

                            <div id="editContainer" style="display: none;">
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

                                <div class="text-right mt-2">
                                    <button type="button" class="btn btn-secondary" onClick="cancelEditing()" data-dismiss="modal">Cancel</button>

                                    <button type="submit" class="btn btn-primary btnFormSubmit">
                                        <i class="btnFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                                        <span class="btnFormTxt"></span>
                                    </button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6">
                <div class="card shadow mt-2 mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">People who comment on this report <span id="commentsCount">({{ $missing_item->comments->count() }})</span></h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTableComment" width="100%" cellspacing="0">
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
                                    @forelse ($missing_item->comments as $comment)
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
                                        <p>No comments added</p>
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
