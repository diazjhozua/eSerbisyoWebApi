@extends('layouts.email')

{{-- Title --}}
@section('greetings', 'Hello!')

{{-- Upper Message --}}
@section('messageUpper')

    @switch($changeValue)
        @case('application_status')
            Your submitted order <strong>(ID - {{ $order->id }})</strong> was changed its application status to {{ $order->application_status }}.
            <br/>
            <br/>
            Please take note the following meaning of application status: <br/>
            <strong>'Pending'</strong> -> Your application is not yet responded by the barangay officials, <br/>
            <strong>'Cancelled'</strong> -> Your application was cancelled by the barangay officials, <br/>
            <strong>'Approved'</strong> -> Your application is approved and see the following notification on what to do next, <br/>
            <strong>'Denied'</strong> -> Your application was denied by the barangay officials. It might be that your requirements is incomplete, please retry again your request. <br/>
            @break
        @case('pick_up_type')
            Your submitted order <strong>(ID - {{ $order->id }})</strong> was changed its pickup type to {{ $order->pick_up_type }}.
            <br/>
            <br/>
            Please take note the following meaning of pickup type: <br/>
            <strong>'Pickup'</strong> -> It means that it cannot find a biker who can deliver your requested certificate hence, you will be scheduled to pickup it to a specific date, <br/>
            <strong>'Delivery'</strong> -> Your application was accepted by the biker, <br/>
            <strong>'Approved'</strong> -> Your application is approved and see the following notification on what to do next, <br/>
            <strong>'Denied'</strong> -> Your application was denied by the barangay officials. It might be that your requirements is incomplete, please retry again your request. <br/>
            @break
        @case('order_status')
            Your submitted order <strong>(ID - {{ $order->id }})</strong> was changed its order status to {{ $order->order_status }}.
            <br/>
            <br/>
            Please take note the following meaning of order status: <br/>
            <strong>'Waiting'</strong> -> It means your application is pending or approved but not yet received, <br/>
            <strong>'Received'</strong> -> It means that you received your requested certificate, <br/>
            <strong>'DNR'</strong> -> It means that you did not receive the certificate form. This will automatically results to your account getting unverified, <br/>
            @break
        @case('pickup_date')
            Your submitted order <strong>(ID - {{ $order->id }})</strong> was changed its pickup date to {{ $order->pickup_date }}.

            @break
        @case('admin_message')
            Your submitted order <strong>(ID - {{ $order->id }})</strong> has been responded by the barangay officials.
            @break
    @endswitch

@endsection

{{-- Lower Message --}}
@section('messageLower1')

    @if ($changeValue == 'admin_message')
        Admin Message: {{ $order->admin_message }}
    @else
        Go to the barangay office if you have a problem with your order.
    @endif
@endsection



