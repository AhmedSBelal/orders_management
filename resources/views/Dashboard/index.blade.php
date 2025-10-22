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
    <!-- Modal Structure -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                    <p class="text-lg mb-4">هل انت متأكد من حذف هذا الاوردر؟</p>
                    <p class="text-muted">لا يمكن استرجاع البيانات بعد الحذف</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>حذف
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
                <div class="card card-dashboard">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">إضافة وإنشاء طلبية</p>
                                </div>
                            </div>
                            <div class="col-4 text-start">
                                <a href="{{route('orders.create')}}">
                                    <div class="icon icon-shape bg-primary shadow text-center border-radius-md">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
                <div class="card card-dashboard">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">طلبيات تحت التجهيز</p>
                                </div>
                            </div>
                            <div class="col-4 text-start">
                                <a href="{{route('in-processing')}}">
                                    <div class="icon icon-shape bg-primary shadow text-center border-radius-md">
                                        <i class="fas fa-cogs text-white"></i>
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
                        <h3 class="card-title">Search Order</h3>
                    </div>
                    <form action="{{ route('orders.index') }}" method="GET" id="searchForm">
                        @csrf
                        <div class="card-body search-form">
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">اسم المرسل اليه</label>
                                    <input type="text" class="form-control" name="client_name" placeholder="اسم المرسل اليه"
                                        value="{{request('client_name')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">العنوان</label>
                                    <input type="text" class="form-control" name="location" placeholder="العنوان"
                                        value="{{request('location')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control" name="client_phone" placeholder="رقم الهاتف"
                                        value="{{request('client_phone')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">حالة الطلب</label>
                                    <select name="status" class="form-control">
                                        <option value="">اختر حالة الطلب</option>
                                        @foreach(\App\Enums\OrderStatuses::cases() as $status)
                                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                                {{ $status->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">السعر</label>
                                    <input type="text" class="form-control" name="total_price" placeholder="السعر"
                                        value="{{request('total_price')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">المقدم</label>
                                    <input type="text" class="form-control" name="deposited" placeholder="المقدم"
                                        value="{{request('deposited')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">جاى عن طريق</label>
                                    <input type="text" class="form-control" name="come_from" placeholder="جاى عن طريق"
                                        value="{{request('come_from')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">حالة دفع الطلب</label>
                                    <select name="payment_status" class="form-control">
                                        <option value="">حالة دفع الطلب</option>
                                        <option value="تم الدفع" {{request('payment_status') == "تم الدفع" ? 'selected' : ''}}>تم الدفع</option>
                                        <option value="انتظار الدفع" {{request('payment_status') == "انتظار الدفع" ? 'selected' : ''}}>انتظار الدفع</option>
                                        <option value="لم يتم الدفع" {{request('payment_status') == "لم يتم الدفع" ? 'selected' : ''}}>لم يتم الدفع</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">تاريخ الاضافة</label>
                                    <input type="date" class="form-control" name="created_at"
                                        value="{{request('created_at')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">تاريخ التعديل</label>
                                    <input type="date" class="form-control" name="updated_at"
                                        value="{{request('updated_at')}}">
                                </div>
                                <div class="form-group col-md-3 mb-3 d-flex align-items-end gap-2">
                                    <button class="btn btn-primary" type="submit" id="searchButton">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    <a href="{{route('orders.index')}}" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Results per page dropdown -->
                    <div class="card-body search-form">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Results per page</label>
                                <select name="per_page" id="perPage" class="form-control" onchange="changePerPage()">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Orders Table</h6>
                    </div>

                    <!-- Bulk Actions Form -->
                    <div class="bulk-actions" id="bulkActions">
                        <form action="{{ route('orders.bulk-update-status') }}" method="POST" id="bulkUpdateForm">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <span>Selected: <span class="selected-count" id="selectedCount">0</span> orders</span>
                                </div>
                                <div class="col-md-4">
                                    <select name="status" class="form-control" required>
                                        <option value="">Change status to</option>
                                        @foreach(\App\Enums\OrderStatuses::cases() as $status)
                                            <option value="{{ $status->value }}">{{ $status->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-sync-alt me-2"></i>Update Status
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="clearSelection">
                                        <i class="fas fa-times me-2"></i>Clear Selection
                                    </button>
                                </div>
                            </div>
                            <!-- THE FIX IS HERE: Added [] to the name attribute -->
                            <div id="selectedOrderIdsContainer"></div>
                        </form>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="checkbox-column">
                                            <input type="checkbox" class="custom-checkbox" id="selectAll">
                                        </th>
                                        <th class="number-column">الرقم</th>
                                        <th class="name-column">اسم المرسل اليه</th>
                                        <th class="location-column">العنوان</th>
                                        <th class="phone-column">رقم الهاتف</th>
                                        <th class="status-column">حالة الطلب</th>
                                        <th class="price-column">السعر</th>
                                        <th class="actions-column">عمليات على الطلب</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td class="checkbox-column">
                                                <input type="checkbox" class="custom-checkbox order-checkbox"
                                                    value="{{ $order->id }}">
                                            </td>
                                            <td class="number-column">{{$orders->firstItem() + $loop->index}}</td>
                                            <td class="name-column">{{Str::limit($order->client_name, 20)}}</td>
                                            <td class="location-column">{{Str::limit($order->location, 20)}}</td>
                                            <td class="phone-column">{{Str::limit($order->client_phone, 20)}}</td>
                                            <td class="status-column">
                                                <span class="status-badge status-{{ str_replace(' ', '-', $order->status) }}">
                                                    {{$order->status}}
                                                </span>
                                            </td>
                                            <td class="price-column">{{$order->total_price}}</td>
                                            <td class="actions-column">
                                                <div class="action-buttons">
                                                    <a class="btn btn-success" href="{{route('orders.edit', $order->id)}}"
                                                        title="Edit Order">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-danger delete-btn"
                                                        data-order-id="{{ $order->id }}" title="Delete Order">
                                                        <i class="fas fa-trash"></i> حذف
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="empty-state">
                                                <i class="fas fa-box-open"></i>
                                                <p class="mb-0">No orders found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($orders->hasPages())
                            <div class="p-3">
                                {{ $orders->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let deleteFormId = null;
            let deleteForm = null;
            let confirmationModal = null;
            let selectedOrders = new Set();

            // Initialize the modal
            const modalElement = document.getElementById('confirmationModal');
            if (modalElement) {
                confirmationModal = new bootstrap.Modal(modalElement);
            }

            // Create delete form
            function createDeleteForm() {
                if (!deleteForm) {
                    deleteForm = document.createElement('form');
                    deleteForm.method = 'POST';
                    deleteForm.style.display = 'none';
                    document.body.appendChild(deleteForm);

                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = "{{ csrf_token() }}";
                    deleteForm.appendChild(csrfToken);

                    // Add method override for DELETE
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    deleteForm.appendChild(methodField);
                }
            }

            // Handle delete button clicks
            document.addEventListener('click', function (e) {
                if (e.target.closest('.delete-btn')) {
                    const orderId = e.target.closest('.delete-btn').dataset.orderId;
                    showConfirmationModal(orderId);
                }
            });

            // Show confirmation modal
            function showConfirmationModal(orderId) {
                deleteFormId = orderId;
                createDeleteForm();
                // Set the correct route for deletion
                deleteForm.action = `/orders/${orderId}`;

                if (confirmationModal) {
                    confirmationModal.show();
                }
            }

            // Handle confirm delete button click
            document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
                if (deleteFormId !== null && deleteForm) {
                    deleteForm.submit();
                }
            });

            // Add loading state to search form
            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function (e) {
                    const searchButton = document.getElementById('searchButton');
                    if (searchButton) {
                        searchButton.classList.add('loading');
                    }
                });
            }

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Handle responsive table
            function handleResponsiveTable() {
                const table = document.querySelector('.table-responsive');
                if (table) {
                    if (window.innerWidth < 768) {
                        table.style.margin = '0';
                    } else {
                        table.style.margin = '0';
                    }
                }
            }

            // Initial call
            handleResponsiveTable();

            // Add event listener for window resize
            window.addEventListener('resize', handleResponsiveTable);

            // Bulk selection functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const orderCheckboxes = document.querySelectorAll('.order-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            const selectedOrderIds = document.getElementById('selectedOrderIds');
            const clearSelectionBtn = document.getElementById('clearSelection');

            // Handle select all checkbox
            selectAllCheckbox.addEventListener('change', function () {
                const isChecked = this.checked;

                orderCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    const orderId = parseInt(checkbox.value);

                    if (isChecked) {
                        selectedOrders.add(orderId);
                    } else {
                        selectedOrders.delete(orderId);
                    }
                });

                updateBulkActions();
            });

            // Handle individual order checkboxes
            orderCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const orderId = parseInt(this.value);

                    if (this.checked) {
                        selectedOrders.add(orderId);
                    } else {
                        selectedOrders.delete(orderId);
                    }

                    updateBulkActions();
                    updateSelectAllCheckbox();
                });
            });

            // Update select all checkbox state
            function updateSelectAllCheckbox() {
                const totalCheckboxes = orderCheckboxes.length;
                const checkedCheckboxes = document.querySelectorAll('.order-checkbox:checked').length;

                selectAllCheckbox.checked = totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes;
                selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
            }

            // Update bulk actions visibility and selected count
            function updateBulkActions() {
                const count = selectedOrders.size;
                const container = document.getElementById('selectedOrderIdsContainer'); // Get the new container

                // Clear any existing hidden inputs from previous selections
                container.innerHTML = '';

                if (count > 0) {
                    bulkActions.classList.add('show');
                    selectedCount.textContent = count;

                    // Create a hidden input for EACH selected order ID
                    selectedOrders.forEach(orderId => {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'order_ids[]'; // The [] is crucial!
                        hiddenInput.value = orderId;
                        container.appendChild(hiddenInput);
                    });

                } else {
                    bulkActions.classList.remove('show');
                }
            }

            // Clear selection
            clearSelectionBtn.addEventListener('click', function () {
                selectedOrders.clear();
                orderCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                selectAllCheckbox.checked = false;
                updateBulkActions();
            });

            // Handle bulk update form submission
            const bulkUpdateForm = document.getElementById('bulkUpdateForm');
            bulkUpdateForm.addEventListener('submit', function (e) {
                if (selectedOrders.size === 0) {
                    e.preventDefault();
                    alert('Please select at least one order to update.');
                    return;
                }

                const statusSelect = this.querySelector('select[name="status"]');
                if (!statusSelect.value) {
                    e.preventDefault();
                    alert('Please select a status to update.');
                    return;
                }
            });
        });

        // Function to change results per page
        function changePerPage() {
            const perPage = document.getElementById('perPage').value;
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        }
    </script>
@endpush