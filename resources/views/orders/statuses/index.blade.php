{{-- resource/views/orders/statuses/index.blade.php --}}
@extends('layout.app-dashboard')

@section('title', 'إدارة الحالات')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- ====== نموذج إضافة حالة جديدة (هذا هو الجزء المضاف) ====== -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6>إضافة حالة جديدة</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.statuses.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="statusName" class="form-label">اسم الحالة</label>
                                    <input type="text" class="form-control" id="statusName" name="name" placeholder="أدخل اسم الحالة الجديدة" required>
                                    @error('name')
                                        <div class="text-danger mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-plus"></i> إضافة حالة
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- ====== نهاية النموذج المضاف ====== -->

            <!-- جدول عرض الحالات (الكود الأصلي الخاص بك) -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6>كل الحالات</h6>
                </div>
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

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اسم الحالة</th>
                                    <th>عدد الطلبات المستخدمة فيها</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($statuses as $status)
                                <tr>
                                    <td>{{ $status->name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $status->orders_count }}</span>
                                    </td>
                                    <td>
                                        @if($status->orders_count == 0)
                                            <form action="{{ route('orders.statuses.destroy', $status->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الحالة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled title="لا يمكن الحذف">محمي</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">لا توجد حالات لعرضها.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection