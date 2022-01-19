<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order #{{ $order->id }} Receipt</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}"/>
    <link href="{{ asset('css/certificate/receipt.css')}}" rel="stylesheet"/>

</head>
<body>
    <div class="ticket">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
        <table>
            <th>
                <tr>
                    <th><p class="centered">Barangay Cupang E-Serbisyo Receipt</p></th>
                 </tr>
        </table>

        @if ($order->ordered_by != null)
            <p style="text-align:left;">Name: (#{{ $order->ordered_by }}) {{ $order->contact->getFullNameAttribute() }}</p>
        @else
            <p style="text-align:left;">Name: {{ $order->name }}</p>
        @endif


        <p style="text-align:left;">Address: {{ $order->location_address }} Cupang, Muntinlupa City.</p>
        <p style="text-align:left;">Contact No. <u><b>{{ $order->phone_no }}</b></u></p>
        <p style="text-align:left;">O.R. No <b><u>{{ $order->id }}</u></b></p>
        <p style="text-align:left;">Pickup Type: {{ $order->pick_up_type }}</p>
        <p style="text-align:left;">Pickup Date: {{ $order->pickup_date }}</p>

        @if ($order->pick_up_type == 'Delivery')
            <p style="text-align:left;">Delivered By:  #({{$order->delivered_by}}){{ $order->biker->getFullNameAttribute() }}</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th class="quantity">Q.</th>
                    <th class="description">Certificate</th>
                    <th class="price">₱</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($order->certificateForms as $certificateForm)
                    <tr>
                        <td class="quantity">1</td>
                        <td class="description">{{ $certificateForm->certificate->name }}</td>
                        <td class="price">{{ '₱'.$certificateForm->price_filled }}</td>
                    </tr>
                @empty
                    <p>No submitted certificates</p>
                @endforelse

                @if ($order->pick_up_type == 'Delivery')
                    <tr>
                        <td class="quantity"></td>
                        <td class="description">CERT PRICE</td>
                        <td class="price">₱{{ $order->total_price }}</td>
                    </tr>
                    <tr>
                        <td class="quantity"></td>
                        <td class="description">DELIVERY</td>
                        <td class="price">₱{{ $order->delivery_fee }}</td>
                    </tr>

                    <tr>
                        <td class="quantity"></td>
                        <td class="description">Total Price</td>
                        <td class="price">₱{{ floatval($order->total_price) + floatval($order->delivery_fee)}}</td>
                    </tr>
                @else
                    <tr>
                        <td class="quantity"></td>
                        <td class="description">Total Price</td>
                        <td class="price">₱{{ $order->total_price }}</td>
                    </tr>
                @endif

            </tbody>
        </table>

        @if ($order->pick_up_type == 'Delivery')
            <p style="text-align:center;"><b>Customer Signature</b></p>
            <p style="text-align:center;"><b>____________________________</b></p>
        @endif

        <p class="centered">Thank you for using our application!
            <br>BarangayCupang.com</p>
    </div>
    <button id="btnPrint" class="hidden-print">Print</button>
    <script>
        const $btnPrint = document.querySelector("#btnPrint");
        $btnPrint.addEventListener("click", () => {
            window.print();
        });
    </script>
</body>
</html>
