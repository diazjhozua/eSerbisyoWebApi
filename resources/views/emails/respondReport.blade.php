@extends('layouts.email')

{{-- Title --}}
@section('greetings', 'Hello!')

{{-- Upper Message --}}
@section('messageUpper')

    @if ($subject == 'Your submitted report was noted by the barangay official')
        Your submitted report <strong>(ID - {{ $report->id }})</strong> was noted by the barangay official. The barangay taskforce will check
        the specified location. Thank you for being an alert and responsible resident in the barangay
        Cupang!

    @else
        Your submitted report <strong>(ID - {{ $report->id }})</strong> was invalidated by the barangay official. Please see the proper guidelines
        in terms of submitting a proper report. Regardless, thank you for being an alert and responsible resident in the barangay
        Cupang.
    @endif

@endsection

{{-- Lower Message --}}
@section('messageLower1')
    @if ($subject == 'Your submitted report was noted by the barangay official')
        Admin Message: {{ $report->admin_message }}
    @else
        Reason: {{ $report->admin_message }}
    @endif
@endsection



