@extends ('layouts.certificates')

@section('page-css')
    {{-- Custom Scripts for this blade --}}
    <link href="{{ asset('css/certificate/clearance.css')}}" rel="stylesheet"/>
@endsection

{{-- Title Page --}}
@section('title', 'Clearance')

@section('content')
    <div class="book">
        <div class="page">
            <div class="subpage">
                <button id="printPageButton"  onclick="window.print()">PRINT</button>
                <div class="rep">
                    <h3>Republic of the Philippines</h3>
                </div>
            </div>

            <div class="city">
                <h3>City of Muntinlupa</h3>
            </div>

            <div class="brg">
                <h4>BARANGAY CUPANG</h4>
            </div>

            <div class="brgcup">
                <img src="{{ asset('assets/img/certificates/brgcup.png')}}" height="100" width="100" alt="barangay cupang">
            </div>

            <div class="munti">
                <img src="{{ asset('assets/img/certificates/munti.png')}}" height="100" width="100" alt="Muntinlupa">
            </div>

            <div class="office">
                <h5><u>OFFICE OF THE BARANGAY CHAIRMAN</u></h5>
                <div class="cert">
                    <h6>BARANGAY CLEARANCE</h6>
                </div>

                <div class="twhom">
                    <h3>TO WHOM IT MAY CONCERN:</h3>
                </div>

                <div class="certify">
                    <p>
                        This is to certify that <b><u>{{ $certificateForm->first_name }} {{ ucwords($certificateForm->middle_name[0]).'.' }} {{ $certificateForm->last_name }}</u></b>, <b><u>{{ $certificateForm->civil_status }}</u></b>,
                        <b><u>{{ \Carbon\Carbon::parse($certificateForm->birthday )->diff(\Carbon\Carbon::now())->format('%y');}}</u></b> years old,
                        <b><u>{{ $certificateForm->citizenship }}</u></b> Citizen, and belongs to the indigent family of this barangay
                        and has no derogatory record filed in out Barangay Office.
                    </p>

                    <p> The aboved-named individual who is a bonafide resident of this barangay is a person of good moral
                        character, peace-loving and civic minded citizen.
                    </p>
                </div>

                <div class="issued">
                    <p>This certification is issued upon request of the subject person of of above mentioned name for <strong><u>{{ $certificateForm->purpose }}</u></strong>. Issued this
                        <b><u>{{ $certificateForm->updated_at->format('l jS \\of F Y ') }}, this Barangay.
                    </p>
                </div>

                <div class="certby">
                    Certified Correct by:
                </div>

                <div class="signaz">
                    <img src="{{ asset('assets/img/certificates/sginat.png')}}" height="100" width="100" alt="Bulos Signature">
                </div>

                <div class="signa">
                    <b><u>HON.RAINER EMMANUEL B. BULOS</u></b>
                </div>

                <div class="brgchm">
                    Barangay Chairmann
                </div>

                <div class="ordnum">
                    O.R No: <b>{{  $certificateForm->orders[0]->id }}</b>
                </div>

                <div class="dateish">
                    Date Issued: <b>{{ \Carbon\Carbon::now()->format(' jS \\of F Y ') }}</b>
                </div>

                <div class="docstamp">
                    Doc. Stamp: <b>{{ $certificateForm->orders[0]->order_status == 'Received' ? 'Paid' : 'Pending' }}</b>
                </div>
            </div>
        </div>
    </div>
@endsection
