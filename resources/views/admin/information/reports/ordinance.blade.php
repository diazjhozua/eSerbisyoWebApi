@extends('layouts.report')

{{-- Title Page --}}
@section('title', 'Ordinance Report')

@section('content')
    <div class="row">
        <div class="col-xs-6">
            <h4>Ordinance Publish Report</h4>
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
                        <td class="text-right">{{ str_replace(array("ordinances."),"",$request->sort_column ); }} ({{{ $request->sort_option }}})</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-bottom: 0px">&nbsp;</div>

            <table style="width: 100%; margin-bottom: 20px">
                <tbody>
                    <tr class="well" style="padding: 5px">
                        <th style="padding: 5px"><div> Ordinance Published: </div></th>
                        <td style="padding: 5px" class="text-right"><strong> {{ $ordinances->count() }} </strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <table class="tableContent">
        <thead style="background: #F5F5F5;">
            <tr>
                <th>ID</th>
                <th>Ordinance Type</th>
                <th>Ordinance No</th>
                <th>Title</th>
                <th>Date Approved</th>
                <th>PDF Name</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($ordinances as $ordinance)
                <tr>
                    <td>{{ $ordinance->id }}</td>
                    @if (isset($ordinance->custom_type))
                        <td>{{ $ordinance->custom_type }}</td>
                    @else
                        <td>{{ $ordinance->type->name }}</td>
                    @endif
                    <td>{{ $ordinance->ordinance_no }}</td>
                    <td>{{ $ordinance->title}}</td>
                    <td>{{ $ordinance->date_approved }}</td>
                    <td>{{ $ordinance->pdf_name }}</td>
                    <td>{{ $ordinance->created_at }}</td>
                    <td>{{ $ordinance->updated_at }}</td>
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
                        <th style="padding: 5px"><div> Ordinance Published: </div></th>
                        <td style="padding: 5px" class="text-right"><strong> {{ $ordinances->count() }} </strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection


