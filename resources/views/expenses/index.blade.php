{{-- resources/views/expenses/index.blade.php --}}
@extends('layout.app-dashboard')

@section('title', 'المصاريف')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>قائمة المصاريف</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('expense-categories.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list ms-1"></i> تصنيفات المصاريف
                        </a>
                        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus ms-1"></i> إضافة مصروف جديد
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-4 mt-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger mx-4 mt-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end pe-4">الرقم</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">الفئة</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">المبلغ</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">التاريخ</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">طريقة الدفع</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">الطلب</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">المنتج</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                    <tr>
                                        <td class="text-end pe-4">
                                            <p class="text-xs font-weight-bold mb-0">{{ $expense->id }}</p>
                                        </td>
                                        <td class="text-end">
                                            <p class="text-xs font-weight-bold mb-0">{{ $expense->category->name }}</p>
                                        </td>
                                        <td class="text-end">
                                            <p class="text-xs font-weight-bold mb-0">{{ number_format($expense->amount, 2) }} جنيه</p>
                                        </td>
                                        <td class="text-end">
                                            <p class="text-xs text-secondary mb-0">{{ $expense->expense_date->format('Y-m-d') }}</p>
                                        </td>
                                        <td class="text-end">
                                            <p class="text-xs text-secondary mb-0">{{ $expense->payment_method ?? 'غير محدد' }}</p>
                                        </td>
                                        <td class="text-end">
                                            @if($expense->order)
                                                <a href="{{ route('orders.show', $expense->order_id) }}" class="text-xs font-weight-bold text-primary">
                                                    #{{ $expense->order_id }}
                                                </a>
                                            @else
                                                <span class="text-xs text-secondary">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($expense->product)
                                                <p class="text-xs text-secondary mb-0">{{ $expense->product->name }}</p>
                                            @else
                                                <span class="text-xs text-secondary">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-sm btn-success" href="{{ route('expenses.edit', $expense->id) }}" title="تعديل المصروف">
                                                    <i class="fas fa-edit"></i> تعديل
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-expense-id="{{ $expense->id }}" title="حذف المصروف">
                                                    <i class="fas fa-trash"></i> حذف
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-inbox text-secondary mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                                                <p class="text-secondary mb-2">لا توجد مصاريف مسجلة</p>
                                                <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus ms-1"></i> إضافة أول مصروف
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($expenses->hasPages())
                        <div class="d-flex justify-content-center mt-4 px-4">
                            {{ $expenses->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mt-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold text-end">إجمالي المصاريف</p>
                                <h5 class="font-weight-bolder mb-0 text-end">
                                    {{ number_format($expenses->sum('amount'), 2) }} جنيه
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-start">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold text-end">عدد المصاريف</p>
                                <h5 class="font-weight-bolder mb-0 text-end">
                                    {{ $expenses->total() }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-start">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                <p class="mb-0">هل أنت متأكد من حذف هذا المصروف؟</p>
                <p class="text-muted small">لا يمكن التراجع عن هذا الإجراء</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">حذف</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .gap-2 {
        gap: 0.5rem !important;
    }
    
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }
    
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
    
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .modal-content {
        border-radius: 0.5rem;
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .modal-body {
        padding: 2rem 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }
</style>
@endpush

@push('js')
<script>
    let deleteFormId = null;
    let deleteForm = null;
    let confirmationModal = null;

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the modal
        const modalElement = document.getElementById('confirmationModal');
        if (modalElement) {
            confirmationModal = new bootstrap.Modal(modalElement);
        }

        // Create delete form
        createDeleteForm();

        // Handle delete button clicks
        document.addEventListener('click', function (e) {
            if (e.target.closest('.delete-btn')) {
                const expenseId = e.target.closest('.delete-btn').dataset.expenseId;
                showConfirmationModal(expenseId);
            }
        });

        // Handle confirm delete button click
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                if (deleteFormId !== null && deleteForm) {
                    deleteForm.submit();
                }
            });
        }
    });

    // Create delete form
    function createDeleteForm() {
        if (!deleteForm) {
            deleteForm = document.createElement('form');
            deleteForm.method = 'POST';
            deleteForm.style.display = 'none';
            document.body.appendChild(deleteForm);

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            deleteForm.appendChild(csrfToken);

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            deleteForm.appendChild(methodField);
        }
    }

    // Show confirmation modal
    function showConfirmationModal(expenseId) {
        deleteFormId = expenseId;
        deleteForm.action = `/expenses/${expenseId}`;

        if (confirmationModal) {
            confirmationModal.show();
        }
    }
</script>
@endpush