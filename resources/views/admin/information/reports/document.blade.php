@extends('layouts.report')

{{-- Title Page --}}
@section('title', 'Document Report')

@section('content')
    <div class="row">
        <div class="col-xs-6">
            <h4>Document Publish Report</h4>
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
                        <td class="text-right">{{ str_replace(array("documents."),"",$request->sort_column ); }} ({{{ $request->sort_option }}})</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-bottom: 0px">&nbsp;</div>

            <table style="width: 100%; margin-bottom: 20px">
                <tbody>
                    <tr class="well" style="padding: 5px">
                        <th style="padding: 5px"><div> Document Published: </div></th>
                        <td style="padding: 5px" class="text-right"><strong> {{ $documents->count() }} </strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <table class="tableContent">
        <thead style="background: #F5F5F5;">
            <tr>
                <th>ID</th>
                <th>Document Type</th>
                <th>Description</th>
                <th>Year</th>
                <th>PDF Name</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($documents as $document)
                <tr>
                    <td>{{ $document->id }}</td>
                    @if (isset($document->custom_type))
                        <td>{{ $document->custom_type }}</td>
                    @else
                        <td>{{ $document->type->name }}</td>
                    @endif
                    <td>{{ $document->description }}</td>
                    <td>{{ $document->year}}</td>
                    <td>{{ $document->pdf_name }}</td>
                    <td>{{ $document->created_at }}</td>
                    <td>{{ $document->updated_at }}</td>
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
                        <th style="padding: 5px"><div> Document Published: </div></th>
                        <td style="padding: 5px" class="text-right"><strong> {{ $documents->count() }} </strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection


