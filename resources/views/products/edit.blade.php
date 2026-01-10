{{-- resource/views/products/edit.blade.php --}}
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
        
        .image-container {
            position: relative;
            transition: transform 0.3s;
        }
        
        .image-container:hover {
            transform: scale(1.05);
        }
        
        .image-container img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .delete-image-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0.8;
            transition: opacity 0.3s;
        }
        
        .delete-image-btn:hover {
            opacity: 1;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>تعديل المنتج - {{ $product->name }}</h6>
                        <div>
                            <span class="badge bg-info me-2">
                                <i class="fas fa-tag"></i> {{ $product->type }}
                            </span>
                            @if($product->tailor_name)
                            <span class="badge bg-secondary">
                                <i class="fas fa-user-secret"></i> {{ $product->tailor_name }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productEditForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @include('layout.messages')
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- معلومات المنتج الأساسية -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" 
                                                   placeholder="اسم المنتج" required value="{{ old('name', $product->name) }}">
                                            @error('name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">نوع المنتج <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="type" 
                                                   placeholder="نوع المنتج" required value="{{ old('type', $product->type) }}">
                                            @error('type')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- الأسعار -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">السعر القطاعي <span class="text-danger">*</span></label>
                                            <input type="number" step="0.001" class="form-control retail-price" 
                                                   name="price" placeholder="السعر القطاعي" required 
                                                   value="{{ old('price', $product->price) }}">
                                            @error('price')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">سعر الجمله <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.001" class="form-control wholesale-price" 
                                                       name="wholesale_price" placeholder="سعر الجمله" required 
                                                       value="{{ old('wholesale_price', $product->wholesale_price) }}">
                                            </div>
                                            <small class="text-muted">يجب أن يكون ≤ السعر القطاعي</small>
                                            @error('wholesale_price')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">التكلفة <span class="text-danger">*</span></label>
                                            <input type="number" step="0.001" class="form-control" name="cost" 
                                                   placeholder="تكلفة المنتج" required value="{{ old('cost', $product->cost) }}">
                                            @error('cost')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- عرض مقارنة الأسعار -->
                                    <div class="col-12">
                                        <div class="price-comparison" id="priceComparison">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <small class="text-muted d-block">سعر القطاعي</small>
                                                        <h5 class="text-primary" id="retailPriceDisplay">
                                                            {{ number_format($product->price, 2) }} ج
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <small class="text-muted d-block">سعر الجمله</small>
                                                        <h5 class="text-success" id="wholesalePriceDisplay">
                                                            {{ number_format($product->wholesale_price, 2) }} ج
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <small class="text-muted d-block">نسبة الخصم</small>
                                                        <h5 class="discount-badge d-inline-block" id="discountPercentage">
                                                            {{ $product->wholesale_discount_percentage }}%
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center mt-2">
                                                <small class="text-muted" id="priceDifferenceText">
                                                    الفرق: {{ number_format($product->price_difference, 2) }} ج
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- معلومات إضافية -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">اسم الخياط</label>
                                            <input type="text" class="form-control" name="tailor_name" 
                                                   placeholder="اسم الخياط" value="{{ old('tailor_name', $product->tailor_name) }}">
                                            @error('tailor_name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">وصف المنتج</label>
                                            <textarea class="form-control" name="description" 
                                                      placeholder="وصف المنتج" rows="3">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                     <!-- قسم الصور الحالية -->
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">صور المنتج الحالية</label>
                                            <div class="row" id="image-gallery">
                                                @forelse($product->images as $image)
                                                    <div class="col-md-3 mb-3 image-container" data-image-id="{{ $image->id }}">
                                                        <img src="{{ asset('storage/' . $image->photo_path) }}" class="img-fluid rounded shadow-sm" alt="Product Image">
                                                        <button type="button" class="btn btn-danger btn-sm mt-2 w-100 delete-image-btn">حذف</button>
                                                    </div>
                                                @empty
                                                    <p class="text-muted">لا توجد صور حالياً</p>
                                                @endforelse
                                            </div>
                                        </div>

                                    <!-- إضافة صور جديدة -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">إضافة صور جديدة</label>
                                            <div class="border rounded p-3 bg-light">
                                                <div class="mb-2">
                                                    <input type="file" class="form-control" name="photos[]" 
                                                           id="newPhotoInput" multiple accept="image/jpeg,image/png,image/jpg,image/gif">
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
                                                <div id="newImagePreview" class="row g-3 mt-3"></div>
                                            </div>
                                            @error('photos')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                            @error('photos.*')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> تحديث المنتج
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> رجوع
                                    </a>
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
            // === منطق حذف الصور الحالية ===
            const gallery = document.getElementById('image-gallery');
            if (gallery) {
                gallery.addEventListener('click', function(e) {
                    if (e.target.classList.contains('delete-image-btn')) {
                        const container = e.target.closest('.image-container');
                        const imageId = container.dataset.imageId;

                        if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                            fetch(`{{ route('products.images.delete', ['product' => $product->id, 'image' => ':imageId']) }}`.replace(':imageId', imageId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    container.remove();
                                    // التحقق إذا لم تعد هناك صور
                                    if(gallery.querySelectorAll('.image-container').length === 0) {
                                        gallery.innerHTML = '<p class="text-muted">لا توجد صور حالياً</p>';
                                    }
                                } else {
                                    alert(data.message || 'فشل الحذف');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('حدث خطأ أثناء الحذف.');
                            });
                        }
                    }
                });
            }

            // === منطق معاينة والتحقق من الصور الجديدة ===
            const newPhotoInput = document.getElementById('newPhotoInput');
            const newImagePreview = document.getElementById('newImagePreview');
            const form = document.getElementById('productEditForm');
            
            // معاينة الصور الجديدة قبل الرفع
            newPhotoInput.addEventListener('change', function() {
                newImagePreview.innerHTML = '';
                
                if (this.files) {
                    const filesAmount = this.files.length;
                    
                    for (let i = 0; i < filesAmount; i++) {
                        const reader = new FileReader();
                        
                        reader.onload = function(event) {
                            const col = document.createElement('div');
                            col.className = 'col-md-3 mb-3';
                            
                            const img = document.createElement('img');
                            img.setAttribute('src', event.target.result);
                            img.setAttribute('class', 'img-fluid rounded border');
                            img.style.height = '150px';
                            img.style.objectFit = 'cover';
                            
                            col.appendChild(img);
                            newImagePreview.appendChild(col);
                        }
                        
                        reader.readAsDataURL(this.files[i]);
                    }
                }
            });
            
            // التحقق من جانب العميل قبل الإرسال
            form.addEventListener('submit', function(event) {
                const files = newPhotoInput.files;
                const maxSize = 2 * 1024 * 1024; // 2 ميجابايت بالبايت
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    
                    // التحقق من نوع الملف
                    if (!allowedTypes.includes(file.type)) {
                        event.preventDefault();
                        alert(`الملف "${file.name}" ليس من الصيغ المسموح بها. الصيغ المسموح بها هي: JPEG, PNG, JPG, GIF`);
                        return;
                    }
                    
                    // التحقق من حجم الملف
                    if (file.size > maxSize) {
                        event.preventDefault();
                        alert(`حجم الصورة "${file.name}" يتجاوز الحد الأقصى المسموح به (2 ميجابايت)`);
                        return;
                    }
                }
            });
        });
    </script>
@endpush
