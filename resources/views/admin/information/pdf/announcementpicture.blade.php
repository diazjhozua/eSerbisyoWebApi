@extends('layouts.newReport')

@section('title', $title)

@section('content')



<div class = "rep">
        <br>
        <br>
        <b> {{ $title }} </b>
        </h6>
        <br>
        <b>Generated By:</b><i>: {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} (#{{ Auth::id() }})</i>
        <br>
        <b>Position:</b> <i>{{ Auth::user()->user_role->role }} </i>
        <br>
        <b>Date Generated:</b><i>{{ date('Y-m-d H:i:s') }}</i>
    </div>

    <hr style="width:45%; margin-left: 0.1rem; background-color:gray; height: 2px;" >
    <div class = "timef">
        <h6>
        <b>Timeframe</b>
    </div>

    <div class ="dits">
        <div class="row p-4">
            <div class="column" style="font-size: 18px">


                <p class="font-weight-bold">Announcement Type: </p>
            </div>
            <div class="column" style="font-size: 18px">


                <p class="text-right"> {{ $modelName }} </p>


            </div>


            <hr style="width:100%; background-color:gray; height: 2px;" >
        </div>
    </div>

    <div class="container mb-3">
    <h4 class="text-center">Overall Statistics</h4>
        <hr/>
        <div class="row">
            <div class="col-sm">
                <div class="row">
                    <div class="col-sm-6">
                     Total Images:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $announcement->announcement_pictures_count}}</div>
                    </div>

                    <div class="col-sm-6">
                     Total Likes:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $announcement->likes_count}}</div>
                    </div>
                    <div class="col-sm-6">
                    Total Comments:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $announcement->comments_count}}</div>
                    </div>
                </div>
                <br>
            </div>


    <div class="container mb-6">
    <h4 class="text-center">Details</h4>
        <hr/>
        <div class="row">
            <div class="col-sm">
                <div class="row">
                    <div class="col-sm-6">
                     ID:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $announcement->id }}</div>
                    </div>

                    <div class="col-sm-6">
                     Title:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $announcement->title }}</div>
                    </div>
                    <div class="col-sm-6">
                   Type:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">@if ($announcement->type_id)
            {{ $announcement->type->name }} (#{{ $announcement->type_id }})
        @else
          >{{ $announcement->custom_type }}
        @endif
                    </div>



                    </div>
                </div>
            </div>

            <div class="container mb-6">

        <hr/>
        <div class="row">
            <div class="col-sm">
                <div class="row">
                    <div class="col-sm-6">
                    Description:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{$announcement->description}}</div>
                    </div>

                    <div class="col-sm-6">
                    Created At:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $announcement->created_at }}</div>
                    </div>

                    <div class="col-sm-6">
                    Updated At:
                    </div>

                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $announcement->updated_at }}

                    </div>



                    </div>
                </div>
            </div>
            </div>
      <br>
    <h4 class="text-center"><strong>Picture List</strong></h4>
    <table class="tableContent mb-4">
        <thead style="background: #F5F5F5;">
            <tr>
                    <th>ID</th>
                    <th>Picture</th>
                    <th>Created At</th>
                    <th>Updated At</th>

            </tr>
        </thead>
        <tbody>
        @forelse ($announcement->announcement_pictures as $picture)
                <tr>
                <td>{{ $picture->id }}</td>
                <td>
                    <br>
                    <p class="text-center">
                        <img src="{{ $picture->file_path }}"
                        width="110px" height="100px"
                        alt="logo">
                    </p>
                    </td>
                <td>{{ $picture->created_at }}</td>
                <td>{{ $announcement->updated_at }}</td>
</tr>
@empty
                <P>No pictures</P>
            @endforelse
            </tbody>
    </table>
    <h4 class="text-center"><strong>Comment List</strong></h4>
    <table class="tableContent mb-4">
        <thead style="background: #F5F5F5;">
            <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Comment</th>
                    <th>Commented At</th>

            </tr>
        </thead>
        <tbody>
        @forelse ($announcement->comments as $comment)
                <tr>
                <td>{{ $comment->id }}</td>
                        <td>{{$comment->user->getFullNameAttribute()}} (#{{ $comment->user->id }})</td>
                        <td>{{$comment->body}}</td>
                        <td>{{ $comment->created_at }}</td>
                    </tr>
                @empty
                    <p>No comments</p>
                @endforelse
            </tbody>
    </table>

    <h4 class="text-center"><strong>Like List</strong></h4>
    <table class="tableContent mb-4">
        <thead style="background: #F5F5F5;">
            <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>

            </tr>
        </thead>
        <tbody>
        @forelse ($announcement->likes as $like)
                    <tr>
                        <td>{{ $like->id }}</td>
                        <td>{{$like->user->getFullNameAttribute()}} (#{{ $like->user->id }})</td>
                        <td>{{ $like->created_at }}</td>
                    </tr>
                @empty
                    <p>No likes</p>
                @endforelse
            </tbody>
    </table>


@endsection
