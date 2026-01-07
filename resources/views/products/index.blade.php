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

        .status-badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            text-transform: capitalize;
        }

        .status-تحت-التجهيز {
            background-color: #0dcaf0;
            color: #000;
        }

        .status-فى-المصنع {
            background-color: #6f42c1;
            color: #fff;
        }

        .status-فى-المصنع-2 {
            background-color: #6f42c1;
            color: #fff;
        }

        .status-فى-المصنع-3 {
            background-color: #6f42c1;
            color: #fff;
        }

        .status-تم-التجهيز {
            background-color: #198754;
            color: #fff;
        }

        .status-تم-الشحن {
            background-color: #20c997;
            color: #000;
        }

        .status-مرتجع {
            background-color: #fd7e14;
            color: #000;
        }

        .status-الغاء {
            background-color: #dc3545;
            color: #fff;
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

        .pagination .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            z-index: 3;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 0.25rem;
        }

        @media (max-width: 576px) {
            .pagination {
                flex-wrap: wrap;
            }

            .pagination .page-link {
                padding: 0.375rem 0.75rem;
                min-width: 2rem;
            }
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

        .modal-footer {
            border-top: 1px solid #dee2e6;
            padding: 1rem;
        }

        .modal-title {
            font-weight: 600;
            color: #212529;
        }

        .btn-close {
            padding: 0.5rem;
            margin: -0.5rem;
        }

        /* Column specific widths */
        .table .number-column {
            width: 80px;
            min-width: 80px;
        }

        .table .checkbox-column {
            width: 50px;
            min-width: 50px;
        }

        .table .location-column {
            width: 150px;
            min-width: 150px;
        }

        .table .name-column {
            width: 150px;
            min-width: 150px;
        }

        .table .phone-column {
            width: 120px;
            min-width: 120px;
        }

        .table .status-column {
            width: 120px;
            min-width: 120px;
        }

        .table .price-column {
            width: 100px;
            min-width: 100px;
        }

        .table .deposit-column {
            width: 100px;
            min-width: 100px;
        }

        .table .actions-column {
            width: 200px;
            min-width: 200px;
        }

        /* Bulk actions styles */
        .bulk-actions {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: none;
        }

        .bulk-actions.show {
            display: block;
        }

        .custom-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .selected-count {
            background-color: #0d6efd;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            margin-left: 0.5rem;
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
                                <label class="form-label">سعر المنتج</label>
                                <input type="text" class="form-control" name="price" placeholder="سعر المنتج" value="{{request('price')}}">
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
                                <button class="btn btn-primary" type="submit">Search</button>
                                <a href="{{route('products.index')}}" class="btn btn-success">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
                    <div class="card-header pb-0">
                        <h6>جدول المنتجات</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الرقم</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">صورة المنتج</th> {{-- العمود الجديد --}}
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">اسم المنتج</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">سعر المنتج</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">عمليات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="d-flex px-2 py-1">{{Str::limit($loop->iteration, 20)}}</div>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $product->images->first()->photo_path) }}" alt="{{ $product->name }}" width="50" height="50" class="rounded" style="object-fit: cover;">
                                            @else
                                                <span class="text-muted">لا توجد صور</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            {{Str::limit($product->name, 20)}}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{Str::limit($product->price, 20)}}
                                        </td>
                                        <td class="align-middle text-center d-flex p-2 justify-content-center">
                                            <a class="btn btn-success ms-2" href="{{route('products.edit', $product->id)}}">Edit</a>
                                            <form action="{{route('products.destroy', $product->id)}}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($products->hasPages())
                            <div class="p-3">
                                {{ $products->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection