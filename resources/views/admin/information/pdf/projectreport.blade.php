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
            </div>
            <div class="column" style="font-size: 18px">
                <p class="text-right"> {{$date_start}} </p>
                <p class="text-right"> {{ $date_end  }} </p>
                <p class="text-right">  {{ str_replace(array("projects."),"",$sort_column ); }} ({{{ $sort_option }}}) </p>
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
                     Project publish this day:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $projectsData->this_day_count }}</div>
                    </div>

                    <div class="col-sm-6">
                    Project publish this month:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $projectsData->this_month_count }}</div>
                    </div>
                    <div class="col-sm-6">
                     Project publish this year:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $projectsData->this_year_count }}</div>
                    </div>
                </div>
            </div>
</div>
    <div class="regiz">
        <div class="regi">
            <pre> <b>{{ $modelName }} count:</b> {{ $projects->count() }}</pre>
        </div>
    </div>

    
    <table class="tableContent mb-4">
        <thead style="background: #F5F5F5;">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Project Type</th>
                <th>Description</th>
                <th>Cost</th>
                <th>Project Start</th>
                <th>Project End</th>
                <th>Location</th>
                <th>PDF Name</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($projects as $project)
                <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->name }}</td>
                @if ($project->custom_type)
                        <td>{{ $project->custom_type }}</td>
                    @else
                        <td>{{ $project->type->name }}</td>
                    @endif
                <td>{{ $project->description}}</td>
                <td>₱{{ number_format($project->cost, 2) }}</td>
                <td>{{ $project->project_start}}</td>
                <td>{{ $project->project_end}}</td>
                <td>{{ $project->location}}</td>
                <td>{{ $project->pdf_name }}</td>
                <td>{{ $project->created_at }}</td>
                <td>{{ $project->updated_at }}</td>
            @endforeach
        </tbody>
    </table>

@endsection