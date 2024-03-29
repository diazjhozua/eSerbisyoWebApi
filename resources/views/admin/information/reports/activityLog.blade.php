@extends('layouts.report')

{{-- Title Page --}}
@section('title', 'Activity Log Report')

@section('content')

    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-xs-6">
                <h4>Activity Log Report</h4>
                <address>
                    <span><strong>Generated By: </strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} (#{{ Auth::id() }})</span><br>
                    <span><strong>Position: </strong>{{ Auth::user()->user_role->role }} </span><br>
                    <span><strong>Date Generated: </strong>{{ date('Y-m-d H:i:s') }}</span><br>
                    <hr>
                    <h5 class="text-center"><strong>Overall Statistics</strong></h5>
                    <span><strong>Created: </strong>{{ $logsData->created_count }}</span><br>
                    <span><strong>Updated: </strong>{{ $logsData->updated_count }}</span><br>
                    <span><strong>Deleted: </strong>{{ $logsData->deleted_count }}</span><br>
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
                            <td class="text-right">{{ str_replace(array("ordinances."),"",$request->sort_column ); }} ({{{ $request->sort_option }}})</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 0px">&nbsp;</div>

                <table style="width: 100%; margin-bottom: 20px">
                    <tbody>

                        <tr class="well" style="padding: 5px">
                            <th style="padding: 5px"><div> Audit Logs Count: </div></th>
                            <td style="padding: 5px" class="text-right"><strong> {{ $logs->count() }} </strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <table class="table">
        <thead style="background: #F5F5F5;">
            <tr>
                <th>Model</th>
                <th>Events</th>
                <th>Executed by</th>
                <th>Position</th>
                <th>Model ID</th>
                <th>Properties</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ str_replace('App\\Models\\', "",$log->subject_type);  }}</td>
                    <td>
                        @switch($log->event)
                            @case('created')
                                {{ strtoupper($log->event).' - '.$log->description }}
                                @break
                            @case('updated')
                                {{ strtoupper($log->event).' - '.$log->description }}
                                @break
                            @case('deleted')
                                {{ strtoupper($log->event).' - '.$log->description }}
                                @break
                        @endswitch
                    </td>

                    <td>{{ $log->causer->first_name .' '. $log->causer->last_name }}</td>
                    <td>
                        {{ $log->causer->user_role->role }}
                    </td>

                    <td>{{ $log->subject_id }}</td>
                    <td>
                        @if (isset($log->properties['attributes']))
                            <div class="m-1">
                                <ul class="list-unstyled">
                                    <li>Attributes:
                                        <ul>
                                            @foreach ($log->properties['attributes'] as $key => $node)
                                                <li>{{ $key }}: {{ $node }}</li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        @endif

                        @if (isset($log->properties['old']))
                            <div class="m-1">
                                <ul class="list-unstyled">
                                    <li>Old :
                                        <ul>
                                            @foreach ($log->properties['old'] as $key => $node)
                                                <li>{{ $key }}: {{ $node }}</li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        @endif

                    </td>
                    <td>{{ $log->created_at }}</td>
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
                        <th style="padding: 5px"><div> Audit Logs Count: </div></th>
                        <td style="padding: 5px" class="text-right"><strong> {{ $logs->count() }} </strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection


