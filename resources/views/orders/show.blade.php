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
        background-color: rgba(0,0,0,0.9);
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
                            <p class="text-sm mb-0 text-secondary">تم الإنشاء في: {{ $order->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="d-flex gap-2">
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
                <div class="card-header pb-0">
                    <h6>بيانات العميل</h6>
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
                            <p class="text-sm info-value">{{ $order->come_from }}</p>
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
                            <p class="text-sm mb-1 info-label">تاريخ آخر تحديث</p>
                            <p class="text-sm info-value">{{ $order->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                        @if($order->notes)
                        <div class="col-12 mb-3">
                            <p class="text-sm mb-1 info-label">ملاحظات</p>
                            <p class="text-sm info-value">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- المنتجات -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>المنتجات</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المنتج</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">اللون</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المقاس</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الكمية</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">السعر</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المجموع</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr class="product-item">
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $product['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $product['color_name'] }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $product['size'] }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $product['quantity'] }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ number_format($product['price'], 2) }} جنيه</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ number_format($product['subtotal'], 2) }} جنيه</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($product['is_done'])
                                            <span class="badge badge-sm bg-gradient-success">منجز</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">قيد التنفيذ</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- صور الطلب -->
            @if($order->images->count() > 0)
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>صور الطلب</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($order->images as $image)
                        <div class="col-md-4 col-sm-6 mb-3">
                            <img src="{{ asset('storage/' . $image->photo_path) }}" 
                                 alt="Order Image" 
                                 class="order-image"
                                 onclick="openImageModal('{{ asset('storage/' . $image->photo_path) }}')">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- ملخص المالي -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>الملخص المالي</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-sm info-label">المجموع الكلي:</span>
                        <span class="text-sm font-weight-bold">{{ number_format($order->total_price, 2) }} جنيه</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-sm info-label">المبلغ المدفوع:</span>
                        <span class="text-sm font-weight-bold text-success">{{ number_format($order->deposited, 2) }} جنيه</span>
                    </div>
                    @if($order->total_price_after_discount > 0)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-sm info-label">السعر بعد الخصم:</span>
                        <span class="text-sm font-weight-bold">{{ number_format($order->total_price_after_discount, 2) }} جنيه</span>
                    </div>
                    @endif
                    <hr class="horizontal dark">
                    <div class="d-flex justify-content-between">
                        <span class="text-sm info-label">المبلغ المتبقي:</span>
                        <span class="text-sm font-weight-bold text-danger">
                            {{ number_format(($order->total_price_after_discount > 0 ? $order->total_price_after_discount : $order->total_price) - $order->deposited, 2) }} جنيه
                        </span>
                    </div>
                </div>
            </div>

            <!-- إجراءات سريعة -->
            <div class="card mt-4">
                <div class="card-header pb-0">
                    <h6>إجراءات سريعة</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>تعديل الطلب
                    </a>
                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>حذف الطلب
                        </button>
                    </form>
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
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endpush