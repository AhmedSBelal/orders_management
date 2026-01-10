{{-- resources/views/products/show.blade.php --}}

@extends('layout.app-dashboard')

@section('title', $title)

@push('css')
<style>
    .product-image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .product-image-item {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .product-image-item:hover {
        transform: scale(1.05);
        cursor: pointer;
    }

    .product-image-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .product-info-card {
        background: #fff;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #666;
    }

    .info-value {
        color: #333;
        font-weight: 500;
    }

    .price-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .wholesale-price-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .profit-card {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .no-images {
        text-align: center;
        padding: 3rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
        color: #6c757d;
    }

    .action-buttons-show {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .product-image-gallery {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }
        
        .stat-number {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb and Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">تفاصيل المنتج</h3>
                    <p class="text-muted mb-0">عرض كافة معلومات المنتج</p>
                </div>
                <div class="action-buttons-show">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> العودة للقائمة
                    </a>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" 
                        onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>صور المنتج</h6>
                </div>
                <div class="card-body">
                    @if($product->images->isNotEmpty())
                        <div class="product-image-gallery">
                            @foreach($product->images as $image)
                                <div class="product-image-item" 
                                    onclick="showImageModal('{{ asset('storage/' . $image->photo_path) }}', '{{ $product->name }}')">
                                    <img src="{{ asset('storage/' . $image->photo_path) }}" 
                                        alt="{{ $product->name }}">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-images">
                            <i class="fas fa-images fa-3x mb-3"></i>
                            <h5>لا توجد صور لهذا المنتج</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Stats -->
        <div class="col-lg-4 mb-4">
            <!-- Retail Price -->
            <div class="price-card">
                <div class="stat-label">السعر القطاعي</div>
                <div class="stat-number">{{ number_format($product->price, 2) }} ج</div>
            </div>

            <!-- Wholesale Price -->
            @if($product->wholesale_price)
                <div class="wholesale-price-card">
                    <div class="stat-label">سعر الجملة</div>
                    <div class="stat-number">{{ number_format($product->wholesale_price, 2) }} ج</div>
                    @if($product->wholesale_price < $product->price)
                        @php
                            $discount = (($product->price - $product->wholesale_price) / $product->price) * 100;
                        @endphp
                        <div class="stat-label">خصم {{ number_format($discount, 1) }}%</div>
                    @endif
                </div>
            @endif

            <!-- Profit -->
            <div class="profit-card">
                <div class="stat-label">الربح القطاعي</div>
                <div class="stat-number">{{ number_format($product->price - $product->cost, 2) }} ج</div>
                @if($product->wholesale_price)
                    <div class="stat-label">الربح بالجملة: {{ number_format($product->wholesale_price - $product->cost, 2) }} ج</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Information -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>المعلومات الأساسية</h6>
                </div>
                <div class="card-body">
                    <div class="product-info-card">
                        <div class="info-row">
                            <span class="info-label">اسم المنتج</span>
                            <span class="info-value">{{ $product->name }}</span>
                        </div>
                        @if($product->description)
                            <div class="info-row">
                                <span class="info-label">الوصف</span>
                                <span class="info-value">{{ $product->description }}</span>
                            </div>
                        @endif
                        @if($product->type)
                            <div class="info-row">
                                <span class="info-label">النوع</span>
                                <span class="info-value">
                                    <span class="badge bg-info">{{ $product->type }}</span>
                                </span>
                            </div>
                        @endif
                        @if($product->tailor_name)
                            <div class="info-row">
                                <span class="info-label">اسم الخياط</span>
                                <span class="info-value">{{ $product->tailor_name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>التكاليف والأسعار</h6>
                </div>
                <div class="card-body">
                    <div class="product-info-card">
                        <div class="info-row">
                            <span class="info-label">التكلفة</span>
                            <span class="info-value">{{ number_format($product->cost, 2) }} ج</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">السعر القطاعي</span>
                            <span class="info-value">{{ number_format($product->price, 2) }} ج</span>
                        </div>
                        @if($product->wholesale_price)
                            <div class="info-row">
                                <span class="info-label">سعر الجملة</span>
                                <span class="info-value">{{ number_format($product->wholesale_price, 2) }} ج</span>
                            </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">هامش الربح القطاعي</span>
                            <span class="info-value text-success">
                                {{ number_format($product->price - $product->cost, 2) }} ج
                            </span>
                        </div>
                        @if($product->wholesale_price)
                            <div class="info-row">
                                <span class="info-label">هامش الربح بالجملة</span>
                                <span class="info-value text-success">
                                    {{ number_format($product->wholesale_price - $product->cost, 2) }} ج
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>معلومات إضافية</h6>
                </div>
                <div class="card-body">
                    <div class="product-info-card">
                        <div class="info-row">
                            <span class="info-label">تاريخ الإضافة</span>
                            <span class="info-value">{{ $product->created_at->format('Y-m-d h:i A') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">آخر تحديث</span>
                            <span class="info-value">{{ $product->updated_at->format('Y-m-d h:i A') }}</span>
                        </div>
                        @if($product->user)
                            <div class="info-row">
                                <span class="info-label">تم الإضافة بواسطة</span>
                                <span class="info-value">{{ $product->user->f_name }} {{ $product->user->l_name }}</span>
                            </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">عدد الصور</span>
                            <span class="info-value">
                                <span class="badge bg-primary">{{ $product->images->count() }} صورة</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Image Preview -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">صورة المنتج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid rounded" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function showImageModal(imageSrc, productName) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModalTitle').textContent = 'صورة المنتج: ' + productName;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }
</script>
@endpush