@extends('layouts.email')

{{-- Title --}}
@section('greetings', 'Hello!')

{{-- Upper Message --}}
@section('messageUpper')

    @if ($status == 'Pending')
        Your submitted {{ $reportName }} <strong>(ID - {{ $id }})</strong> was changed its status by <strong>{{ $status }}</strong> by the administrator. There might be that there is a wrong input or wrong credentials.
        in submitting the report. Please edit the specified report.

    @elseif ($status == 'Approved')
        Your submitted {{ $reportName }} <strong>(ID - {{ $id }})</strong> was changed its status by <strong>{{ $status }}</strong> by the administrator. User who uses the e-serbisyo can now see the specified report.
        Thank you for using the application. Avoid editing the specified report, otherwise it will marked again as pending.

    @elseif ($status == 'Resolved')
        Your submitted {{ $reportName }} <strong>(ID - {{ $id }})</strong> was changed its status by <strong>{{ $status }}</strong> by the administrator. Please go to the barangay office as soon as possible.

    @elseif ($status == 'Denied')
        Your submitted {{ $reportName }} <strong>(ID - {{ $id }})</strong> was changed its status by <strong>{{ $status }}</strong> by the administrator. There might be that there is a wrong input or wrong credentials.
        in submitting the report. Please edit the specified report. Please edit again the specified report (It will automatically marked as pending).
    @endif

@endsection

{{-- Lower Message --}}
@section('messageLower1')
    Admin Message: <strong>{{ $adminMessage }}</strong>
@endsection



