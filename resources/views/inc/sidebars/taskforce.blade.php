<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Barangay Cupang">
        </div>
        <div class="sidebar-brand-text mx-3">Barangay Cupang</div>
    </a>

    @if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4)
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
                    <a id="demoteStaffItem" class="collapse-item" href="{{ route('admin.staffs.adminStaff') }}">Demote Taskforce Staff</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
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

    <!-- User Nav Item - Tables -->
    <li id="report" class="nav-item">
        <a class="nav-link" href="{{ route('admin.reports.index') }}">
            <img src="{{ asset('assets/img/report.png') }}" alt="report">
            <span>Report</span></a>
    </li>

     <!-- Types Nav Item - Pages Collapse Menu -->
     <li id="TypeTaskforceNavCollapse" class="nav-item">
        <a  class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTaskforceType"
            aria-expanded="true" aria-controls="collapseTwo">
            <img src="{{ asset('assets/img/type.png') }}" alt="Type">
            <span>Types</span></a>
        </a>
        <div id="collapseTaskforceType" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Model Types</h6>
                <a id="complaintType" class="collapse-item" href="{{ route('admin.complaint-types.index') }}">Complaint</a>
                <a id="reportType" class="collapse-item" href="{{ route('admin.report-types.index') }}">Report</a>
            </div>
        </div>
    </li>

    <!-- Feedback Nav Item - Tables -->
    <li id="complaint" class="nav-item">
        <a class="nav-link" href="{{ route('admin.complaints.index') }}">
            <img src="{{ asset('assets/img/complaint.png') }}" alt="Complaint">
            <span>Complaint</span></a>
    </li>

    <!-- Document Nav Item - Tables -->
    <li id="missingPerson" class="nav-item">
        <a class="nav-link" href="{{ route('admin.missing-persons.index') }}">
            <img src="{{ asset('assets/img/missingPerson.png') }}" alt="Missing-Person">
            <span>Missing Person</span></a>
    </li>

    <!-- Ordinance Nav Item - Tables -->
    <li id="missingItem" class="nav-item">
        <a class="nav-link" href="{{ route('admin.missing-items.index') }}">
            <img src="{{ asset('assets/img/missingItem.png') }}" alt="Missing-Item">
            <span>Missing Item</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
