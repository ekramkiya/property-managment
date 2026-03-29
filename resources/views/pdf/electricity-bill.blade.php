<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>قبض برق - {{ $bill->id }}</title>
    <style>
        /* PDF styles – can be A4 or custom */
        body {
            font-family: 'DejaVu Sans', 'Tahoma', sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 12px;
        }
        .invoice-box {
            max-width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .info table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .info td {
            padding: 6px;
            border-bottom: 1px dotted #ccc;
        }
        .info td.label {
            font-weight: bold;
            width: 35%;
        }
        .total {
            font-weight: bold;
            text-align: left;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #000;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .barcode-section {
            text-align: center;
            margin: 15px 0;
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
                <tr><td class="label">مشتری:</td><td>{{ $bill->customer->name }}</td></tr>
                <tr><td class="label">شماره بل:</td><td>{{ $bill->id }}</td></tr>
                <tr><td class="label">تاریخ قرائت:</td><td>{{ \Morilog\Jalali\Jalalian::fromCarbon($bill->reading_date)->format('Y/m/d') }}</td></tr>
                <tr><td class="label">شماره قبلی:</td><td>{{ number_format($bill->previous_reading) }}</td></tr>
                <tr><td class="label">شماره فعلی:</td><td>{{ number_format($bill->current_reading) }}</td></tr>
                <tr><td class="label">مصرف (kWh):</td><td>{{ number_format($bill->current_reading - $bill->previous_reading) }}</td></tr>
                <tr><td class="label">مبلغ:</td><td>{{ number_format($bill->amount) }} AFN</td></tr>
                <tr><td class="label">وضعیت:</td><td>{{ $bill->status === 'paid' ? 'پرداخت شده' : 'پرداخت نشده' }}</td></tr>
                @if($bill->note)
                <tr><td class="label">یادداشت:</td><td>{{ $bill->note }}</td></tr>
                @endif
            </table>
        </div>

        <div class="total">
            جمع مبلغ: {{ number_format($bill->amount) }} AFN
        </div>

        <div class="barcode-section">
            @php
                $qrData = "Bill ID: {$bill->id}\nCustomer: {$bill->customer->name}\nAmount: {$bill->amount} AFN\nDate: " . ($bill->reading_date instanceof \Carbon\Carbon ? $bill->reading_date->toDateString() : $bill->reading_date);
                $qrSvg = \Milon\Barcode\Facades\DNS2DFacade::getBarcodeHTML($qrData, 'QRCODE', 2, 2);
            @endphp
            {!! $qrSvg !!}
        </div>

        <div class="footer">
         با تشکر
        </div>
    </div>
</body>
</html>