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
            <div class="column">
                <p class="font-weight-bold">Date Start:</p>
                <p class="font-weight-bold">Date End:</p>
                <p class="font-weight-bold">Sort by:</p>
            </div>
            <div class="column">
                <p class="text-right"> {{$date_start}} </p>
                <p class="text-right"> {{ $date_end  }} </p>
                <p class="text-right">  {{ str_replace(array("types."),"",$sort_column ); }} ({{{ $sort_option }}}) </p>
            </div>
            <hr style="width:100%; background-color:gray; height: 2px;" >

            @if ($status_option == 'all')
                <div class="column">
                    <p class="font-weight-bold">Pending Count:</p>
                    <p class="font-weight-bold">Denied Count:</p>
                    <p class="font-weight-bold">Resolved Count:</p>
                    <p class="font-weight-bold">Approved Count:</p>
                </div>
                <div class="column">
                    <p class="text-right"> {{ $complaintsData->pending_count }} </p>
                    <p class="text-right"> {{ $complaintsData->denied_count }} </p>
                    <p class="text-right"> {{ $complaintsData->resolved_count }} </p>
                    <p class="text-right"> {{ $complaintsData->approved_count }} </p>
                </div>
            @endif


        </div>
    </div>

    <div class="regiz">
        <div class="regi">
            <pre> <b>{{ $modelName }} Submitted:</b> {{ $complaints->count() }}</pre>
        </div>


    </div>

    <table class="tableContent mb-4">
        <thead style="background: #F5F5F5;">
            <tr>
                <th >ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Reason</th>
                <th>Action</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Filed at</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($complaints as $complaint)
                <tr>
                    <td scope="row">{{ $complaint->id }}</td>
                    <td>{{ $complaint->user->getFullNameAttribute() }} ({{$complaint->user->id}})</td>
                    @if ($complaint->type_id != 0)
                        <td>{{ $complaint->type->name }}</td>
                    @else
                        <td>Others/Deleted- {{ $complaint->custom_type }}</td>
                    @endif
                    <td>{{ $complaint->reason }}</td>
                    <td>{{ $complaint->action }}</td>
                    <td>
                        <p class="font-weight-bold">Complainants:</p>

                        @foreach ($complaint->complainants as $complainant)
                            <p>- {{$complainant->name}}</p>
                        @endforeach

                        <p class="font-weight-bold">Defendants:</p>

                        @foreach ($complaint->defendants as $defendant)
                            <p>- {{$defendant->name}}</p>
                        @endforeach

                        <p class="p-0"><span class="font-weight-bold">Email:</span><br>{{ $complaint->email}}</p>
                        <p class="p-0"><span class="font-weight-bold">Phone No:</span><br>{{ $complaint->phone_no}}</p>
                    </td>
                    <td>
                        <p class="font-weight-bold">Status:</p>
                        <p>{{ $complaint->status }}</p>

                        @if ($complaint->admin_message != null)
                            <p class="font-weight-bold">Admin Message:</p>
                            <p>{{ $complaint->admin_message }}</p>
                        @endif


                    </td>
                    <td>{{ $complaint->created_at }}</td>


                </tr>
            @endforeach

        </tbody>
    </table>

</div>


    <div class="regiz">
        <div class="regi">
            <pre> <b>{{ $modelName }} Submitted:</b> {{ $complaints->count() }}</pre>
        </div>
    </div>

@endsection