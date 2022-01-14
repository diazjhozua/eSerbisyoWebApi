@extends('layouts.admin')


@section('page-js')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $('#dashboard').addClass('active')

        $('#dashboardCollapse').addClass('active');
        $('#collapseDashboard').collapse();
        $('#certificateDashboardItem').addClass('active');

        var earningsOverviewCanvas= document.getElementById('earningsOverviewChart');
        var earningsOverviewChart = new Chart(earningsOverviewCanvas, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Earnings Overview Report',
                    data: [{{ $earningsOverview[1]}}, {{ $earningsOverview[2]}}, {{ $earningsOverview[3]}},
                        {{ $earningsOverview[4]}}, {{ $earningsOverview[5]}}, {{ $earningsOverview[6]}},
                        {{ $earningsOverview[7]}}, {{ $earningsOverview[8]}}, {{ $earningsOverview[9]}},
                        {{ $earningsOverview[10]}}, {{ $earningsOverview[11]}}, {{ $earningsOverview[12]}}],
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

        var certificatesCanvas = document.getElementById('certificatesChart');
        var certificatesChart = new Chart(certificatesCanvas, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach ($certificates as $certificate)
                        "{{ $certificate->name }}: {{$certificate->certificate_forms_count}}",
                    @endforeach
                ],
                datasets: [{
                    label: 'Reports',
                    data: [
                        @foreach ($certificates as $certificate)
                            {{$certificate->certificate_forms_count}},
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

        var earningsThisYearCanvas = document.getElementById('earningsThisYearChart');
        var earningsThisYearChart = new Chart(earningsThisYearCanvas, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Earnings this year Report',
                    data: [{{ $earningsThisYear[1]}}, {{ $earningsThisYear[2]}}, {{ $earningsThisYear[3]}},
                        {{ $earningsThisYear[4]}}, {{ $earningsThisYear[5]}}, {{ $earningsThisYear[6]}},
                        {{ $earningsThisYear[7]}}, {{ $earningsThisYear[8]}}, {{ $earningsThisYear[9]}},
                        {{ $earningsThisYear[10]}}, {{ $earningsThisYear[11]}}, {{ $earningsThisYear[12]}}],
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
@section('title', 'Barangay Cupang - Certificate Dashboard')


@section('content')
<!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Certificate Dashboard</h1>
        </div>

        <!-- Content Row -->
        <div class="row mb-2">
            <!-- Earnings this day Card -->
            <div class="col-sm mt-2">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Earnings this day
                                    </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ₱ {{ floatval($thisDayEarning->total_price) + floatval($thisDayEarning->delivery_fee)}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings this month Card -->
            <div class="col-sm mt-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Earnings this month
                                    </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ₱ {{ floatval($thisMonthEarning->total_price) + floatval($thisMonthEarning->delivery_fee)}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings this year Card -->
            <div class="col-sm mt-2">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Earnings this year
                                    </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ₱ {{ floatval($thisYearEarning->total_price) + floatval($thisYearEarning->delivery_fee)}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row mb-4">
            <!-- Pending Order Count Card -->
            <div class="col-sm mt-2">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    <a href="{{ route('admin.orders.index') }}">Pending Order Count</a>
                                    </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $pendingOrderCount }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <img src="{{ asset('assets/img/requests.png') }}" alt="Order">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biker Count -->
            <div class="col-sm mt-2">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    <a href="{{ route('admin.bikers.index') }}">Bikers</a>
                                    </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $bikerCount }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <img src="{{ asset('assets/img/bicycle.png') }}" alt="Biker">
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
                                    <a href="{{ route('admin.bikers.applicationRequests') }}">Pending Biker's Application</a>
                                    </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $bikerApplicationCount }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <img src="{{ asset('assets/img/requirement.png') }}" alt="Biker Application">
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
                        <h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('admin.orders.index') }}">Earnings Overview</a></h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area graph">
                            <canvas id="earningsOverviewChart"></canvas>
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
                        <h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('admin.certificates.index') }}">Frequent Requested Certificate (This month)</a> </h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="graph">
                            <canvas id="certificatesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Content Column -->
            <div class="col-lg-8 mb-4">

                <!-- Earnings this year Chart Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('admin.orders.index') }}">Earnings within this year</a></h6>
                    </div>
                    <div class="card-body">
                            <div class="graph">
                            <canvas id="earningsThisYearChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">

                <!-- Illustrations -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top bikers</h6>
                    </div>
                    <div class="card-body">
                        {{-- Top bikers --}}
                        @forelse ($bikers as $biker)
                                <div class="card border-left-primary shadow h-100 py-2 mb-3">
                                    <div class="card-body mb-2">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> <a href="{{ route('admin.bikers.profile', $biker->id) }}">(#{{$biker->id}}) {{ $biker->getFullNameAttribute() }} </a>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="mb-0 font-weight-bold text-gray-800">
                                                    Earnings: ₱ {{ floatval($biker->delivers->sum('delivery_fee')) }}
                                                </div>
                                                <div class="mb-0 font-weight-bold text-gray-800">
                                                    Delivered Order Count: {{ $biker->delivers->count() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="mb-0 font-weight-bold text-gray-800">
                                    NO DATA
                                </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection
