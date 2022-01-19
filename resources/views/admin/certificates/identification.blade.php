@extends ('layouts.certificates')

@section('page-css')
    {{-- Custom Scripts for this blade --}}
    <link href="{{ asset('css/certificate/identification.css')}}" rel="stylesheet"/>
@endsection

{{-- Title Page --}}
@section('title', 'Barangay ID')

@section('content')

	<div class="container">

		<div class="padding">
            <button id="printPageButton"  onclick="window.print()">PRINT</button>
            <div class="fronterist">
                <div class="font">
                    <div class="top">
                        <img src="{{ asset('assets/img/certificates/munti.png')}}" alt="Muntinlupa">
                        <p>Republic of the Philippines</p>
                        <p class="city"> City of Muntinlupa </p>
                        <p class="barangay"> BARANGAY </p>
                        <p class="cupang"> CUPANG </p>
                    </div>
                    <div class="bottom">
                        <img src="{{ asset('assets/img/certificates/munti.png')}}" alt="Muntinlupa">
                        <p>I.D. Number:<u> {{ $certificateForm->id }}</u></p>
                        <p class="name"><u>{{ $certificateForm->first_name }} {{ ucwords($certificateForm->middle_name[0]).'.' }} {{ $certificateForm->last_name }}</u></p>
                        <p class= "nm">Name</p>
                    </div>
                    
                    <div class="person">
                        {{-- <img src="{{ asset('storage/'.$certificateForm->file_path) }}" alt="Signature"> --}}
                        <img src="{{ asset('assets/img/certificates/signa.png')}}" alt="img">
                        <p>____________________________________________________</p>
                        <p class="signature"> Signature</p>
                    </div>
                </div>
            </div>

		</div>

		<div class="back">
		    <br>
            <div class="details-info">
                <p class="addr">{{ $certificateForm->address }} </p>
                <p style="font-size:14px"><b>ADDRESS:</b>______________________________________</p>
                <br>

                <p class="mcty">Cupang Muntinlupa City</p>
                <p style="font-size:14px">_______________________________________________</p>
                <br>
                <p class="num">{{ $certificateForm->contact_no }}</p>
                <p style="font-size:15px"><b>Contact No:_________________________________</b></p>
                <br>
                <p class="bday">{{  \Carbon\Carbon::parse($certificateForm->birthday )->format('l jS \\of F Y') }}</p>
                <p style="font-size:14px"><b>BIRTHDATE:_____________________________________</b></p>
                <br>
                <p class="pobt">{{ $certificateForm->birthplace }}</p>
                <p style="font-size:14px"><b>PLACE OF BIRTH:_______________________________</b></p>
                <br>
                <p class="dateis">{{ \Carbon\Carbon::now()->isoFormat('MMM Do YYYY') }}</p>
                <p style="font-size:14px"><b>DATE ISSUED:___________________________________ </b></p>
                <br>
                <p class="connum">{{ $certificateForm->contact_person_no }}</p>
                <p style="font-size:14px"><b>CONTACT PERSON/NO:__________________________</b></p>
                    <p class="conname">{{ $certificateForm->contact_person }} ({{ $certificateForm->contact_person_relation }})</p>
                <p style="font-size:14px"><b>________________________________________________</b></p>


                <div class="signaz">
                    <img src="{{ asset('assets/img/certificates/signa.png')}}" alt="Signature">
                    <br>
                    <p>_________________________</p>
                    <p class="signame">Hon. RAINIER EMMANUELLE B. BULOS</p>
                    <p class="signameby">DIC. Barangay Chairman</p>

                </div>

                <div class="writ">
                    <p class="ident">This identification card is non-transferable and must be used exclusively by a bonifide resident of Brgy. Cupang, Muntinlupa City. In case of Lost I.D., the finder is requested to return it to the Brgy. Cupang, Muntilupa City or contact the number of this identification card. This identification card is valid only for (1) year from the date of issuance.</p>
                </div>
            </div>
        </div>
   	</div>
@endsection

