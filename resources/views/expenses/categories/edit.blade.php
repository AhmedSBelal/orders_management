{{-- resources/views/expenses/categories/edit.blade.php --}}
@extends('layout.app-dashboard')

@section('title', 'تعديل تصنيف المصروف')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>تعديل تصنيف: {{ $category->name }}</h6>
                    <a href="{{ route('expense-categories.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-right ms-1"></i> العودة للقائمة
                    </a>
                </div>
                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('expense-categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            {{-- Name Field --}}
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">اسم التصنيف <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $category->name) }}" 
                                    placeholder="مثال: رواتب، إيجار، مواد خام"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Type Field --}}
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">نوع المصروف <span class="text-danger">*</span></label>
                                <select 
                                    class="form-select @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type"
                                    required
                                >
                                    <option value="">اختر نوع المصروف</option>
                                    <option value="fixed" {{ old('type', $category->type) == 'fixed' ? 'selected' : '' }}>ثابت</option>
                                    <option value="variable" {{ old('type', $category->type) == 'variable' ? 'selected' : '' }}>متغير</option>
                                    <option value="other" {{ old('type', $category->type) == 'other' ? 'selected' : '' }}>أخرى</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    ثابت: مصاريف شهرية ثابتة | متغير: مصاريف تختلف حسب الإنتاج | أخرى: مصاريف عامة
                                </small>
                            </div>

                            {{-- Description Field --}}
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">الوصف (اختياري)</label>
                                <textarea 
                                    class="form-control @error('description') is-invalid @enderror" 
                                    id="description" 
                                    name="description" 
                                    rows="4"
                                    placeholder="أضف وصفاً تفصيلياً للتصنيف..."
                                >{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Info Box --}}
                            @if($category->expenses_count > 0)
                            <div class="col-12 mb-3">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle ms-1"></i>
                                    <strong>ملاحظة:</strong> هذا التصنيف يحتوي على {{ $category->expenses_count }} مصروف مرتبط به.
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-between">
                                <div>
                                    @if($category->expenses_count == 0)
                                    <button 
                                        type="button" 
                                        class="btn btn-danger"
                                        onclick="confirmDelete()"
                                    >
                                        <i class="fas fa-trash ms-1"></i> حذف التصنيف
                                    </button>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('expense-categories.index') }}" class="btn btn-light">
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save ms-1"></i> حفظ التعديلات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Delete Form --}}
                    @if($category->expenses_count == 0)
                    <form id="delete-form" action="{{ route('expense-categories.destroy', $category) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .form-label {
        font-weight: 600;
        color: #344767;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border: 1px solid #d2d6da;
        border-radius: 0.5rem;
        padding: 0.625rem 0.75rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #cb0c9f;
        box-shadow: 0 0 0 2px rgba(203, 12, 159, 0.1);
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }

    .alert-info {
        background-color: #e7f3ff;
        border-color: #b3d9ff;
        color: #004085;
    }
</style>
@endpush

@push('js')
<script>
    function confirmDelete() {
        if (confirm('هل أنت متأكد من حذف هذا التصنيف؟ لا يمكن التراجع عن هذا الإجراء.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush