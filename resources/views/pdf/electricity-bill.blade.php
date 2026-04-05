<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>بل برق</title>

    <style>
        body {
            width: 80mm;
            margin: 0 auto;
            font-family: Tahoma, sans-serif;
            font-size: 12px;
            color: #000;
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
            vertical-align: top;
            word-break: break-word;
        }

        .label {
            font-weight: bold;
            width: 45%;
        }

        .total {
            margin-top: 10px;
            font-weight: bold;
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 5px;
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

        @media print {
            body {
                width: 80mm;
            }
        }
    </style>
</head>

<body>

<div class="invoice-box">

    <div class="header">
        <h1>بل مصرف برق</h1>
        <p>تاریخ چاپ: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i') }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td class="label">مشتری:</td>
                <td>{{ $bill->customer->name }}</td>
            </tr>
            <tr>
                <td class="label">شماره بل:</td>
                <td>{{ $bill->id }}</td>
            </tr>
            <tr>
                <td class="label">تاریخ قرائت:</td>
                <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($bill->reading_date)->format('Y/m/d') }}</td>
            </tr>
            <tr>
                <td class="label">شماره قبلی:</td>
                <td>{{ number_format($bill->previous_reading) }}</td>
            </tr>
            <tr>
                <td class="label">شماره فعلی:</td>
                <td>{{ number_format($bill->current_reading) }}</td>
            </tr>
            <tr>
                <td class="label">مصرف (kWh):</td>
                <td>{{ number_format($bill->current_reading - $bill->previous_reading) }}</td>
            </tr>
            <tr>
                <td class="label">مبلغ:</td>
                <td>{{ number_format($bill->amount) }} AFN</td>
            </tr>
            <tr>
                <td class="label">وضعیت:</td>
                <td>{{ $bill->status === 'paid' ? 'پرداخت شده' : 'پرداخت نشده' }}</td>
            </tr>

            @if($bill->note)
            <tr>
                <td class="label">یادداشت:</td>
                <td>{{ $bill->note }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="total">
        جمع مبلغ: {{ number_format($bill->amount) }} AFN
    </div>

    <!-- QR Code -->
    <div class="barcode-section">
        @php
            $qrData = "Bill ID: {$bill->id}\n"
                    . "Customer: {$bill->customer->name}\n"
                    . "Amount: {$bill->amount} AFN\n"
                    . "Date: " . (
                        $bill->reading_date instanceof \Carbon\Carbon
                            ? $bill->reading_date->toDateString()
                            : $bill->reading_date
                    );

            $qrSvg = \Milon\Barcode\Facades\DNS2DFacade::getBarcodeHTML($qrData, 'QRCODE', 2, 2);
        @endphp

        @if($qrSvg)
            {!! $qrSvg !!}
        @else
            <div style="color:red;">خطا در تولید QR Code</div>
        @endif
    </div>

    <div class="footer">
        با تشکر
    </div>

</div>

</body>
</html>