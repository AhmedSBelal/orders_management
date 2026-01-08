{{-- resources/views/expenses/create.blade.php --}}
@extends('layout.app-dashboard')

@section('title', 'إضافة مصروف جديد')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6>إضافة مصروف جديد</h6>
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('expenses.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expense_category_id" class="form-control-label">فئة المصروف <span class="text-danger">*</span></label>
                                    <select class="form-control @error('expense_category_id') is-invalid @enderror" 
                                            id="expense_category_id" 
                                            name="expense_category_id" 
                                            required>
                                        <option value="">اختر الفئة</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('expense_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('expense_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount" class="form-control-label">المبلغ <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           step="0.01"
                                           min="0"
                                           value="{{ old('amount') }}"
                                           placeholder="0.00"
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expense_date" class="form-control-label">تاريخ المصروف <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('expense_date') is-invalid @enderror" 
                                           id="expense_date" 
                                           name="expense_date" 
                                           value="{{ old('expense_date', date('Y-m-d')) }}"
                                           required>
                                    @error('expense_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method" class="form-control-label">طريقة الدفع</label>
                                    <select class="form-control @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" 
                                            name="payment_method">
                                        <option value="">اختر طريقة الدفع</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                                        <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>بطاقة</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                        <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>دفع إلكتروني</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_id" class="form-control-label">الطلب (اختياري)</label>
                                    <select class="form-control @error('order_id') is-invalid @enderror" 
                                            id="order_id" 
                                            name="order_id">
                                        <option value="">اختر الطلب</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                                #{{ $order->id }} - {{ $order->customer_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('order_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_id" class="form-control-label">المنتج (اختياري)</label>
                                    <select class="form-control @error('product_id') is-invalid @enderror" 
                                            id="product_id" 
                                            name="product_id">
                                        <option value="">اختر المنتج</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes" class="form-control-label">ملاحظات</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="4"
                                              placeholder="أدخل أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('expenses.index') }}" class="btn btn-light">إلغاء</a>
                            <button type="submit" class="btn btn-primary">حفظ المصروف</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .form-control:focus {
        border-color: #cb0c9f;
        box-shadow: 0 0 0 0.2rem rgba(203, 12, 159, 0.25);
    }
    .gap-2 {
        gap: 0.5rem;
    }
</style>
@endpush