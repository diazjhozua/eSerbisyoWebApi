@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/certification/certificates/show.js')}}"></script>

@endsection


{{-- Title Page --}}
@section('title', $certificate->name)

@section('content')

    {{-- Included Modals --}}
    @include('admin.certification.certificates.addRequirement')
    @include('admin.certification.certificates.reportSelectModal')
    @include('inc.delete')


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button> {{ $certificate->name }}
            (#{{ $certificate->id }})
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>

            {{-- Edit --}}
            <button class="btn btn-primary btn-sm" onclick="editCertificate({{ $certificate->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
        </h1>
    </div>


    <div class="information">
        <div class="row">
            <div class="col-md-6 col-sm-6 mt-3">
                {{-- Certificate Information --}}
                <div class="card text-black mb-3">
                    <div class="card-body">
                        <form name="certificateForm" id="certificateForm" action="{{ route('admin.certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                            @method('PUT')
                            {{-- Name --}}
                            <div class="form-group">
                                <label for="name">Certificate Name</label>
                                <input type="text" class="form-control" name="inputName" id="inputName" value="{{ $certificate->name }}" disabled>
                                <small class="form-text text-muted">These will be the name displayed in the android application</small>
                            </div>

                            {{-- Price --}}
                            <div class="form-group">
                                <label for="contact_user_id">Price</label>
                                <input type="number" class="form-control" name="inputPrice" id="inputPrice" value="{{ $certificate->price }}" disabled>
                                <small class="form-text text-muted">The specified user_id will receive notification within the application</small>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="selectStatus" name="selectStatus" disabled>
                                    <option value="{{ $certificate->status }}" selected>{{ $certificate->status }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="delivery_option">Received Option</label>
                                <select class="form-control" id="selectReceivedOption" name="selectReceivedOption" disabled>
                                    <option value="{{ $certificate->is_open_delivery }}" selected>{{ $certificate->is_open_delivery == 1 ? 'Available for delivery and walkin' : 'Walkin Only' }}</option>
                                </select>
                            </div>

                            <button type="button" class="btn btn-secondary" onClick="cancelEditing()" id="cancelEditBtn" data-dismiss="modal" hidden>Cancel</button>

                            <button type="submit" class="btn btn-primary btnCertificateFormSubmit" hidden>
                                <i class="btnCertificateFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                                <span class="btnCertificateFormTxt">Submit</span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6">
                <div class="card shadow mt-2 mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Requirements List<span id="requirementsCount">{{ $certificate->requirements->count()}}</span>
                        <!-- Button to trigger modal (for adding of requirement) -->
                        <button type="button" class="btn btn-primary" onclick="addRequirement({{ $certificate->id }})" data-toggle="tooltip" data-placement="top" title="Add Requirement">
                            <i class="fas fa-plus"></i>
                        </button>

                        </h6>
                    </div>

                    {{-- CERTIFICATE REQUIREMENTS TABLE --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTableRequirement" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($certificate->requirements as $requirement)
                                        <tr>
                                            <td>{{ $requirement->id }}</td>
                                            <td>{{ $requirement->name }}</td>

                                            <td>
                                                <ul class="list-inline m-0">
                                                    <li class="list-inline-item mb-1">
                                                        <button class="btn btn-danger btn-sm" onclick="deleteRequirement({{ $certificate->id }},{{$requirement->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <p>No comments added</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p class="text-justify">
        These are the list of the approved {{ $certificate->name }} requested by the residents thru smartphone. You can use this page for checking or reprinting of the said certificate.
    </p>


    {{-- List of approved request of the certificate --}}
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="typeCount">{{ $certificateForms->count() }}</span>)</h6>
                @if (Auth::user()->user_role_id < 5)
                    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal">
                        <i class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableApprovedRequest" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            @if ($certificate->id == 1 || $certificate->id == 3)
                                <th>ID</th>
                                <th>Requested by</th>
                                <th>From Order</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Purpose</th>
                                <th>General Information</th>
                                <th>Date Requested</th>
                                <th>Action</th>
                            @endif

                            @if ($certificate->id == 2)
                                <th>ID</th>
                                <th>Requested by</th>
                                <th>From Order</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>General Information</th>
                                <th>Date Requested</th>
                                <th>Action</th>
                            @endif

                            @if ($certificate->id == 4)
                                <th>ID</th>
                                <th>Requested by</th>
                                <th>From Order</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>General Information</th>
                                <th>Date Requested</th>
                                <th>Action</th>
                            @endif

                            @if ($certificate->id == 5)
                                <th>ID</th>
                                <th>Requested by</th>
                                <th>From Order</th>
                                <th>Business Name</th>
                                <th>Address</th>
                                <th>Date Requested</th>
                                <th>Action</th>
                            @endif

                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            @if ($certificate->id == 1 || $certificate->id == 3)
                                <th>ID</th>
                                <th>Requested by</th>
                                <th>From Order</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Purpose</th>
                                <th>General Information</th>
                                <th>Date Requested</th>
                                <th>Action</th>
                            @endif

                            @if ($certificate->id == 2)
                                <th>ID</th>
                                <th>Requested by</th>
                                <th>From Order</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>General Information</th>
                                <th>Date Requested</th>
                                <th>Action</th>
                            @endif


                            @if ($certificate->id == 4)
                                <th>ID</th>
                                <th>Requested by</th>
                                <th>From Order</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>General Information</th>
                                <th>Date Requested</th>
                                <th>Action</th>
                            @endif

                            @if ($certificate->id == 5)
                                <th>ID</th>
                                <th>Requested by</th>
                                <th>From Order</th>
                                <th>Business Name</th>
                                <th>Address</th>
                                <th>Date Requested</th>
                                <th>Action</th>
                            @endif
                        </tr>
                    </tfoot>
                    <tbody>
                        {{-- IF BARANGAY CLEARANCE OR INDIGENCY --}}
                        @if ($certificate->id == 1 || $certificate->id == 3)
                            @forelse ($certificateForms as $certificateForm)
                                <tr>
                                    <td>{{ $certificateForm->id }}</td>
                                    @if ($certificateForm->user_id == null)
                                        <td>{{ $certificateForm->orders[0]->name }} (Walkin)</td>
                                    @else
                                        <td>{{ $certificateForm->user->getFullNameAttribute() }} (#{{ $certificateForm->user_id }})</td>
                                    @endif

                                    <td>From Order #{{ $certificateForm->orders[0]->id }}</td>
                                    <td>{{ $certificateForm->first_name }} {{ ucwords($certificateForm->middle_name[0]).'.' }} {{ $certificateForm->last_name }}</td>
                                    <td>{{ $certificateForm->address }}</td>
                                    <td>{{ $certificateForm->purpose }}</td>
                                    <td>
                                        <p><span class="font-weight-bold">Birthday: </span> {{ $certificateForm->birthday }}</p>
                                        <p><span class="font-weight-bold">Citizenship: </span> {{ $certificateForm->citizenship }}</p>
                                        <p><span class="font-weight-bold">Civil Status: </span> {{ $certificateForm->civil_status }}</p>
                                    </td>
                                    <td>{{ date_format($certificateForm->created_at,'d-m-Y') }}</td>
                                    <td>
                                        <ul class="list-inline m-0">
                                            <li class="list-inline-item mb-1">
                                                <a class="btn btn-primary btn-sm" href="{{ route('admin.certificates.printCertificate', $certificateForm->id) }}" target="_blank" type="button" data-toggle="tooltip" data-placement="top" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <p>No certificates created</p>
                            @endforelse
                        @endif

                        {{-- BARANGAY CEDULA --}}
                        @if ($certificate->id == 2)
                            @forelse ($certificateForms as $certificateForm)
                                <tr>
                                    <td>{{ $certificateForm->id }}</td>
                                    @if ($certificateForm->user_id == null)
                                        <td>{{ $certificateForm->orders[0]->name }} (Walkin)</td>
                                    @else
                                        <td>{{ $certificateForm->user->getFullNameAttribute() }} (#{{ $certificateForm->user_id }})</td>
                                    @endif
                                    <td>From Order #{{ $certificateForm->orders[0]->id }}</td>
                                    <td>{{ $certificateForm->first_name }} {{ ucwords($certificateForm->middle_name[0]).'.' }} {{ $certificateForm->last_name }}</td>
                                    <td>{{ $certificateForm->address }}</td>
                                    <td>
                                        <p><span class="font-weight-bold">Sex: </span> {{ $certificateForm->sex }}</p>
                                        <p><span class="font-weight-bold">Birthday: </span> {{ $certificateForm->birthday }}</p>
                                        <p><span class="font-weight-bold">Citizenship: </span> {{ $certificateForm->citizenship }}</p>
                                        <p><span class="font-weight-bold">Cedula Type: </span> {{ $certificateForm->cedula_type }}</p>
                                        <p><span class="font-weight-bold">TIN: </span> {{ $certificateForm->tin_no }}</p>
                                        <p><span class="font-weight-bold">ICR Type: </span> {{ $certificateForm->icr_no }}</p>
                                        <p><span class="font-weight-bold">Civil Status: </span> {{ $certificateForm->civil_status }}</p>
                                        <p><span class="font-weight-bold">Profession: </span> {{ $certificateForm->profession }}</p>
                                        <p><span class="font-weight-bold">Basic Community Tax: </span> ₱ {{ $certificateForm->basic_tax }}</p>
                                        <p><span class="font-weight-bold">Additional Community Tax : </span> ₱ {{ $certificateForm->additional_tax }}</p>
                                        <p><span class="font-weight-bold">Interest: </span> ₱ {{ $certificateForm->interest }}</p>

                                        <p>
                                            <span class="font-weight-bold">Earnings derived from business during the preceding: </span>
                                            ₱ {{ $certificateForm->gross_receipt_preceding }}
                                        </p>

                                        <p>
                                            <span class="font-weight-bold">Earnings derived from exercise of profession: </span>
                                            ₱ {{ $certificateForm->gross_receipt_profession }}
                                        </p>

                                        <p>
                                            <span class="font-weight-bold">Income from real property: </span>
                                            ₱ {{ $certificateForm->real_property }}
                                        </p>
                                    </td>
                                    <td>{{ date_format($certificateForm->created_at,'d-m-Y') }}</td>
                                    <td>
                                        <ul class="list-inline m-0">
                                            <li class="list-inline-item mb-1">
                                                <a class="btn btn-primary btn-sm" href="{{ route('admin.certificates.printCertificate', $certificateForm->id) }}" target="_blank" type="button" data-toggle="tooltip" data-placement="top" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <p>No certificates created</p>
                            @endforelse
                        @endif

                        {{-- IF BARANGAY ID --}}
                        @if ($certificate->id == 4)
                            @forelse ($certificateForms as $certificateForm)
                                <tr>
                                    <td>{{ $certificateForm->id }}</td>
                                    @if ($certificateForm->user_id == null)
                                        <td>{{ $certificateForm->orders[0]->name }} (Walkin)</td>
                                    @else
                                        <td>{{ $certificateForm->user->getFullNameAttribute() }} (#{{ $certificateForm->user_id }})</td>
                                    @endif
                                    <td>From Order #{{ $certificateForm->orders[0]->id }}</td>
                                    <td>{{ $certificateForm->first_name }} {{ ucwords($certificateForm->middle_name[0]).'.' }} {{ $certificateForm->last_name }}</td>
                                    <td>{{ $certificateForm->address }}</td>
                                    <td>
                                        <p><span class="font-weight-bold">Contact No: </span> {{ $certificateForm->contact_no }}</p>
                                        <p><span class="font-weight-bold">Contact Person: </span> {{ $certificateForm->contact_person }}</p>
                                        <p><span class="font-weight-bold">Contact Person No: </span> {{ $certificateForm->contact_person_no }}</p>
                                        <p><span class="font-weight-bold">Contact Person Relation: </span> {{ $certificateForm->contact_person_relation }}</p>
                                    </td>
                                    <td>{{ date_format($certificateForm->created_at,'d-m-Y') }}</td>
                                    <td>
                                        <ul class="list-inline m-0">
                                            <li class="list-inline-item mb-1">
                                                <a class="btn btn-primary btn-sm" href="{{ route('admin.certificates.printCertificate', $certificateForm->id) }}" target="_blank" type="button" data-toggle="tooltip" data-placement="top" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <p>No certificates created</p>
                            @endforelse
                        @endif

                        {{-- IF BARANGAY ID --}}
                        @if ($certificate->id == 5)
                            @forelse ($certificateForms as $certificateForm)
                                <tr>
                                    <td>{{ $certificateForm->id }}</td>
                                    @if ($certificateForm->user_id == null)
                                        <td>{{ $certificateForm->orders[0]->name }} (Walkin)</td>
                                    @else
                                        <td>{{ $certificateForm->user->getFullNameAttribute() }} (#{{ $certificateForm->user_id }})</td>
                                    @endif
                                    <td>From Order #{{ $certificateForm->orders[0]->id }}</td>
                                    <td>{{ $certificateForm->business_name }}</td>
                                    <td>{{ $certificateForm->address }}</td>
                                    <td>{{ date_format($certificateForm->created_at,'d-m-Y') }}</td>
                                    <td>
                                        <ul class="list-inline m-0">
                                            <li class="list-inline-item mb-1">
                                                <a class="btn btn-primary btn-sm" href="{{ route('admin.certificates.printCertificate', $certificateForm->id) }}" target="_blank" type="button" data-toggle="tooltip" data-placement="top" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <p>No certificates created</p>
                            @endforelse
                        @endif

                        {{-- @forelse ($certificates as $certificate)
                            <tr>
                                <td>{{ $certificate->id }}</td>
                                <td>{{ $certificate->name }}</td>
                                <td>{{ '₱'.number_format($certificate->price, 2) }}</td>
                                <td>{{ $certificate->certificate_forms_count }}</td>
                                <td>{{ $certificate->is_open_delivery == 1 ? 'Open for delivery' : 'Not available for delivery' }}</td>
                                <td>{{ $certificate->updated_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.certificates.show', $certificate->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-primary btn-sm" onclick="editCertificate({{ $certificate->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No certificates created</p>
                        @endforelse --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection





