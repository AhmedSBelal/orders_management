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
        
        /* أنماط جديدة */
        .order-type-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            margin-right: 5px;
        }
        .wholesale-badge {
            background-color: #28a745;
            color: white;
        }
        .retail-badge {
            background-color: #17a2b8;
            color: white;
        }
        .original-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 9px;
            display: block;
        }
        .wholesale-price {
            color: #28a745;
            font-weight: bold;
        }
        .discount-highlight {
            background-color: #fff3cd;
            border-left: 3px solid #ffc107;
        }
        .discount-row {
            background-color: #d4edda;
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
                    <div style="text-align: center; font-size: 13px; margin-top: 4px;">
                        فاتورة شراء 
                        @if($order->is_wholesale)
                            <span class="order-type-badge wholesale-badge">جمله</span>
                        @else
                            <span class="order-type-badge retail-badge">قطاعي</span>
                        @endif
                    </div>
                </td>
                <td style="width: 15%; text-align: left;">
                    <div class="invoice-details">
                        <div><strong>رقم الفاتورة:</strong> {{ $order->id }}</div>
                        <div><strong>التاريخ:</strong> {{ $order->created_at->locale('ar')->translatedFormat('d/m/Y') }}</div>
                        <div><strong>النوع:</strong> {{ $order->is_wholesale ? 'جمله' : 'قطاعي' }}</div>
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
                    $totalQuantity = 0;
                    $totalAmount = 0;
                    $totalOriginalAmount = 0;
                @endphp
                
                @foreach($order->products as $product)
                    @php
                        // استخدام السعر من pivot table (السعر المستخدم فعلياً)
                        $usedPrice = $product->pivot->price ?? $product->price;
                        $originalPrice = $product->price; // السعر القطاعي الأصلي
                        $wholesalePrice = $product->wholesale_price ?? $product->price; // سعر الجمله
                        
                        $subtotal = $usedPrice * $product->pivot->quantity;
                        $originalSubtotal = $originalPrice * $product->pivot->quantity;
                        
                        $totalQuantity += $product->pivot->quantity;
                        $totalAmount += $subtotal;
                        $totalOriginalAmount += $originalSubtotal;
                        
                        $isWholesaleOrder = $order->is_wholesale;
                        $hasDiscount = $order->total_price_after_discount > 0 && $order->total_price_after_discount != $order->total_price;
                    @endphp
                    
                    <tr @if($isWholesaleOrder && $usedPrice < $originalPrice) class="discount-highlight" @endif>
                        <td class="item-name">
                            {{ $product->name }}
                            @if($isWholesaleOrder && $usedPrice < $originalPrice)
                                <br><small class="text-success">(سعر جمله)</small>
                            @endif
                        </td>
                        <td>
                            @if($product->pivot->color_id && isset($colors[$product->pivot->color_id]))
                                {{ $colors[$product->pivot->color_id]->name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $product->pivot->size ?? '-' }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>
                            @if($isWholesaleOrder && $usedPrice < $originalPrice)
                                <span class="original-price">{{ $originalPrice}} ج</span>
                                <span class="wholesale-price">{{ $usedPrice}} ج</span>
                            @else
                                {{ $usedPrice}} ج
                            @endif
                        </td>
                        <td>{{ $subtotal }} ج</td>
                    </tr>
                @endforeach
                
                <!-- صف المجموع -->
                <tr class="grand-total-row">
                    <td class="item-name" colspan="3">المجموع الكلي</td>
                    <td>{{ $totalQuantity }}</td>
                    <td>-</td>
                    <td>{{$totalAmount }} ج</td>
                </tr>
                
                <!-- عرض السعر الأصلي إذا كان طلب جمله -->
                @if($isWholesaleOrder && $totalOriginalAmount > $totalAmount)
                    <tr>
                        <td colspan="3" class="text-end">
                            <strong>السعر الأصلي (قطاعي):</strong>
                        </td>
                        <td colspan="2"></td>
                        <td>
                            <span style="text-decoration: line-through; color: #6c757d;">
                                {{ $totalOriginalAmount }} ج
                            </span>
                        </td>
                    </tr>
                    <tr class="discount-row">
                        <td colspan="3" class="text-end">
                            <strong>الخصم:</strong>
                        </td>
                        <td colspan="2"></td>
                        <td>
                            <span class="text-success">
                                -{{ $totalOriginalAmount - $totalAmount }} ج
                                ({{ $totalOriginalAmount > 0 ? round((($totalOriginalAmount - $totalAmount) / $totalOriginalAmount) * 100, 2) : 0 }}%)
                            </span>
                        </td>
                    </tr>
                @endif
                
                <!-- عرض خصم إضافي إذا كان هناك total_price_after_discount -->
                @if($order->total_price_after_discount > 0 && $order->total_price_after_discount != $totalAmount)
                    <tr class="discount-row">
                        <td colspan="3" class="text-end">
                            <strong>الخصم الإضافي:</strong>
                        </td>
                        <td colspan="2"></td>
                        <td>
                            <span class="text-success">
                                -{{ $totalAmount - $order->total_price_after_discount }} ج
                                ({{ $totalAmount > 0 ? round((($totalAmount - $order->total_price_after_discount) / $totalAmount) * 100, 2) : 0 }}%)
                            </span>
                        </td>
                    </tr>
                    
                    <!-- السعر النهائي بعد الخصم الإضافي -->
                    <tr class="grand-total-row" style="background-color: #d4edda;">
                        <td colspan="3" class="text-end">
                            <strong>السعر النهائي بعد الخصم:</strong>
                        </td>
                        <td colspan="2"></td>
                        <td class="text-success">
                            {{$order->total_price_after_discount }} ج
                        </td>
                    </tr>
                @else
                    <!-- إذا لم يكن هناك خصم إضافي -->
                    <tr class="grand-total-row" style="background-color: #d4edda;">
                        <td colspan="3" class="text-end">
                            <strong>المبلغ الإجمالي:</strong>
                        </td>
                        <td colspan="2"></td>
                        <td>
                            {{ $totalAmount }} ج
                        </td>
                    </tr>
                @endif
                
                <!-- المقدم والمتبقي -->
                @if($order->deposited > 0)
                    <tr>
                        <td colspan="3" class="text-end">
                            <strong>المبلغ المدفوع:</strong>
                        </td>
                        <td colspan="2"></td>
                        <td class="text-success">
                            {{ $order->deposited }} ج
                        </td>
                    </tr>
                    
                    @php
                        $finalPrice = $order->total_price_after_discount > 0 ? $order->total_price_after_discount : $totalAmount;
                        $remainingAmount = $finalPrice - $order->deposited;
                    @endphp
                    
                    <tr>
                        <td colspan="3" class="text-end">
                            <strong>المبلغ المتبقي:</strong>
                        </td>
                        <td colspan="2"></td>
                        <td class="{{ $remainingAmount > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $remainingAmount }} ج
                        </td>
                    </tr>
                @endif
                
                <!-- ملاحظة خاصة بنوع الطلب -->
                <tr>
                    <td colspan="6" style="padding: 10px; text-align: center; font-size: 9px; background-color: #f8f9fa;">
                        @if($order->is_wholesale)
                            <strong>ملاحظة:</strong> هذه فاتورة جمله - جميع الأسعار هي أسعار جمله مخفضة
                        @else
                            <strong>ملاحظة:</strong> هذه فاتورة قطاعي - الأسعار هي أسعار القطاعي
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p><strong>شكراً لتعاملكم معنا!</strong></p>
            <p>أجياد مكة - جميع الحقوق محفوظة</p>
            <p style="font-size: 8px; color: #6c757d; margin-top: 5px;">
                تم إنشاء الفاتورة في: {{ now()->locale('ar')->translatedFormat('Y-m-d H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>