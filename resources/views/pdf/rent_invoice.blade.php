<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>رسید پرداخت اجاره</title>
    <style>
        /* Thermal printer friendly */
        body {
            font-family: 'DejaVu Sans', 'Tahoma', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
            width: 80mm;
            max-width: 80mm;
        }

        .invoice-box {
            margin: 0;
            padding: 5px;
            /* Prevent page break inside the main container */
            page-break-inside: avoid;
            page-break-after: avoid;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
            /* reduced */
            margin-bottom: 5px;
            /* reduced */
        }

        .header h1 {
            margin: 0;
            font-size: 14px;
        }

        .header p {
            margin: 2px 0;
            font-size: 9px;
        }

        .info table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin: 5px 0;
            /* reduced */
        }

        .info td {
            padding: 3px 2px;
            /* slightly less */
            border: none;
            vertical-align: top;
        }

        .info td.label {
            font-weight: bold;
            width: 35%;
        }

        .total {
            font-weight: bold;
            text-align: left;
            margin-top: 5px;
            padding-top: 3px;
            border-top: 1px dashed #000;
            font-size: 10px;
        }

        .footer {
            margin-top: 5px;
            text-align: center;
            font-size: 8px;
            border-top: 1px dashed #000;
            padding-top: 3px;
        }

        .barcode-section {
            margin: 5px 0;
            text-align: center;
        }

        .barcode-section svg {
            max-width: 100%;
            height: auto;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
        }

        /* Ensure no extra page breaks inside any element */
        * {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <h1>رسید پرداخت کرایه</h1>
            <p>تاریخ: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}</p>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td class="label">مشتری:</td>
                    <td>{{ $payment->customer->name }} {{ $payment->customer->lastname }}</td>
                </tr>
                <tr>
                    <td class="label">مبلغ:</td>
                    <td>{{ number_format($payment->amount) }} AFN</td>
                </tr>
                <tr>
                    <td class="label">تاریخ پرداخت:</td>
                    <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($payment->payment_date)->format('Y/m/d') }}</td>
                </tr>
                <tr>
                    <td class="label">شماره رسید:</td>
                    <td>{{ $payment->id }}</td>
                </tr>
            </table>
        </div>

        <div class="total">
            جمع: {{ number_format($payment->amount) }} AFN
        </div>

        <!-- QR Code Section -->
        <div class="barcode-section">
            @php
                $payment->loadMissing('user');  // loads only if not already loaded
                $userName = $payment->user->name ?? 'N/A';
                $qrData = "Invoice: {$payment->id}\nAmount: {$payment->amount} AFN\nDate: " . ($payment->payment_date instanceof \Carbon\Carbon ? $payment->payment_date->toDateString() : $payment->payment_date) . "\nUser: {$userName}";
                $qrSvg = \Milon\Barcode\Facades\DNS2DFacade::getBarcodeHTML($qrData, 'QRCODE', 2, 2);
            @endphp
            <div>
                {{-- <strong>QR Code</strong><br> --}}
                @if($qrSvg)
                    {!! $qrSvg !!}
                @else
                    <div style="color: red;">خطا در تولید QR Code</div>
                @endif
                {{-- <div style="font-size: 8px; margin-top: 2px;">شماره: {{ $payment->id }}</div> --}}
            </div>
        </div>

        <div class="footer">
            با تشکر از شما<br>
            سیستم مدیریت املاک
        </div>
    </div>
</body>

</html>