<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>گزارش پرداخت‌های اجاره</title>

    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: 'DejaVu Sans', Tahoma, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
        }

        .report {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4a6b8f;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #2c3e50;
        }

        .header .subtitle {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .date {
            text-align: left;
            font-size: 11px;
            margin-bottom: 20px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        td {
            text-align: right;
        }

        td.amount {
            text-align: left;
            direction: ltr;
            font-family: monospace;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .empty-row td {
            text-align: center;
            color: #888;
            padding: 20px;
        }

        .total-row {
            font-weight: bold;
            background: #eef3ff;
        }

        .total-row td {
            border-top: 2px solid #888;
            padding: 10px;
        }

        .footer {
            position: fixed;
            bottom: 10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        /* Prevent row breaking across pages */
        tr {
            page-break-inside: avoid;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

    </style>
</head>

<body>

<div class="report">

    <div class="header">
        <h1>گزارش پرداخت‌های کرایه</h1>
        <div class="subtitle">سیستم مدیریت املاک</div>
    </div>

    <div class="date">
        تاریخ تهیه: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}
    </div>

    @php
        $total = $payments->sum('amount');
    @endphp

    <table>
        <thead>
            <tr>
                <th>مشتری</th>
                <th>مبلغ (AFN)</th>
                <th>بابت ماه</th>
                <th>تاریخ پرداخت</th>
                <th>توضیحات</th>
                <th>ثبت‌کننده</th>
                <th>یادداشت</th>
            </tr>
        </thead>

        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->customer->name ?? '' }} {{ $payment->customer->lastname ?? '' }}</td>
                <td class="amount">{{ number_format($payment->amount) }}</td>
                <td>{{ $payment->month ?? '' }}</td>
                <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($payment->payment_date)->format('Y/m/d') }}</td>
                <td>{{ $payment->note ?? '' }}</td>
                <td>{{ $payment->user->name ?? '' }}</td>
                <td>{{ $payment->note ?? '' }}</td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="7">هیچ داده‌ای وجود ندارد</td>
            </tr>
            @endforelse
        </tbody>

        @if($payments->isNotEmpty())
        <tfoot>
            <tr class="total-row">
                <td>جمع کل</td>
                <td class="amount">{{ number_format($total) }}</td>
                <td colspan="5"></td>
            </tr>
        </tfoot>
        @endif
    </table>

</div>

<div class="footer">
    گزارش تهیه شده توسط سیستم مدیریت املاک – نسخه چاپی
</div>

</body>
</html>