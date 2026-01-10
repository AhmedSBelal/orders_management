{{-- resource/views/products/index.blade.php --}}

@extends('layout.app-dashboard')

@section('title', $title)

@push('css')
    <style>
        .hidden {
            display: none;
        }

        .card-dashboard {
            transition: transform 0.2s;
            cursor: pointer;
        }

        .card-dashboard:hover {
            transform: translateY(-5px);
        }

        .card-dashboard .icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .search-form {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
        }

        .search-form .form-control {
            border-radius: 0.25rem;
            border: 1px solid #dee2e6;
        }

        .search-form .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            direction: rtl;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            text-align: right;
            min-width: 100px;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
            transition: all 0.2s;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
        }

        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 1.5rem;
            height: 1.5rem;
            margin: -0.75rem 0 0 -0.75rem;
            border: 2px solid #fff;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .search-form .col-md-2 {
                margin-bottom: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .table-responsive {
                margin: 0;
            }

            .table {
                min-width: 1000px;
            }
        }

        .pagination {
            margin-top: 1.5rem;
            justify-content: center;
            gap: 0.5rem;
        }

        .pagination .page-link {
            color: #0d6efd;
            border: 1px solid #dee2e6;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
            min-width: 2.5rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #0a58ca;
            transform: translateY(-1px);
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            padding: 1rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            color: #212529;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">إضافة وإنشاء منتج</p>
                                </div>
                            </div>
                            <div class="col-4 text-start">
                                <a href="{{route('products.create')}}">
                                    <div class="icon icon-shape bg-primary shadow text-center border-radius-md">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <!-- Search Form -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">البحث عن المنتج</h3>
                    </div>
                    <form action="" method="GET">
                        @csrf
                        <div class="card-body row">
                            <div class="col-md-2 mb-3">
                                <label class="form-label">اسم المنتج</label>
                                <input type="text" class="form-control" name="name" placeholder="اسم المنتج" value="{{request('name')}}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">وصف المنتج</label>
                                <input type="text" class="form-control" name="description" placeholder="وصف المنتج" value="{{request('description')}}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">سعر القطاعي</label>
                                <input type="text" class="form-control" name="price" placeholder="سعر القطاعي" value="{{request('price')}}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">سعر الجمله</label>
                                <input type="text" class="form-control" name="wholesale_price" placeholder="سعر الجمله" value="{{request('wholesale_price')}}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">تكلفه المنتج</label>
                                <input type="text" class="form-control" name="cost" placeholder="تكلفه المنتج" value="{{request('cost')}}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">نوع المنتج</label>
                                <input type="text" class="form-control" name="type" placeholder="نوع المنتج" value="{{request('type')}}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">اسم الخياط</label>
                                <input type="text" class="form-control" name="tailor_name" placeholder="اسم الخياط" value="{{request('tailor_name')}}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">تاريخ الاضافة</label>
                                <input type="date" class="form-control" name="created_at" value="{{request('created_at')}}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">تاريخ التعديل</label>
                                <input type="date" class="form-control" name="updated_at" value="{{request('updated_at')}}">
                            </div>
                            <div class="form-group col-md-3 mb-3 d-flex align-items-end gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                                <a href="{{route('products.index')}}" class="btn btn-success">
                                    <i class="fas fa-sync-alt"></i> إعادة ضبط
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>جدول المنتجات</h6>
                        <div>
                            <span class="badge bg-info me-2">
                                <i class="fas fa-box"></i> {{ $products->total() }} منتج
                            </span>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الصورة</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">اسم المنتج</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">السعر القطاعي</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">سعر الجمله</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">نسبة الخصم</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">التكلفة</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الربحية</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الإجراءات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $loop->iteration + (($products->currentPage() - 1) * $products->perPage()) }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $product->images->first()->photo_path) }}" 
                                                    alt="{{ $product->name }}" 
                                                    width="50" 
                                                    height="50" 
                                                    class="rounded-circle border"
                                                    style="object-fit: cover; cursor: pointer;" 
                                                    onclick="showImageModal('{{ asset('storage/' . $product->images->first()->photo_path) }}', '{{ $product->name }}')">
                                            @else
                                                <div class="icon icon-shape bg-secondary rounded-circle text-center">
                                                    <i class="fas fa-image text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $product->name }}</h6>
                                                @if($product->type)
                                                    <span class="badge bg-light text-dark mt-1">{{ $product->type }}</span>
                                                @endif
                                                @if($product->tailor_name)
                                                    <small class="text-muted">الخياط: {{ $product->tailor_name }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ number_format($product->price, 2) }} ج
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($product->wholesale_price)
                                                <span class="text-success text-xs font-weight-bold">
                                                    {{ number_format($product->wholesale_price, 2) }} ج
                                                </span>
                                                @if($product->wholesale_price < $product->price)
                                                    <span class="badge bg-success badge-sm">
                                                        <i class="fas fa-users"></i>
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-muted text-xs">
                                                    {{ number_format($product->price, 2) }} ج
                                                    <small class="d-block">(نفس القطاعي)</small>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($product->wholesale_price && $product->price > 0)
                                                @php
                                                    $discountPercentage = (($product->price - $product->wholesale_price) / $product->price) * 100;
                                                @endphp
                                                @if($discountPercentage > 0)
                                                    <span class="badge bg-success text-white">
                                                        {{ number_format($discountPercentage, 1) }}%
                                                    </span>
                                                @else
                                                    <span class="text-muted">0%</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ number_format($product->cost, 2) }} ج
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            @php
                                                $profitMarginRetail = $product->price - $product->cost;
                                                $profitMarginWholesale = $product->wholesale_price ? ($product->wholesale_price - $product->cost) : 0;
                                            @endphp
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="text-success text-xs">
                                                    قطاعي: {{ number_format($profitMarginRetail, 2) }} ج
                                                </span>
                                                @if($product->wholesale_price)
                                                    <span class="text-info text-xs">
                                                        جمله: {{ number_format($profitMarginWholesale, 2) }} ج
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-success btn-sm" href="{{route('products.edit', $product->id)}}" title="تعديل">
                                                    تعديل
                                                </a>
                                                <form action="{{route('products.destroy', $product->id)}}" method="POST" 
                                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                                        حذف
                                                    </button>
                                                </form>
                                                <button class="btn btn-info btn-sm" 
                                                        onclick="showProductDetails({{ $product->id }})"
                                                        title="تفاصيل">
                                                    مشاهده
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-box-open fa-3x"></i>
                                                <h5 class="mt-3">لا توجد منتجات</h5>
                                                <p class="text-muted">لم يتم العثور على منتجات تطابق معايير البحث</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($products->hasPages())
                            <div class="p-3">
                                {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Image Preview -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">صورة المنتج</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Product Details -->
    <div class="modal fade" id="productDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تفاصيل المنتج</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="productDetailsContent">
                    <!-- Content will be loaded via AJAX -->
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
    
    function showProductDetails(productId) {
        document.getElementById('productDetailsContent').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <p class="mt-2">جاري تحميل تفاصيل المنتج...</p>
            </div>
        `;
        
        fetch(`/products/${productId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('productDetailsContent').innerHTML = data.html;
                } else {
                    document.getElementById('productDetailsContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('productDetailsContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> حدث خطأ أثناء تحميل البيانات
                    </div>
                `;
                console.error('Error:', error);
            });
        
        const detailsModal = new bootstrap.Modal(document.getElementById('productDetailsModal'));
        detailsModal.show();
    }
</script>
@endpush