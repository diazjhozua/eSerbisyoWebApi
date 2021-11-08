@extends('layouts.report')

{{-- Title Page --}}
@section('title', 'Announcement Report')

@section('content')
    <div class="row">
        <div class="col-xs-6">
            <h4>Announcement Publish Report</h4>
            <address>
                <span><strong>Generated By: </strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} (#{{ Auth::id() }})</span><br>
                <span><strong>Position: </strong>{{ Auth::user()->user_role->role }} </span><br>
                <span><strong>Date Generated: </strong>{{ date('Y-m-d H:i:s') }}</span><br>
            </address>
        </div>

        <div class="col-xs-5">
            <h4 class="text-center"><strong>Timeframe</strong></h4>
            <table style="width: 100%">
                <tbody>
                    <tr>
                        <th>Date Start: </th>
                        <td class="text-right">{{ $request->date_start }}</td>
                    </tr>

                    <tr>
                        <th>Date End: </th>
                        <td class="text-right">{{ $request->date_end }}</td>
                    </tr>
                    <tr>
                        <th>Sort By: </th>
                        <td class="text-right">{{ str_replace(array("announcements."),"",$request->sort_column ); }} ({{{ $request->sort_option }}})</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-bottom: 0px">&nbsp;</div>

            <table style="width: 100%; margin-bottom: 20px">
                <tbody>
                    <tr class="well" style="padding: 5px">
                        <th style="padding: 5px"><div> Announcement Published: </div></th>
                        <td style="padding: 5px" class="text-right"><strong> {{ $announcements->count() }} </strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <table class="table">
        <thead style="background: #F5F5F5;">
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Title</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($announcements as $announcement)
                <tr>
                    <td>{{ $announcement->id }}</td>
                    @if (isset($announcement->custom_type))
                        <td>{{ $announcement->custom_type }}</td>
                    @else
                        <td>{{ $announcement->type->name }}</td>
                    @endif
                    <td>{{ $announcement->title }}</td>
                    <td>{{ $announcement->description}}</td>
                    <td>{{ $announcement->created_at }}</td>
                    <td>{{ $announcement->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-xs-6"></div>
        <div class="col-xs-5">
            <table style="width: 100%">
                <tbody>
                    <tr class="well" style="padding: 5px">
                        <th style="padding: 5px"><div> Announcement Published: </div></th>
                        <td style="padding: 5px" class="text-right"><strong> {{ $announcements->count() }} </strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection


