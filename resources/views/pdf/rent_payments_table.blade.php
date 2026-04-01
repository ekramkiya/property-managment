<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>گزارش پرداخت‌های اجاره</title>
    <style>
        /* Base styles */
        body {
            font-family: 'DejaVu Sans', 'Tahoma', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 30px;
        }

        .report {
            max-width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #4a6b8f;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #2c3e50;
        }
        .header .subtitle {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .date {
            text-align: left;
            font-size: 11px;
            margin-bottom: 25px;
            color: #555;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 15px; /* increased horizontal padding */
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            color: #2c3e50;
        }
        td {
            text-align: right;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        td.amount {
            text-align: left;
            direction: ltr;
            font-family: monospace;
        }
        .empty-row td {
            text-align: center;
            color: #999;
        }

        .total-row {
            font-weight: bold;
            background-color: #eef;
        }
        .total-row td {
            border-top: 2px solid #aaa;
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-size: 10px;
            text-align: center;
            color: #777;
        }

        .page-break {
            page-break-before: always;
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
        <td colspan="5">هیچ داده‌ای وجود ندارد</td>
    </tr>
    @endforelse
</tbody>
            @if($payments->isNotEmpty())
            <tfoot>
                <tr class="total-row">
                    <td colspan="1" style="text-align: left;">جمع کل</td>
                    <td class="amount">{{ number_format($total) }}</td>
                    <td colspan="3"></td>
                 </tr>
            </tfoot>
            @endif
         </table>

        <div class="footer">
            گزارش تهیه شده توسط سیستم مدیریت املاک – نسخه چاپی
        </div>
    </div>
</body>
</html>