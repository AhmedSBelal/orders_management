{{-- resource/views/products/create.blade.php --}}
@extends('layout.app-dashboard')

@section('title', $title)

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
                                            <input type="text" class="form-control" name="name" placeholder="اسم المنتج" required value="{{old('name')}}">
                                            <div style="color: red;">{{$errors->first('name')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">سعر المنتج<span style="color: red;">*</span></label>
                                            <input type="number" step="0.001" class="form-control" name="price" placeholder="سعر المنتج" required value="{{old('price')}}">
                                            <div style="color: red;">{{$errors->first('price')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> تكلفه المنتج<span style="color: red;">*</span></label>
                                            <input type="number" step="0.001" class="form-control" name="cost" placeholder="تكلفه المنتج" required value="{{old('cost')}}">
                                            <div style="color: red;">{{$errors->first('cost')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">نوع المنتج <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="type" placeholder="نوع المنتج" required value="{{old('type')}}">
                                            <div style="color: red;">{{$errors->first('type')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">اسم الخياط</label>
                                            <input type="text" class="form-control" name="tailor_name" placeholder="اسم الخياط" value="{{old('tailor_name')}}">
                                            <div style="color: red;">{{$errors->first('tailor_name')}}</div>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">وصف المنتج</label>
                                            <textarea class="form-control" name="description" placeholder="وصف المنتج">{{ old('description') }}</textarea>
                                            <div style="color: red;">{{ $errors->first('description') }}</div>
                                        </div>

                                        <!-- حقل رفع الصور المحسن -->
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">صور المنتج</label>
                                            <div class="border rounded p-3 bg-light">
                                                <div class="mb-2">
                                                    <input type="file" class="form-control" name="photos[]" id="photoInput" multiple accept="image/jpeg,image/png,image/jpg,image/gif">
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
                                            <div style="color: red;">{{$errors->first('photos')}}</div>
                                        </div>

                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
            const photoInput = document.getElementById('photoInput');
            const imagePreview = document.getElementById('imagePreview');
            const form = document.getElementById('productForm');
            
            // معاينة الصور قبل الرفع
            photoInput.addEventListener('change', function() {
                imagePreview.innerHTML = '';
                
                if (this.files) {
                    const filesAmount = this.files.length;
                    
                    for (let i = 0; i < filesAmount; i++) {
                        const reader = new FileReader();
                        
                        reader.onload = function(event) {
                            const col = document.createElement('div');
                            col.className = 'col-md-3 mb-3';
                            
                            const img = document.createElement('img');
                            img.setAttribute('src', event.target.result);
                            img.setAttribute('class', 'img-fluid rounded');
                            img.style.height = '150px';
                            img.style.objectFit = 'cover';
                            
                            col.appendChild(img);
                            imagePreview.appendChild(col);
                        }
                        
                        reader.readAsDataURL(this.files[i]);
                    }
                }
            });
            
            // التحقق من جانب العميل قبل الإرسال
            form.addEventListener('submit', function(event) {
                const files = photoInput.files;
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