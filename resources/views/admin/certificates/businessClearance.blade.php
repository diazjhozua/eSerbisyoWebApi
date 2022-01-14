@extends ('layouts.certificates')

@section('page-css')
    {{-- Custom Scripts for this blade --}}
    <link href="{{ asset('css/certificate/businessClearance.css')}}" rel="stylesheet"/>
@endsection

{{-- Title Page --}}
@section('title', 'Business Clearance')

@section('content')
    <div class="book">
        <div class="page">
            <div class="subpage">
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
                    <h6>BARANGAY BUSINESS CLEARANCE</h6>
                </div>

                <div class="twhom">
                    <h3>TO WHOM IT MAY CONCERN:</h3>
                </div>

                <div class="certify">
                    <p>
                        This is to clarify that <b><u>{{ strtoupper($certificateForm->business_name) }}</u></b>, with business address at
                        <b><u> {{ strtoupper($certificateForm->address) }}</u></b>,
                        has been granted BARANGAY BUSINESS CLEARANCE for the purpose of securing the necessary BUSNIESS PERMIT and License from the Office of the Mayor.
                    </p>
                </div>

                <div class="issued">
                    <p>
                        This clearance is Non-Transferable, and shall be null and void upon failure of the aforementioned applicant to strictly comply with the conditions of this permit.
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
