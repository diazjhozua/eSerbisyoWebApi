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
                <p class="font-weight-bold">Date Start:</p>
                <p class="font-weight-bold">Date End:</p>
                <p class="font-weight-bold">Sort by:</p>
                <p class="font-weight-bold">Ordinance Type: </p> 
            </div>
            <div class="column" style="font-size: 18px">
                <p class="text-right"> {{$date_start}} </p>
                <p class="text-right"> {{ $date_end  }} </p>
                <p class="text-right">  {{ str_replace(array("ordinances."),"",$sort_column ); }} ({{{ $sort_option }}}) </p>
                <p class="text-right"> {{ $modelName }} </p>    
                
                
            </div>
            <hr style="width:100%; background-color:gray; height: 2px;" >
        </div>
    </div>
    
    <div class="regiz">
        <div class="regi">
            <pre> <b>Ordinance count:</b> {{ $ordinances->count() }}</pre>
        </div>
    </div>

    <table class="tableContent mb-4">
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
                    @if ($ordinance->custom_type)
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

    @endsection