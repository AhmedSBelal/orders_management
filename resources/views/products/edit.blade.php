{{-- resource/views/products/edit.blade.php --}}
{{-- @extends('layout.app-dashboard')

@section('title', $title)

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>تعديل المنتج</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <form action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
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
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">اسم المنتج <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="name" placeholder="اسم المنتج" required value="{{old('name', $product->name)}}">
                                            <div style="color: red;">{{$errors->first('name')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">سعر المنتج<span style="color: red;">*</span></label>
                                            <input type="number" step="0.001" class="form-control" name="price" placeholder="سعر المنتج" required value="{{old('price', $product->price)}}">
                                            <div style="color: red;">{{$errors->first('price')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> تكلفه المنتج<span style="color: red;">*</span></label>
                                            <input type="number" step="0.001" class="form-control" name="cost" placeholder="تكلفه المنتج" required value="{{old('cost', $product->cost)}}">
                                            <div style="color: red;">{{$errors->first('cost')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">نوع المنتج <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="type" placeholder="نوع المنتج" required value="{{old('type', $product->type)}}">
                                            <div style="color: red;">{{$errors->first('type')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">اسم الخياط</label>
                                            <input type="text" class="form-control" name="tailor_name" placeholder="اسم الخياط" value="{{old('tailor_name', $product->tailor_name)}}">
                                            <div style="color: red;">{{$errors->first('tailor_name')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">وصف المنتج</label>
                                            <textarea class="form-control" name="description" placeholder="وصف المنتج">{{ old('description', $product->description) }}</textarea>
                                            <div style="color: red;">{{ $errors->first('description') }}</div>
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

                                        <!-- قسم إضافة صور جديدة -->
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">إضافة صور جديدة</label>
                                            <input type="file" class="form-control" name="photos[]" multiple accept="image/*">
                                            <small class="text-muted">اختر صوراً جديدة لإضافتها للمعرض</small>
                                            <div style="color: red;">{{$errors->first('photos')}}</div>
                                        </div>

                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
    @endpush
@endsection --}}


@extends('layout.app-dashboard')

@section('title', $title)

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>تعديل المنتج</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <form action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data" id="productEditForm">
                                @csrf
                                @method('PUT')
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
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">اسم المنتج <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="name" placeholder="اسم المنتج" required value="{{old('name', $product->name)}}">
                                            <div style="color: red;">{{$errors->first('name')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">سعر المنتج<span style="color: red;">*</span></label>
                                            <input type="number" step="0.001" class="form-control" name="price" placeholder="سعر المنتج" required value="{{old('price', $product->price)}}">
                                            <div style="color: red;">{{$errors->first('price')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> تكلفه المنتج<span style="color: red;">*</span></label>
                                            <input type="number" step="0.001" class="form-control" name="cost" placeholder="تكلفه المنتج" required value="{{old('cost', $product->cost)}}">
                                            <div style="color: red;">{{$errors->first('cost')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">نوع المنتج <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="type" placeholder="نوع المنتج" required value="{{old('type', $product->type)}}">
                                            <div style="color: red;">{{$errors->first('type')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">اسم الخياط</label>
                                            <input type="text" class="form-control" name="tailor_name" placeholder="اسم الخياط" value="{{old('tailor_name', $product->tailor_name)}}">
                                            <div style="color: red;">{{$errors->first('tailor_name')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">وصف المنتج</label>
                                            <textarea class="form-control" name="description" placeholder="وصف المنتج">{{ old('description', $product->description) }}</textarea>
                                            <div style="color: red;">{{ $errors->first('description') }}</div>
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

                                        <!-- قسم إضافة صور جديدة (محسن) -->
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">إضافة صور جديدة</label>
                                            <div class="border rounded p-3 bg-light">
                                                <div class="mb-2">
                                                    <input type="file" class="form-control" name="photos[]" id="newPhotoInput" multiple accept="image/jpeg,image/png,image/jpg,image/gif">
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
                                                <div id="newImagePreview" class="row mt-3"></div>
                                            </div>
                                            <div style="color: red;">{{$errors->first('photos')}}</div>
                                        </div>

                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
@endsection