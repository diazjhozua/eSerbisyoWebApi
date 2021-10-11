<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('information-dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Barangay Cupang">
        </div>
        <div class="sidebar-brand-text mx-3">Barangay Cupang</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('information-dashboard') }}">
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
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <img src="{{ asset('assets/img/staff.png') }}" alt="Staff">
            <span>Staff</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Available function:</h6>
                <a class="collapse-item" href="buttons.html">Promote User</a>
                <a class="collapse-item" href="cards.html">Demote Information Staff</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <img src="{{ asset('assets/img/audit.png') }}" alt="Audit">
            <span>Audit Logs</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Models
    </div>

    <!-- User Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive"
            aria-expanded="true" aria-controls="collapseTwo">
            <img src="{{ asset('assets/img/users.png') }}" alt="Users">
            <span>Users</span>
        </a>
        <div id="collapseFive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Types:</h6>
                <a class="collapse-item" href="buttons.html">Registered User</a>
                <a class="collapse-item" href="cards.html">Pending Registration</a>
            </div>
        </div>
    </li>

    <!-- Barangay Official Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour"
            aria-expanded="true" aria-controls="collapseTwo">
            <img src="{{ asset('assets/img/organization-chart.png') }}" alt="Official Chart">
            <span>Barangay Officials</span>
        </a>
        <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Types:</h6>
                <a class="collapse-item" href="buttons.html">Term</a>
                <a class="collapse-item" href="cards.html">Official</a>
            </div>
        </div>
    </li>

     <!-- Types Nav Item - Pages Collapse Menu -->
     <li id="TypeNavCollapse" class="nav-item">
        <a  class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseType"
            aria-expanded="true" aria-controls="collapseTwo">
            <img src="{{ asset('assets/img/type.png') }}" alt="Feedbacks">
            <span>Types</span></a>
        </a>
        <div id="collapseType" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Model Types</h6>
                <a id="feedbackType" class="collapse-item" href="{{ route('admin.feedback-types.index') }}">Feedback</a>
                <a id="documentType" class="collapse-item" href="{{ route('admin.document-types.index') }}">Document</a>
                <a id="ordinanceType" class="collapse-item" href="{{ route('admin.ordinance-types.index') }}">Ordinance</a>
                <a id="projectType" class="collapse-item" href="{{ route('admin.project-types.index') }}">Project</a>
                <a class="collapse-item" href="">Announcement</a>
            </div>
        </div>
    </li>

    <!-- Feedback Nav Item - Tables -->
    <li id="feedback" class="nav-item">
        <a class="nav-link" href="{{ route('admin.feedbacks.index') }}">
            <img src="{{ asset('assets/img/feedback.png') }}" alt="Feedbacks">
            <span>Feedbacks</span></a>
    </li>

    <!-- Document Nav Item - Tables -->
    <li id="document" class="nav-item">
        <a class="nav-link" href="{{ route('admin.documents.index') }}">
            <img src="{{ asset('assets/img/documents.png') }}" alt="Documents">
            <span>Documents</span></a>
    </li>

    <!-- Ordinance Nav Item - Tables -->
    <li id="ordinance" class="nav-item">
        <a class="nav-link" href="{{ route('admin.ordinances.index') }}">
            <img src="{{ asset('assets/img/ordinance.png') }}" alt="Ordinances">
            <span>Ordinances</span></a>
    </li>

    <!-- Project Nav Item - Tables -->
    <li id="project" class="nav-item">
        <a class="nav-link" href="tables.html">
            <img src="{{ asset('assets/img/project.png') }}" alt="Projects">
            <span>Projects</span></a>
    </li>

    <!-- Announcement Nav Item - Tables -->
    <li id="announcement" class="nav-item">
        <a class="nav-link" href="tables.html">
            <img src="{{ asset('assets/img/announcement.png') }}" alt="Announcement">
            <span>Announcements</span></a>
    </li>




    <!-- Nav Item - Pages Collapse Menu -->
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
            aria-expanded="true" aria-controls="collapseTwo">
            <img src="{{ asset('assets/img/missing.png') }}" alt="Missing">
            <span>Missing Reports</span>
        </a>
        <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Types:</h6>
                <a class="collapse-item" href="{{ route('missing-report') }}">Person</a>
                <a class="collapse-item" href="cards.html">Property</a>
            </div>
        </div>
    </li> --}}











    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
