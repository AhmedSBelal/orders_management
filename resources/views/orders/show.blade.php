{{-- views/orders/show.blade.php --}}
@extends('layout.app-dashboard')

@section('title')
    {{ $title }}
@endsection

@push('css')
    <style>
        .order-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .order-image:hover {
            transform: scale(1.05);
        }

        .status-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .info-label {
            font-weight: 600;
            color: #344767;
        }

        .info-value {
            color: #67748e;
        }

        .product-item {
            transition: all 0.3s ease;
        }

        .product-item:hover {
            background-color: #f8f9fa;
        }

        /* Modal للصور */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .image-modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        /* تصميم بادج نوع الطلب */
        .order-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .order-type-wholesale {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .order-type-retail {
            background: linear-gradient(45deg, #0d6efd, #0dcaf0);
            color: white;
        }

        /* تصميم للأسعار */
        .price-tag {
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .price-wholesale {
            background-color: #d4edda;
            color: #155724;
        }

        .price-retail {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* بطاقة الملخص المالي */
        .summary-card {
            border-left: 4px solid #0d6efd;
        }

        .summary-wholesale {
            border-left-color: #28a745;
        }

        .summary-retail {
            border-left-color: #0d6efd;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">تفاصيل الطلب #{{ $order->id }}</h5>
                                <p class="text-sm mb-0 text-secondary">تم الإنشاء في:
                                    {{ $order->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <!-- بادج نوع الطلب -->
                                <div class="order-type-badge {{ $order->is_wholesale ? 'order-type-wholesale' : 'order-type-retail' }}">
                                    <i class="fas {{ $order->is_wholesale ? 'fa-users' : 'fa-user' }}"></i>
                                    {{ $order->is_wholesale ? 'طلب جمله' : 'طلب قطاعي' }}
                                </div>
                                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary btn-sm mb-0">
                                    <i class="fas fa-edit me-1"></i> تعديل
                                </a>
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                                    <i class="fas fa-arrow-right me-1"></i> رجوع
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- معلومات العميل والطلب -->
            <div class="col-lg-8">
                <!-- بيانات العميل -->
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>بيانات العميل</h6>
                        <div>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $order->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="text-sm mb-1 info-label">اسم العميل</p>
                                <p class="text-sm info-value">{{ $order->client_name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-sm mb-1 info-label">رقم الهاتف</p>
                                <p class="text-sm info-value">{{ $order->client_phone }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-sm mb-1 info-label">العنوان</p>
                                <p class="text-sm info-value">{{ $order->location }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-sm mb-1 info-label">المدينة</p>
                                <p class="text-sm info-value">{{ $order->city }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تفاصيل الطلب -->
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>تفاصيل الطلب</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="text-sm mb-1 info-label">مصدر الطلب</p>
                                <p class="text-sm info-value">{{ $order->come_from ?: 'غير محدد' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-sm mb-1 info-label">المستخدم المسؤول</p>
                                <p class="text-sm info-value">{{ $order->user->f_name }} {{ $order->user->l_name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-sm mb-1 info-label">حالة الطلب</p>
                                <span class="badge status-badge bg-gradient-{{ $order->status->name == 'قيد التنفيذ' ? 'warning' : ($order->status->name == 'مكتمل' ? 'success' : 'info') }}">
                                    {{ $order->status->name ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-sm mb-1 info-label">نوع الطلب</p>
                                <div class="d-flex align-items-center">
                                    @if($order->is_wholesale)
                                        <span class="badge bg-success me-2">
                                            <i class="fas fa-users me-1"></i> جمله
                                        </span>
                                        <small class="text-muted">(سعر جمله لكل منتج)</small>
                                    @else
                                        <span class="badge bg-info me-2">
                                            <i class="fas fa-user me-1"></i> قطاعي
                                        </span>
                                        <small class="text-muted">(سعر قطاعي لكل منتج)</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-sm mb-1 info-label">تاريخ آخر تحديث</p>
                                <p class="text-sm info-value">{{ $order->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                            @if($order->notes)
                                <div class="col-12 mb-3">
                                    <p class="text-sm mb-1 info-label">ملاحظات</p>
                                    <div class="border rounded p-3 bg-light">
                                        <p class="text-sm info-value mb-0">{{ $order->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- المنتجات -->
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>المنتجات</h6>
                        <div>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-box me-1"></i>
                                {{ count($products) }} منتج
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            المنتج</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            اللون</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            المقاس</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            الكمية</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            السعر</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            المجموع</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        @php
                                            // 🟢 **الآن هذه البيانات موجودة مباشرة في $product**
                                            $originalPrice = $product['original_price'];
                                            $wholesalePrice = $product['wholesale_price'];
                                            $isWholesalePrice = $product['is_wholesale_price'];
                                        @endphp
                                        <tr class="product-item">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $product['name'] }}</h6>
                                                        @if($isWholesalePrice)
                                                            <small class="text-success">
                                                                <i class="fas fa-tag me-1"></i> سعر جمله
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $product['color_name'] }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $product['size'] ?: '-' }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $product['quantity'] }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        {{ number_format($product['price'], 2) }} ج
                                                    </span>
                                                    @if($isWholesalePrice && $originalPrice != $product['price'])
                                                        <small class="text-success">
                                                            <del class="text-muted">{{ number_format($originalPrice, 2) }} ج</del>
                                                            <span class="text-success ms-1">
                                                                (خصم {{ $originalPrice > 0 ? round((($originalPrice - $product['price']) / $originalPrice) * 100, 2) : 0 }}%)
                                                            </span>
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ number_format($product['subtotal'], 2) }} ج</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($product['is_done'])
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <i class="fas fa-check me-1"></i> منجز
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-secondary">
                                                        <i class="fas fa-clock me-1"></i> قيد التنفيذ
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <!-- في جدول المنتجات - قسم tfoot -->
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">
                                            المجموع الكلي للمنتجات:
                                            @if($order->is_wholesale)
                                                <br>
                                                <small class="text-muted fw-normal">
                                                    <del>{{ number_format($totalRetailPrice, 2) }} ج</del>
                                                    <span class="text-success">(سعر قطاعي)</span>
                                                </small>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold text-primary">
                                            {{ number_format($totalSubtotal, 2) }} ج
                                            @if($order->is_wholesale && isset($totalRetailPrice))
                                                <br>
                                                <small class="text-success">
                                                    خصم: -{{ number_format($totalRetailPrice - $totalSubtotal, 2) }} ج
                                                    ({{ $totalRetailPrice > 0 ? round((($totalRetailPrice - $totalSubtotal) / $totalRetailPrice) * 100, 2) : 0 }}%)
                                                </small>
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- صور الطلب -->
                @if($order->images->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6>صور الطلب</h6>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-image me-1"></i>
                                {{ $order->images->count() }} صور
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($order->images as $image)
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $image->photo_path) }}" 
                                                 alt="Order Image" 
                                                 class="order-image"
                                                 onclick="openImageModal('{{ asset('storage/' . $image->photo_path) }}')">
                                            <span class="badge bg-dark position-absolute bottom-0 start-0 m-2">
                                                #{{ $loop->iteration }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- ملخص المالي -->
            <div class="col-lg-4">


                    <div class="card {{ $order->is_wholesale ? 'summary-wholesale' : 'summary-retail' }}">
                        <div class="card-header pb-0">
                            <h6 class="d-flex justify-content-between align-items-center">
                                <span>الملخص المالي</span>
                                @if($order->is_wholesale)
                                    <span class="badge bg-success">
                                        <i class="fas fa-users me-1"></i> جمله
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="fas fa-user me-1"></i> قطاعي
                                    </span>
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- نوع الطلب والخصم -->
                            @if($order->is_wholesale)
                                <div class="alert alert-success alert-sm mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <div>
                                            <strong>طلب جمله</strong>
                                            <p class="mb-0 small">جميع المنتجات بأسعار الجمله المخفضة</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- 🟢 عرض السعر القطاعي إذا كان طلب جملة -->
                            @if($order->is_wholesale && isset($totalRetailPrice))
                                <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                                    <span class="text-sm info-label">المجموع الكلي (قطاعي):</span>
                                    <span class="text-sm font-weight-bold text-muted">
                                        <del>{{ number_format($totalRetailPrice, 2) }} ج</del>
                                    </span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <span class="text-sm info-label">خصم الجملة:</span>
                                    <span class="text-sm font-weight-bold text-success">
                                        -{{ number_format($totalRetailPrice - $totalSubtotal, 2) }} ج
                                        <small class="text-muted">
                                            ({{ $totalRetailPrice > 0 ? round((($totalRetailPrice - $totalSubtotal) / $totalRetailPrice) * 100, 2) : 0 }}%)
                                        </small>
                                    </span>
                                </div>
                            @endif

                            <!-- 🟢 هذا هو السعر الفعلي (بعد خصم الجملة إذا وجد) -->
                            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                <span class="text-sm info-label">
                                    @if($order->is_wholesale && isset($totalRetailPrice))
                                        المجموع بعد خصم الجملة:
                                    @else
                                        المجموع الكلي:
                                    @endif
                                </span>
                                <span class="text-sm font-weight-bold text-primary">
                                    {{ number_format($totalSubtotal, 2) }} ج
                                </span>
                            </div>
                            
                            <!-- 🟢 الخصم الإضافي يجب أن يحسب من $totalSubtotal -->
                            @if($order->total_price_after_discount > 0 && $order->total_price_after_discount != $totalSubtotal)
                                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <span class="text-sm info-label">الخصم الإضافي:</span>
                                    <span class="text-sm font-weight-bold text-success">
                                        -{{ number_format($totalSubtotal - $order->total_price_after_discount, 2) }} ج
                                        <small class="text-muted">
                                            ({{ $totalSubtotal > 0 ? round((($totalSubtotal - $order->total_price_after_discount) / $totalSubtotal) * 100, 2) : 0 }}%)
                                        </small>
                                    </span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <span class="text-sm info-label">السعر النهائي:</span>
                                    <span class="text-sm font-weight-bold text-primary">
                                        {{ number_format($order->total_price_after_discount, 2) }} ج
                                    </span>
                                </div>
                            @endif
                            
                            <!-- باقي الكود كما هو -->
                            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                <span class="text-sm info-label">المبلغ المدفوع:</span>
                                <span class="text-sm font-weight-bold text-success">
                                    {{ number_format($order->deposited, 2) }} ج
                                </span>
                            </div>
                            
                            <hr class="horizontal dark">
                            
                            @php
                                // 🟢 استخدام $totalSubtotal بدلاً من $order->total_price
                                $finalPrice = $order->total_price_after_discount > 0 ? $order->total_price_after_discount : $totalSubtotal;
                                $remainingAmount = $finalPrice - $order->deposited;
                                $paymentPercentage = $finalPrice > 0 ? ($order->deposited / $finalPrice) * 100 : 0;
                            @endphp
                            
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-sm info-label">المبلغ المتبقي:</span>
                                <span class="text-sm font-weight-bold {{ $remainingAmount > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($remainingAmount, 2) }} ج
                                </span>
                            </div>
                            
                            <!-- شريط تقدم الدفع -->
                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">نسبة السداد</small>
                                    <small class="text-muted">{{ round($paymentPercentage, 1) }}%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $paymentPercentage == 100 ? 'bg-success' : ($paymentPercentage > 50 ? 'bg-warning' : 'bg-danger') }}" 
                                        role="progressbar" 
                                        style="width: {{ $paymentPercentage }}%;" 
                                        aria-valuenow="{{ $paymentPercentage }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">مدفوع: {{ number_format($order->deposited, 2) }} ج</small>
                                    <small class="text-muted">الإجمالي: {{ number_format($finalPrice, 2) }} ج</small>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- إجراءات سريعة -->
                <div class="card mt-4">
                    <div class="card-header pb-0">
                        <h6>إجراءات سريعة</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>تعديل الطلب
                            </a>
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i>حذف الطلب
                                </button>
                            </form>
                            <a href="{{ route('invoice.print', $order->id) }}" class="btn btn-info">
                                <i class="fas fa-print me-2"></i>طباعة الفاتورة
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ملخص سريع -->
                <div class="card mt-4">
                    <div class="card-header pb-0">
                        <h6>ملخص سريع</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-muted">عدد المنتجات:</span>
                                <span class="text-sm font-weight-bold">{{ count($products) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-muted">الكمية الإجمالية:</span>
                                <span class="text-sm font-weight-bold">{{ $products->sum('quantity') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-muted">المنتجات المنجزة:</span>
                                <span class="text-sm font-weight-bold">
                                    {{ $products->where('is_done', true)->count() }}
                                    / {{ count($products) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-muted">متوسط سعر المنتج:</span>
                                <span class="text-sm font-weight-bold">
                                    {{ count($products) > 0 ? number_format($products->avg('price'), 2) : 0 }} ج
                                    @if($order->is_wholesale)
                                        <br>
                                        <small class="text-muted">
                                            <del>{{ count($products) > 0 ? number_format($products->avg('original_price'), 2) : 0 }} ج</del>
                                        </small>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal للصور -->
    <div id="imageModal" class="image-modal" onclick="closeImageModal()">
        <span class="close-modal">&times;</span>
        <img class="image-modal-content" id="modalImage">
    </div>
@endsection

@push('js')
    <script>
        function openImageModal(imageSrc) {
            document.getElementById('imageModal').style.display = 'block';
            document.getElementById('modalImage').src = imageSrc;
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });

        // نسخ رابط الطلب
        function copyOrderLink() {
            const orderUrl = window.location.href;
            navigator.clipboard.writeText(orderUrl).then(() => {
                alert('تم نسخ رابط الطلب إلى الحافظة');
            }).catch(err => {
                console.error('فشل نسخ الرابط: ', err);
            });
        }

        // إظهار رسالة تأكيد عند مغادرة الصفحة إذا كان هناك تغييرات غير محفوظة
        window.addEventListener('beforeunload', function (e) {
            // يمكنك إضافة منطق للتحقق من وجود تغييرات غير محفوظة
        });
    </script>
@endpush