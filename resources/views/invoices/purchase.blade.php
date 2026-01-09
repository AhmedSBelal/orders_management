<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة شراء</title>
     <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/agyad_maka.jpeg') }}">
    <link rel="icon" type="image/jpeg" sizes="32x32" href="{{ asset('media/agyad_maka.jpeg') }}">
    <link rel="icon" type="image/jpeg" sizes="16x16" href="{{ asset('media/agyad_maka.jpeg') }}">
    <meta name="theme-color" content="#ffffff">
    <style>

        @page {
            prince-bookmark-level: none;
            marks: crop cross;
            size: A4 portrait;
            background-image: url("{{ asset('media/agyad_maka.jpeg') }}");
            background-position: 10px 10px;
            background-size: 16px 16px;
            background-repeat: no-repeat;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 10px;
            color: #333;
            line-height: 1.3;
        }

        .invoice-container {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 4px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }
        .invoice-details {
            font-size: 10px;
        }
        .invoice-details strong {
            font-weight: bold;
        }

        .customer-table {
            margin: 12px 0;
            border: 1px solid #000;
        }
        .customer-table td {
            padding: 5px 6px;
            border: 1px solid #ccc;
            font-size: 10px;
        }
        .customer-table .label {
            font-weight: bold;
            background-color: #f2f2f2;
            width: 25%;
        }

        .products-table {
            margin: 12px 0;
            border: 1px solid #000;
        }
        .products-table th {
            background-color: #1a3a5f;
            color: white;
            padding: 6px 3px;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #000;
        }
        .products-table td {
            padding: 5px 3px;
            text-align: center;
            border: 1px solid #000;
            font-size: 10px;
            vertical-align: middle;
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
            font-size: 11px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 9px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <table class="header-table">
            <tr>
                <td style="width: 15%;">
                    <img src="{{ asset('media/agyad_maka.jpeg') }}" alt="أجياد مكة" style="width: 50px; height: auto;">
                </td>
                <td style="width: 70%;">
                    <div class="company-name">أجياد مكة</div>
                    <div style="text-align: center; font-size: 13px; margin-top: 4px;">فاتورة شراء</div>
                </td>
                <td style="width: 15%; text-align: left;">
                    <div class="invoice-details">
                        <div><strong>رقم الفاتورة:</strong> {{ $order->id }}</div>
                        <div><strong>التاريخ:</strong> {{ $order->created_at->locale('ar')->translatedFormat('d/m/Y') }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Customer Info Section -->
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
                    <th>المنتج</th>
                    <th>اللون</th>
                    <th>المقاس</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>المجموع</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Load all colors once to avoid N+1 queries
                    $colorIds = $order->products->pluck('pivot.color_id')->filter()->unique();
                    $colors = \App\Models\Color::whereIn('id', $colorIds)->get()->keyBy('id');
                @endphp
                
                @foreach($order->products as $product)
                <tr>
                    <td class="item-name">{{ $product->name }}</td>
                    <td>
                        @if($product->pivot->color_id && isset($colors[$product->pivot->color_id]))
                            {{ $colors[$product->pivot->color_id]->name }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $product->pivot->size ?? '-' }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>{{ number_format($product->price, 2) }} جنيه</td>
                    <td>{{ number_format($product->price * $product->pivot->quantity, 2) }} جنيه</td>
                </tr>
                @endforeach
                
                <!-- Grand Total Row -->
                <tr class="grand-total-row">
                    <td class="item-name" colspan="3">المجموع الكلي</td>
                    <td>{{ $order->products->sum('pivot.quantity') }}</td>
                    <td>-</td>
                    <td>{{ number_format($order->total_price, 2) }} جنيه</td>
                    <td>-</td>
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