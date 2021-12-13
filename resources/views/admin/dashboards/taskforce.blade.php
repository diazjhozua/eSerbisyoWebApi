@extends('layouts.admin')

@section('page-js')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $('#dashboard').addClass('active')

        var reportSubmittedCanvas= document.getElementById('reportSubmittedChart');
        var userReportChart = new Chart(reportSubmittedCanvas, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Average User Report',
                    data: [{{ $userReport[1]}}, {{ $userReport[2]}}, {{ $userReport[3]}},
                        {{ $userReport[4]}}, {{ $userReport[5]}}, {{ $userReport[6]}},
                        {{ $userReport[7]}}, {{ $userReport[8]}}, {{ $userReport[9]}},
                        {{ $userReport[10]}}, {{ $userReport[11]}}, {{ $userReport[12]}}],
                    borderColor: '#E7625F',
                    backgroundColor: '#C85250',
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

        var reportTypeCanvas = document.getElementById('reportTypeChart');
        var reportTypeChart = new Chart(reportTypeCanvas, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach ($reportTypes as $reportType)
                        "{{ $reportType->name }}: {{$reportType->reports_count}} reports",
                    @endforeach
                ],
                datasets: [{
                    label: 'Reports',
                    data: [
                        @foreach ($reportTypes as $reportType)
                            {{$reportType->reports_count}},
                        @endforeach
                    ],
                    backgroundColor: [
                    '#05445E',
                    '#189AB4',
                    '#75E6DA',
                    '#D4F1F4',
                    '#B68D40',
                    '#F4EBD0',

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



        var complaintCanvas = document.getElementById('complaintChart');
        var complaintChart = new Chart(complaintCanvas, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Complaints Submitted',
                    data: [{{ $complaintChart[1]}}, {{ $complaintChart[2]}}, {{ $complaintChart[3]}},
                                    {{ $complaintChart[4]}}, {{ $complaintChart[5]}}, {{ $complaintChart[6]}},
                                    {{ $complaintChart[7]}}, {{ $complaintChart[8]}}, {{ $complaintChart[9]}},
                                    {{ $complaintChart[10]}}, {{ $complaintChart[11]}}, {{ $complaintChart[12]}}],
                    borderColor: '#E7625F',
                    backgroundColor: '#C85250',
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
@section('title', 'Barangay Cupang - Dashboards')


@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row mb-4">
        <!-- Pending Missing Item Case Card -->
        <div class="col-sm mt-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <a href="{{ route('admin.missing-items.index') }}">Pending Missing Item</a>
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $missingItemPendingCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('assets/img/missingItem.png') }}" alt="Missing-Item">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Missing Person Case Card -->
        <div class="col-sm mt-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <a href="{{ route('admin.missing-persons.index') }}">Pending Missing Person Item</a>
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $missingPersonPendingCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('assets/img/missingPerson.png') }}" alt="Missing-Item">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taskforce Staff Card -->
        <div class="col-sm mt-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <a href="{{ route('admin.staffs.adminStaff') }}">Taskforce Staff</a>
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $userTaskforceStaffCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('assets/img/staff.png') }}" alt="Staff">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Report Card -->
        <div class="col-sm mt-2">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                <a href="{{ route('admin.reports.index') }}">Report (This Day)</a>
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $reportThisDayCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('assets/img/report.png') }}" alt="report">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complaint Card -->
        <div class="col-sm mt-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <a href="{{ route('admin.complaints.index') }}">Complaint (This Month)</a>
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $complaintThisMonthCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('assets/img/complaint.png') }}" alt="Complaint">
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
                    <h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('admin.reports.index') }}">Report Submitted Overview</a></h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area graph">
                        <canvas id="reportSubmittedChart"></canvas>
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
                    <h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('admin.report-types.index') }}">Reports Summary (This month)</a> </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="graph">
                        <canvas id="reportTypeChart"></canvas>
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
                    <h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('admin.complaints.index') }}">Complaints within this year</a></h6>
                </div>
                <div class="card-body">
                        <div class="graph">
                        <canvas id="complaintChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">

            <!-- Illustrations -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Frequent Complaint (This month)</h6>
                </div>
                <div class="card-body">

                    {{-- Feedbacks Recieved --}}
                    @forelse ($complaintTypes as $complaintType)
                            <div class="card border-left-primary shadow h-100 py-2 mb-3">
                                <div class="card-body mb-2">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> <a href="{{ route('admin.complaint-types.show', $complaintType->id) }}"> {{ $complaintType->name }} </a>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{-- {{ $feedbacksData->this_month_total_feedbacks }} --}}
                                                {{ $complaintType->complaints_count }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty

                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

