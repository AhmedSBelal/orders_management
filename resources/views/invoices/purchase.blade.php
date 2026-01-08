<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة شراء</title>
    <style>
        /* TCPDF works best with basic CSS. Keep it simple. */
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif; /* DejaVu Sans is a good choice for Arabic in TCPDF */
            direction: rtl;
            text-align: right;
            font-size: 11px; /* Smaller base font size */
            color: #333;
            line-height: 1.4;
        }

        .invoice-container {
            width: 100%;
        }

        /* General Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Header Table */
        .header-table td {
            vertical-align: top;
            padding: 5px;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
        }
        .invoice-details {
            font-size: 11px;
        }
        .invoice-details strong {
            font-weight: bold;
        }

        /* Customer Info Table */
        .customer-table {
            margin: 15px 0;
            border: 1px solid #000;
        }
        .customer-table td {
            padding: 6px 8px;
            border: 1px solid #ccc;
            font-size: 11px;
        }
        .customer-table .label {
            font-weight: bold;
            background-color: #f2f2f2;
            width: 25%; /* Control label column width */
        }

        /* Products Table */
        .products-table {
            margin: 15px 0;
            border: 1px solid #000;
        }
        .products-table th {
            background-color: #1a3a5f;
            color: white;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #000;
        }
        .products-table td {
            padding: 6px 5px;
            text-align: center;
            border: 1px solid #000;
            font-size: 11px;
            vertical-align: top;
        }
        .products-table .item-name {
            text-align: right;
        }
        .grand-total-row {
            background-color: #e0e0e0 !important;
            font-weight: bold;
        }
        .grand-total-row td {
            font-weight: bold;
            font-size: 12px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header Section (using a table for layout) -->
        <table class="header-table">
            <tr>
                <td style="width: 15%;">
                    <img src="{{ asset('media/agyad_maka.jpeg') }}" alt="أجياد مكة" style="width: 60px; height: auto;">
                </td>
                <td style="width: 70%;">
                    <div class="company-name">أجياد مكة</div>
                    <div style="text-align: center; font-size: 14px; margin-top: 5px;">فاتورة شراء</div>
                </td>
                <td style="width: 15%; text-align: left;">
                    <div class="invoice-details">
                        <div><strong>رقم الفاتورة:</strong> {{ $order->id }}</div>
                        <div><strong>التاريخ:</strong> {{ $order->created_at->locale('ar')->translatedFormat('d/m/Y') }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Customer Info Section (using a table for layout) -->
        <table class="customer-table">
            <tr>
                <td colspan="4" style="background-color: #f2f2f2; font-weight: bold; text-align: center; border-bottom: 2px solid #000;">
                    الفاتورة موجهة إلى
                </td>
            </tr>
            <tr>
                <td class="label">الاسم/المؤسسة:</td>
                <td>{{ $order->client_name ?? $order->user->name }}</td>
                <td class="label">الهاتف:</td>
                <td>{{ $order->client_phone ?? $order->user->phone }}</td>
            </tr>
            <tr>
                <td class="label">العنوان/المدينة:</td>
                <td colspan="3">{{ $order->location ?? $order->city }}</td>
            </tr>
        </table>

        <!-- Products Table -->
        <table class="products-table">
            <thead>
                <tr>
                    <th>البند</th>
                    <th>الكمية</th>
                    <th>سعر الوحدة</th>
                    <th>المجموع</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->products as $product)
                <tr>
                    <td class="item-name">{{ $product->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>{{ number_format($product->price, 0) }}</td>
                    <td>{{ number_format($product->price * $product->pivot->quantity, 0) }}</td>
                </tr>
                @endforeach
                
                <!-- Grand Total Row -->
                <tr class="grand-total-row">
                    <td class="item-name">المجموع الكلي</td>
                    <td>{{ $order->products->sum('pivot.quantity') }}</td>
                    <td>-</td>
                    <td>{{ number_format($order->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p><strong>شكراً لتعاملكم معنا!</strong></p>
            <p>أجياد مكة - جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>