<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Barangay Cupang">
        </div>
        <div class="sidebar-brand-text mx-3">Barangay Cupang</div>
    </a>

    @if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 3)
        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li id="dashboard" class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                <img src="{{ asset('assets/img/dashboard.png') }}" alt="Dashboard">
                <span>Dashboard</span></a>

                <div class="sidebar-brand-icon">

                </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Admin Interface
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li id="OfficialAdminCollapse" class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOfficialAdmin"
                aria-expanded="true" aria-controls="collapseTwo">
                <img src="{{ asset('assets/img/staff.png') }}" alt="Staff">
                <span>Staff</span>
            </a>
            <div id="collapseOfficialAdmin" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Available function:</h6>
                    <a id="promoteUserItem" class="collapse-item" href="{{ route('admin.staffs.users') }}">Promote User</a>
                    <a id="demoteStaffItem" class="collapse-item" href="{{ route('admin.staffs.adminStaff') }}">Demote Certificate Staff</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Tables -->
        <li id="auditLogItem" class="nav-item">
            <a class="nav-link" href="{{ route('admin.auditLogs') }}">
                <img src="{{ asset('assets/img/audit.png') }}" alt="Audit">
                <span>Audit Logs</span></a>
        </li>

        <!-- User Nav Item - Tables -->
        <li id="user" class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <img src="{{ asset('assets/img/users.png') }}" alt="Users">
                <span>Users</span></a>
        </li>

    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Models
    </div>

    <!-- Request Nav Item - Tables -->
    <li id="orders_nav" class="nav-item">
        <a class="nav-link" href="{{ route('admin.orders.index') }}">
            <img src="{{ asset('assets/img/requests.png') }}" alt="Users">
            <span>Orders</span></a>
    </li>

    <!-- Certificate Nav Item - Tables -->
    <li id="certificate" class="nav-item">
        <a class="nav-link" href="{{ route('admin.certificates.index') }}">
            <img src="{{ asset('assets/img/certificate.png') }}" alt="Android">
            <span>Certificate</span></a>
    </li>

    <!-- Requirement Nav Item - Tables -->
    <li id="requirement" class="nav-item">
        <a class="nav-link" href="{{ route('admin.requirements.index') }}">
            <img src="{{ asset('assets/img/requirement.png') }}" alt="Requirement">
            <span>Requirement</span></a>
    </li>

    <!-- Barangay Official Nav Item - Pages Collapse Menu -->
    <li id="bikerCollapse" class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBiker"
            aria-expanded="true" aria-controls="collapseTwo">
            <img src="{{ asset('assets/img/bicycle.png') }}" alt="Bicycle">
            <span>Bikers</span>
        </a>

        <div id="collapseBiker" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Bikers:</h6>
                <a id="biker_nav" class="collapse-item" href="{{ route('admin.bikers.index') }}">List</a>
                <a id="biker_request_nav" class="collapse-item" href="{{ route('admin.bikers.applicationRequests') }}">Pending Application</a>

            </div>
        </div>
    </li>

    <li id="orderReport" class="nav-item">
        <a class="nav-link" href="{{ route('admin.orderReports.index') }}">
            <img src="{{ asset('assets/img/report.png') }}" alt="orderReport">
            <span>Order Report</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
