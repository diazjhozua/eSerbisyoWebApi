@extends('layouts.information')

@section('page-css')
    <!-- Custom styles for type template-->
    <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection


@section('page-js')
    <!-- Page level plugins -->
    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('admin/js/demo/datatables-demo.js')}}"></script>

    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/announcements/show.js')}}"></script>

@endsection

{{-- Title Page --}}
@section('title', $announcement->title)


@section('content')

    {{-- Included Modals --}}
    @include('admin.announcements.form')
    @include('admin.announcements.picture-form')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')

        <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button> Title:<span id="announcementTitle"> {{ $announcement->title }}</span>
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>

            {{-- Edit --}}
            <button class="btn btn-primary btn-sm" onclick="editAnnouncement({{ $announcement->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            {{-- Delete --}}
            <button class="btn btn-danger btn-sm" type="button" onclick="deleteAnnouncement({{ $announcement->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                <i class="fas fa-trash-alt"></i>
            </button>
        </h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
    </div>

    <style>
        .announcementCardContainer{
            width: 100%;
        }
        .announcementCardContainer .heading{
            font-size: 20px;
            color: black
        }

        .announcementCardContainer .description{
            padding: 5px;
            text-align: justify;
            font-size: 15px;
            color: black;
            width:100%;
            height: 400px;
            overflow-y: scroll;
        }
        .announcementCardContainer .date{
            text-align: justify;
            font-size: 10px;
            color: black;
        }
    </style>

    <div class="announcementCardContainer">
        <div class="row">
            <div class="col mt-3">
                {{-- Announcement Information --}}
                <p class="heading">Announcement Type: <span id="announcementTypes">{{ $announcement->type->name }}</span></p>
                <p class="heading">Description:<p>
                <p class="description" id="announcementDescription">{{ $announcement->description }}</p>
                <p class="date"> Created At: {{$announcement->created_at}} |  Updated At: <span id="announcementUpdatedAt">{{ $announcement->updated_at}}</span></p>
            </div>
            <div class="col">
                {{-- Announcement Card --}}
                    <!-- DataTales Example -->
                <div class="card shadow mt-2 mb-4">
                    <div class="card-header d-flex justify-content-between align-items:center py-3">
                        <h6 class="font-weight-bold text-primary">Announcement Pictures (Total: <span id="announcementPicturesCount">{{ $announcement->announcement_pictures_count}}</span> pictures) </h6>
                            <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" onclick="addPicture({{$announcement->id}})" data-toggle="tooltip" data-placement="top" title="Add Pictures">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTablePicture" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Picture</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Picture</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($announcement->announcement_pictures as $picture)
                                        <tr>
                                            <td>
                                                <img style="height:100px; max-height: 100px; max-width:100px; width: 100px;" src="{{ asset('storage/'.$picture->file_path) }}" class="rounded" alt="{{$picture->picture_name}} image">
                                            </td>

                                            <td>
                                                <ul class="list-inline m-0">
                                                    <li class="list-inline-item mb-1">
                                                        <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{route('admin.viewFiles', [ 'folderName' => 'announcements', 'fileName' => $picture->picture_name])}}"  target="_blank">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item mb-1">
                                                        <button class="btn btn-primary btn-sm" onclick="editPicture({{ $picture->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </li>
                                                    <li class="list-inline-item mb-1">
                                                        <button class="btn btn-danger btn-sm" type="button" onclick="deletePicture({{ $picture->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <p>No pictures added</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="announcmentInfoContainer">
        <div class="row">
            <div class="col">
                {{-- Likes Count Card --}}
                    <div class="card border-left-info shadow py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Likes</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $announcement->likes_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-thumbs-up fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- Likes Table --}}
                <div class="card shadow mt-2 mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">People who like this announcement</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTableLike" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Like_at</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Like_at</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($announcement->likes as $like)
                                        <tr>
                                            <td>
                                                <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                                src="{{ isset($like->user->file_path) ? asset('storage/'.$like->user->file_path)
                                                :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}"
                                                class="rounded" alt="{{$like->user->getFullNameAttribute()}} image">

                                            </td>

                                            <td>{{$like->user->getFullNameAttribute()}}</td>

                                            <td> {{ $like->created_at }}
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
            <div class="col">
                {{-- Comment Count Card --}}
                <div class="card border-left-info shadow py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1" id="thisYearCount">
                                    Total Comments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $announcement->comments_count }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Comments Table --}}
                <div class="card shadow mt-2 mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">People who comment on this announcement</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTableComment" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Comment</th>
                                        <th>Commented_at</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Comment</th>
                                        <th>Commented_at</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($announcement->comments as $comment)
                                        <tr>
                                            <td>
                                                 <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                                src="{{ isset($comment->user->file_path) ? asset('storage/'.$comment->user->file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$comment->user->getFullNameAttribute()}} image">
                                            </td>

                                            <td>{{$comment->user->getFullNameAttribute()}}</td>
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
