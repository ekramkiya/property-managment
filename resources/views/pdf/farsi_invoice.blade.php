<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>رسید پرداخت کرایه</title>
    <style>
        /* Use Vazir font from public folder via HTTP (asset URL) */
        @font-face {
            font-family: 'Vazir';
            src: url("{{ asset('fonts/ttf/Vazirmatn-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Vazir', sans-serif;
            font-size: 12px;
            direction: rtl;
            width: 80mm;
            margin: 0 auto;
        }

        .invoice-box {
            width: 100%;
            padding: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
        }

        .header p {
            font-size: 11px;
            margin: 3px 0;
        }

        .info table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            padding: 3px 0;
            text-align: right;
            word-break: break-word;
        }

        .label {
            font-weight: bold;
            width: 40%;
        }

        /* Numbers and English text LTR */
        .ltr {
            direction: ltr;
            unicode-bidi: embed;
            font-family: 'Vazir', monospace;
        }

        .total {
            text-align: center;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            font-weight: bold;
        }

        .barcode-section {
            text-align: center;
            margin-top: 10px;
        }

        .barcode-section svg {
            max-width: 100%;
            height: auto;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 11px;
        }
    </style>
</head>
<body>
<div class="invoice-box">

    <!-- Header -->
    <div class="header">
        <h1>رسید پرداخت کرایه</h1>
        <p>تاریخ: <span class="ltr">{{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}</span></p>
    </div>

    <!-- Info Table -->
    <div class="info">
        <table>
            <tr>
                <td class="label">شماره رسید:</td>
                <td><span class="ltr">{{ $payment->id }}</span></td>
            </tr>
            <tr>
                <td class="label">مشتری:</td>
                <td>{{ $payment->customer->name }} {{ $payment->customer->lastname }}</td>
            </tr>
            <tr>
                <td class="label">مبلغ:</td>
                <td><span class="ltr">{{ number_format($payment->amount) }} AFN</span></td>
            </tr>
            <tr>
                <td class="label">بابت ماه:</td>
                <td>{{ $payment->month }}</td>
            </tr>
            <tr>
                <td class="label">توضیحات:</td>
                <td>{{ $payment->note }}</td>
            </tr>
            <tr>
                <td class="label">تاریخ پرداخت:</td>
                <td><span class="ltr">{{ \Morilog\Jalali\Jalalian::fromCarbon($payment->payment_date)->format('Y/m/d') }}</span></td>
            </tr>
        </table>
    </div>

    <!-- Total -->
    <div class="total">
        جمع: <span class="ltr">{{ number_format($payment->amount) }} AFN</span>
    </div>

    <!-- QR Code Section -->
    <div class="barcode-section">
        @php
            $payment->loadMissing('user');
            $userName = $payment->user->name ?? 'نامشخص';

            $qrData = "Invoice: {$payment->id}\n"
                . "Amount: {$payment->amount} AFN\n"
                . "Date: " . (
                    $payment->payment_date instanceof \Carbon\Carbon
                    ? $payment->payment_date->toDateString()
                    : $payment->payment_date
                ) . "\n"
                . "User: {$userName}";

            $qrSvg = \Milon\Barcode\Facades\DNS2DFacade::getBarcodeHTML($qrData, 'QRCODE', 2, 2);
        @endphp

        {!! $qrSvg !!}
    </div>

    <!-- Footer -->
    <div class="footer">
        با تشکر از شما<br />
        سیستم مدیریت املاک
    </div>

</div>
</body>
</html>