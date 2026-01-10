{{-- resource/views/products/create.blade.php --}}
@extends('layout.app-dashboard')

@section('title', $title)

@push('css')
<style>
    .price-comparison {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin: 10px 0;
        border-left: 4px solid #0d6efd;
    }
    
    .price-difference {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 4px;
        margin: 5px 0;
    }
    
    .discount-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.875rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>اضافة منتج جديد</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data" id="productForm">
                            @csrf
                            <div class="card-body">
                                @include('layout.messages')
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" placeholder="اسم المنتج" required value="{{old('name')}}">
                                        @error('name')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- السعر القطاعي -->
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">السعر القطاعي <span class="text-danger">*</span></label>
                                        <input type="number" step="0.001" class="form-control" name="price" 
                                               placeholder="السعر القطاعي" required value="{{old('price')}}"
                                               id="retailPrice">
                                        @error('price')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- سعر الجمله -->
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">سعر الجمله <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" step="0.001" class="form-control" name="wholesale_price" 
                                                   placeholder="سعر الجمله" required value="{{old('wholesale_price')}}"
                                                   id="wholesalePrice">
                                            {{-- <button type="button" class="btn btn-outline-secondary" id="calculateDiscount">
                                                حساب الخصم
                                            </button> --}}
                                        </div>
                                        <small class="text-muted">يجب أن يكون أقل من أو يساوي السعر القطاعي</small>
                                        @error('wholesale_price')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- عرض مقارنة الأسعار -->
                                        <div class="price-comparison mt-2 d-none" id="priceComparison">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>الفرق بين السعرين:</span>
                                                <span class="text-success fw-bold" id="priceDifference">0 ج</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>نسبة الخصم:</span>
                                                <span class="discount-badge" id="discountPercentage">0%</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">تكلفة المنتج <span class="text-danger">*</span></label>
                                        <input type="number" step="0.001" class="form-control" name="cost" 
                                               placeholder="تكلفة المنتج" required value="{{old('cost')}}">
                                        @error('cost')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">نوع المنتج <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="type" placeholder="نوع المنتج" 
                                               required value="{{old('type')}}">
                                        @error('type')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">اسم الخياط</label>
                                        <input type="text" class="form-control" name="tailor_name" 
                                               placeholder="اسم الخياط" value="{{old('tailor_name')}}">
                                        @error('tailor_name')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">وصف المنتج</label>
                                        <textarea class="form-control" name="description" 
                                                  placeholder="وصف المنتج" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- حقل رفع الصور -->
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">صور المنتج</label>
                                        <div class="border rounded p-3 bg-light">
                                            <div class="mb-2">
                                                <input type="file" class="form-control" name="photos[]" 
                                                       id="photoInput" multiple accept="image/jpeg,image/png,image/jpg,image/gif">
                                            </div>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i>
                                                <strong>ملاحظات هامة:</strong>
                                                <ul class="mb-0 mt-2">
                                                    <li>يمكنك اختيار أكثر من صورة بالضغط مع الاستمرار على Ctrl/Cmd</li>
                                                    <li>الحد الأقصى لحجم كل صورة: <strong>2 ميجابايت</strong></li>
                                                    <li>الصيغ المسموح بها: <strong>JPEG, PNG, JPG, GIF</strong></li>
                                                </ul>
                                            </div>
                                            <div id="imagePreview" class="row mt-3"></div>
                                        </div>
                                        @error('photos')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                        @error('photos.*')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> حفظ المنتج
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearForm()">
                                        <i class="fas fa-eraser"></i> مسح الحقول
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const retailPriceInput = document.getElementById('retailPrice');
        const wholesalePriceInput = document.getElementById('wholesalePrice');
        const priceComparison = document.getElementById('priceComparison');
        const priceDifference = document.getElementById('priceDifference');
        const discountPercentage = document.getElementById('discountPercentage');
        const calculateBtn = document.getElementById('calculateDiscount');
        
        // دالة لحساب الفرق ونسبة الخصم
        function calculatePriceDifference() {
            const retailPrice = parseFloat(retailPriceInput.value) || 0;
            const wholesalePrice = parseFloat(wholesalePriceInput.value) || 0;
            
            if (retailPrice > 0 && wholesalePrice > 0) {
                const difference = retailPrice - wholesalePrice;
                const percentage = retailPrice > 0 ? ((difference / retailPrice) * 100).toFixed(2) : 0;
                
                // تحديث العرض
                priceDifference.textContent = `${difference.toFixed(2)} ج`;
                discountPercentage.textContent = `${percentage}%`;
                priceComparison.classList.remove('d-none');
                
                // تلوين النتيجة
                if (difference > 0) {
                    priceDifference.classList.add('text-success');
                    priceDifference.classList.remove('text-danger');
                } else if (difference < 0) {
                    priceDifference.classList.add('text-danger');
                    priceDifference.classList.remove('text-success');
                } else {
                    priceDifference.classList.remove('text-success', 'text-danger');
                }
            } else {
                priceComparison.classList.add('d-none');
            }
        }
        
        // تحديث تلقائي عند تغيير الأسعار
        retailPriceInput.addEventListener('input', calculatePriceDifference);
        wholesalePriceInput.addEventListener('input', calculatePriceDifference);
        
        // زر حساب الخصم
        calculateBtn.addEventListener('click', function() {
            const retailPrice = parseFloat(retailPriceInput.value) || 0;
            
            if (retailPrice > 0) {
                // اقتراح سعر الجمله كـ 80% من سعر القطاعي (20% خصم)
                const suggestedWholesalePrice = retailPrice * 0.8;
                wholesalePriceInput.value = suggestedWholesalePrice.toFixed(2);
                calculatePriceDifference();
                
                // رسالة تأكيد
                showToast('تم حساب سعر الجمله بنجاح (خصم 20%)', 'success');
            } else {
                showToast('الرجاء إدخال السعر القطاعي أولاً', 'warning');
            }
        });
        
        // التحقق من صحة الأسعار قبل الإرسال
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const retailPrice = parseFloat(retailPriceInput.value) || 0;
            const wholesalePrice = parseFloat(wholesalePriceInput.value) || 0;
            
            if (wholesalePrice > retailPrice) {
                e.preventDefault();
                showToast('سعر الجمله يجب أن يكون أقل من أو يساوي السعر القطاعي', 'error');
                wholesalePriceInput.focus();
            }
        });
        
        // معاينة الصور
        const photoInput = document.getElementById('photoInput');
        const imagePreview = document.getElementById('imagePreview');
        
        photoInput.addEventListener('change', function() {
            imagePreview.innerHTML = '';
            
            if (this.files && this.files.length > 0) {
                const filesAmount = this.files.length;
                
                for (let i = 0; i < filesAmount; i++) {
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 mb-3';
                        
                        col.innerHTML = `
                            <div class="position-relative">
                                <img src="${event.target.result}" class="img-fluid rounded" 
                                     style="height: 150px; object-fit: cover; width: 100%;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                        onclick="removeImagePreview(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        
                        imagePreview.appendChild(col);
                    }
                    
                    reader.readAsDataURL(this.files[i]);
                }
            }
        });
        
        // رسائل التأكيد
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.style.minWidth = '300px';
            
            toast.innerHTML = `
                <strong>${type === 'success' ? 'نجاح!' : type === 'warning' ? 'تحذير!' : 'خطأ!'}</strong>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }
        
        window.removeImagePreview = function(button) {
            const col = button.closest('.col-md-3, .col-sm-4');
            col.remove();
        };
        
        window.clearForm = function() {
            if (confirm('هل أنت متأكد من مسح جميع الحقول؟')) {
                document.getElementById('productForm').reset();
                imagePreview.innerHTML = '';
                priceComparison.classList.add('d-none');
                showToast('تم مسح جميع الحقول', 'info');
            }
        };
        
        // حساب الفرق عند تحميل الصفحة إذا كانت هناك قيم
        if (retailPriceInput.value || wholesalePriceInput.value) {
            calculatePriceDifference();
        }
    });
</script>
@endpush