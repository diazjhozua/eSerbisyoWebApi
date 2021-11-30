@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/information/projects/index.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Projects')

@section('content')

    {{-- Included Modals --}}
    @include('admin.information.projects.formModal')

    @section('reportRoute', route('admin.projects.report'))
    @include('admin.information.projects.reportSelectModal')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Projects
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
            class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" onclick="createProject()">
        Create
    </button>

    <div class="row">
        {{-- Project publish this day --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1" >
                                Project publish this day</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisDayCount">{{ $projectsData->this_day_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Project publish this month --}}
        <div class="col-sm mt-2">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" >
                                Project publish this month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthCount">{{ $projectsData->this_month_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Project publish this year Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1" >
                                Project publish this year</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisYearCount">{{ $projectsData->this_year_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p class="mt-3">These are the list of projects that are available for the residents to view.</p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="projectsCount">{{ $projects->count() }}</span>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Project Start</th>
                            <th>Project End</th>
                            <th>Location</th>
                            <th>PDF</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Project Start</th>
                            <th>Project End</th>
                            <th>Location</th>
                            <th>PDF</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td>{{ $project->id }}</td>
                                <td>{{ $project->name }}</td>
                                @if (isset($project->custom_type))
                                    <td>{{ $project->custom_type }}</td>
                                @else
                                    <td><a href="{{route('admin.project-types.show', $project->type_id)}}">{{ $project->type->name }}</a></td>
                                @endif
                                <td>{{ $project->description }}</td>
                                <td>{{ '₱'.number_format($project->cost, 2) }}</td>
                                <td>{{ $project->project_start}}</td>
                                <td>{{ $project->project_end}}</td>
                                <td>{{ $project->location}}</td>
                                <td><a href="{{route('admin.viewFiles', [ 'folderName' => 'projects', 'fileName' => $project->pdf_name])}}" target="_blank">{{ $project->pdf_name}}</a></td>
                                <td>{{ $project->updated_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-primary btn-sm" onclick="editProject({{ $project->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </li>
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-danger btn-sm" type="button" onclick="deleteProject({{ $project->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No projects yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
