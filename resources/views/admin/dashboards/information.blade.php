@extends('layouts.admin')

@section('page-js')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $('#dashboard').addClass('active');

        $('#dashboardCollapse').addClass('active');
        $('#collapseDashboard').collapse();
        $('#informationDashboardItem').addClass('active');

        var userRegistrationCanvas = document.getElementById('userRegistrationChart');
        var userRegistrationChart = new Chart(userRegistrationCanvas, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Average User Registration',
                    data: [{{ $userChart[1]}}, {{ $userChart[2]}}, {{ $userChart[3]}},
                        {{ $userChart[4]}}, {{ $userChart[5]}}, {{ $userChart[6]}},
                        {{ $userChart[7]}}, {{ $userChart[8]}}, {{ $userChart[9]}},
                        {{ $userChart[10]}}, {{ $userChart[11]}}, {{ $userChart[12]}}],
                    borderColor: '#0275d8',
                    backgroundColor: '#0275d8',
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var feedbackCanvas = document.getElementById('feedbackChart');
        var feedbackChart = new Chart(feedbackCanvas, {
            type: 'doughnut',
            data: {
                labels: [
                    'Positive {{ $feedbacksData->this_month_positive_count.'%'}}' ,
                    'Neutral {{ $feedbacksData->this_month_neutral_count.'%'}}' ,
                    'Negative {{ $feedbacksData->this_month_negative_count.'%'}}' ,
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [{{ $feedbacksData->this_month_positive_count }}, {{ $feedbacksData->this_month_neutral_count }}, {{ $feedbacksData->this_month_negative_count }}],
                    backgroundColor: [
                    '#5cb85c',
                    '#5bc0de',
                    '#d9534f'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                },
            }
        });

        var projectCanvas = document.getElementById('projectChart');
        var projectChart = new Chart(projectCanvas, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Projects Implemented',
                    data: [{{ $projectChart[1]}}, {{ $projectChart[2]}}, {{ $projectChart[3]}},
                                    {{ $projectChart[4]}}, {{ $projectChart[5]}}, {{ $projectChart[6]}},
                                    {{ $projectChart[7]}}, {{ $projectChart[8]}}, {{ $projectChart[9]}},
                                    {{ $projectChart[10]}}, {{ $projectChart[11]}}, {{ $projectChart[12]}}],
                    borderColor: '#0275d8',
                    backgroundColor: '#0275d8',
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    xAxes: [{
                        barPercentage: 100
                    }]

                }
            }
        });


    </script>

@endsection

@section('page-css')

    <style>
        .graph{
            position: relative;
            height: 100%;
            width: 100% !important;
            }

        canvas {
            width: 100% !important;
        }
    </style>

@endsection

{{-- Title Page --}}
@section('title', 'Barangay Cupang - Information Dashboard')


@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Information Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Registered User (This month) Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <a href="{{ route('admin.users.index') }}">Registered User (This month)</a>
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersData->this_month_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Staff Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <a href="{{ route('admin.staffs.adminStaff') }}">Information Staff</a>
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersData->information_staff_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Blocked User Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                <a href="{{ route('admin.users.index') }}">Restricted User (Blocked)</a>
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersData->blocked_user_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-slash fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Requests Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <a href="{{ route('admin.users.index') }}">Verification Requests</a>
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $verificationCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="far fa-id-card fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Line Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">User Registration Overview</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area graph">
                        <canvas id="userRegistrationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('admin.feedbacks.index') }}">Feedbacks Summary (This month)</a> </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="graph">
                        <canvas id="feedbackChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Content Column -->
        <div class="col-lg-8 mb-4">

            <!-- Project Chart Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('admin.projects.index') }}">Projects Implemented within this year</a></h6>
                </div>
                <div class="card-body">
                        <div class="graph">
                        <canvas id="projectChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">

            <!-- Illustrations -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Illustrations (This month)</h6>
                </div>
                <div class="card-body">

                    {{-- Feedbacks Recieved --}}
                     <div class="card border-left-primary shadow h-100 py-2 mb-3">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> <a href="{{ route('admin.feedbacks.index') }}"> Feedbacks Recieved </a>
                                       </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $feedbacksData->this_month_total_feedbacks }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="far fa-comment-alt fa-2x text-black-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Announcement Posted --}}
                     <div class="card border-left-primary shadow h-100 py-2 mb-3">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <a href="{{ route('admin.announcements.index') }}">Announcement Posted</a>
                                      </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $announcementCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-bullhorn fa-2x text-black-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Project Posted --}}
                     <div class="card border-left-primary shadow h-100 py-2 mb-3">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <a href="{{ route('admin.projects.index') }}"> Project Posted </a>
                                       </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $thisMonthProjectCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-hard-hat fa-2x text-black-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

