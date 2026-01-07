{{-- resources/views/orders/edit-order.blade.php --}}
@extends('layout.app-dashboard')

@section('title', $title)

@push('css')
    <style>
        #productTable {
            margin-bottom: 15px;
            width: 100%;
        }

        #productTable th,
        #productTable td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .remove-row {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            #productTable {
                display: block;
                overflow-x: auto;
            }
            
            .form-control {
                width: 100%;
            }
            
            .table-responsive {
                margin-bottom: 1rem;
            }
        }

        .card-footer {
            display: flex;
            justify-content: start !important;
            padding: 1rem;
            text-align: left !important;
        }

        .btn-submit {
            min-width: 120px;
            margin-right: auto;
        }

        [dir="rtl"] .card-footer {
            justify-content: start !important;
            text-align: left !important;
        }

        /* Image Management Styles */
        .image-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .existing-images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .existing-image-card {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            background: white;
            transition: transform 0.2s;
        }

        .existing-image-card:hover {
            transform: scale(1.05);
        }

        .existing-image-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .existing-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .existing-image-card:hover .existing-image-overlay {
            opacity: 1;
        }

        .delete-existing-image {
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .delete-existing-image:hover {
            background: rgba(220, 53, 69, 1);
        }

        .image-preview-container {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            background: white;
        }

        .image-preview-container img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .image-name {
            padding: 8px;
            font-size: 0.875rem;
            color: #6c757d;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .no-images-message {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            background: white;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }

        .section-divider {
            border-top: 2px solid #dee2e6;
            margin: 20px 0;
            position: relative;
        }

        .section-divider::before {
            content: 'أو';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: #f8f9fa;
            padding: 0 10px;
            color: #6c757d;
            font-weight: bold;
        }

        .delete-indicator {
            background: rgba(220, 53, 69, 0.1);
            border: 2px dashed #dc3545;
        }

        .delete-indicator img {
            opacity: 0.3;
        }

        .delete-indicator::after {
            content: 'سيتم الحذف';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <div class="card-title">تعديل الطلب #{{ $order->id }}</div>
                </div>
                @include('layout.messages')
                
                <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data" id="orderForm">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">اسم المرسل اليه <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="client_name" placeholder="اسم المرسل اليه" required value="{{ old('client_name', $order->client_name) }}">
                                @error('client_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="client_phone" placeholder="رقم الهاتف" required value="{{ old('client_phone', $order->client_phone) }}">
                                @error('client_phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">العنوان <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="location" placeholder="العنوان" required value="{{ old('location', $order->location) }}">
                                @error('location')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">المحافظه <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="city" placeholder="المحافظه" required value="{{ old('city', $order->city) }}">
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">حالة الطلب <span class="text-danger">*</span></label>
                                <select name="status_id" class="form-control" required>
                                    <option value="">اختر حالة الطلب</option>
                                    @foreach(\App\Models\OrderStatus::all() as $status)
                                        <option value="{{ $status->id }}" {{ old('status_id', $order->status_id) == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">المقدم</label>
                                <input type="number" class="form-control" name="deposited" placeholder="المقدم" value="{{ old('deposited', $order->deposited) }}" step="0.01" min="0">
                                @error('deposited')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">جاى عن طريق</label>
                                <input type="text" class="form-control" name="come_from" placeholder="جاى عن طريق" value="{{ old('come_from', $order->come_from) }}">
                                @error('come_from')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">المبلغ بعد الخصم</label>
                                <input type="number" class="form-control" name="total_price_after_discount" placeholder="المبلغ بعد الخصم" value="{{old('total_price_after_discount', $order->total_price_after_discount)}}" step="0.001" min="0">
                                @error('total_price_after_discount')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">ملاحظات</label>
                                <textarea class="form-control" name="notes" placeholder="أضف ملاحظاتك هنا">{{ old('notes', $order->notes) }}</textarea>
                                @error('notes')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Management Section -->
                            <div class="col-12 mb-4">
                                <div class="image-section">
                                    <h5 class="mb-3">
                                        <i class="fas fa-images"></i> إدارة صور الطلب
                                    </h5>

                                    <!-- Existing Images -->
                                    @if($order->images && $order->images->count() > 0)
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">الصور الحالية ({{ $order->images->count() }})</label>
                                            <div class="existing-images-grid" id="existingImagesGrid">
                                                @foreach($order->images as $image)
                                                    <div class="existing-image-card" id="existing-image-{{ $image->id }}" data-image-id="{{ $image->id }}">
                                                        <img src="{{ asset('storage/' . $image->photo_path) }}" alt="Order Image">
                                                        <div class="existing-image-overlay">
                                                            <button type="button" class="delete-existing-image" onclick="markImageForDeletion({{ $image->id }})">
                                                                <i class="fas fa-trash"></i> حذف
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- Hidden inputs for images to delete -->
                                            <div id="deletedImagesContainer"></div>
                                        </div>

                                        <div class="section-divider"></div>
                                    @else
                                        <div class="no-images-message mb-4">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p>لا توجد صور حالية لهذا الطلب</p>
                                        </div>
                                    @endif

                                    <!-- Add New Images -->
                                    <div>
                                        <label class="form-label fw-bold">
                                            إضافة صور جديدة
                                            <small class="text-muted">(اختياري - يمكنك إضافة حتى 5 صور)</small>
                                        </label>
                                        <div class="border rounded p-3 bg-white">
                                            <div class="d-flex align-items-center justify-content-center w-100 mb-3">
                                                <label for="photoUpload" class="btn btn-outline-primary cursor-pointer mb-0">
                                                    <i class="fas fa-cloud-upload-alt me-2"></i>
                                                    اختر صور جديدة
                                                </label>
                                                <input type="file" name="photos[]" id="photoUpload" class="d-none" 
                                                       multiple accept="image/*">
                                                <small class="text-muted ms-3">يمكنك اختيار عدة صور في نفس الوقت</small>
                                            </div>
                                            
                                            <!-- New Images Preview Grid -->
                                            <div id="imagePreviewGrid" class="row g-2">
                                                <!-- New images will be displayed here -->
                                            </div>
                                        </div>
                                        @error('photos')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                        @error('photos.*')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Products Table -->
                            <div class="mb-3 col-12">
                                <label class="form-label">Products and Colors</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="productTable">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Numbers</th>
                                                <th>Done</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($order->products as $product)
                                                <tr>
                                                    <td>
                                                        <select class="form-control product-select" name="products[]" required>
                                                            <option value="">Select Product</option>
                                                            @foreach($productsData as $p)
                                                                <option value="{{ $p->id }}" {{ $p->id == $product->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control color-select" name="colors[]" required>
                                                            <option value="">Select Color</option>
                                                            @foreach($colors as $color)
                                                                <option value="{{ $color->id }}" {{ $color->id == $product->pivot->color_id ? 'selected' : '' }}>{{ $color->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" class="form-control" name="sizes[]" placeholder="المقاس" value="{{ $product->pivot->size }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="quantities[]" placeholder="عدد" value="{{ $product->pivot->quantity }}">
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch d-flex justify-content-center">
                                                            <input class="form-check-input" type="checkbox" name="is_done[{{ $loop->index }}]" value="1" {{ $product->pivot->is_done ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td>
                                                        <select class="form-control product-select" name="products[]" required>
                                                            <option value="">Select Product</option>
                                                            @foreach($productsData as $p)
                                                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control color-select" name="colors[]" required>
                                                            <option value="">Select Color</option>
                                                            @foreach($colors as $color)
                                                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" class="form-control" name="sizes[]" placeholder="المقاس">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="quantities[]" placeholder="عدد" value="1">
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch d-flex justify-content-center">
                                                            <input class="form-check-input" type="checkbox" name="is_done[0]" value="1">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-primary" id="addRow">Add Row</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="fas fa-save"></i> تحديث الطلب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Product Table Functions
    document.getElementById('addRow').addEventListener('click', function () {
        const table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
        const lastRow = table.rows[table.rows.length - 1];
        const newRow = lastRow.cloneNode(true);

        const lastColor = lastRow.querySelector('.color-select').value;
        const lastSize = lastRow.querySelector('input[name="sizes[]"]').value;
        const lastQuantity = lastRow.querySelector('input[name="quantities[]"]').value;

        newRow.querySelector('.product-select').selectedIndex = 0;
        newRow.querySelector('.color-select').value = lastColor;
        newRow.querySelector('input[name="sizes[]"]').value = lastSize;
        newRow.querySelector('input[name="quantities[]"]').value = lastQuantity || 1;
        
        const checkbox = newRow.querySelector('input[type="checkbox"]');
        checkbox.checked = false;
        
        let match = checkbox.name.match(/\d+/);
        let index = match ? parseInt(match[0]) + 1 : 0;
        checkbox.name = `is_done[${index}]`;

        table.appendChild(newRow);
    });

    document.addEventListener('click', function (event) {
        if (event.target && event.target.classList.contains('remove-row')) {
            const tableBody = document.getElementById('productTable').getElementsByTagName('tbody')[0];
            if (tableBody.rows.length > 1) {
                event.target.closest('tr').remove();
            } else {
                alert('At least one row is required.');
            }
        }
    });

    // Image Deletion Management
    const imagesToDelete = new Set();

    function markImageForDeletion(imageId) {
        const imageCard = document.getElementById(`existing-image-${imageId}`);
        const container = document.getElementById('deletedImagesContainer');
        
        if (imagesToDelete.has(imageId)) {
            // Unmark for deletion
            imagesToDelete.delete(imageId);
            imageCard.classList.remove('delete-indicator');
            
            // Remove hidden input
            const input = document.getElementById(`delete-image-${imageId}`);
            if (input) input.remove();
        } else {
            // Mark for deletion
            imagesToDelete.add(imageId);
            imageCard.classList.add('delete-indicator');
            
            // Add hidden input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imageId;
            input.id = `delete-image-${imageId}`;
            container.appendChild(input);
        }
    }

    // New Images Preview
    const photoUpload = document.getElementById('photoUpload');
    const imagePreviewGrid = document.getElementById('imagePreviewGrid');

    photoUpload.addEventListener('change', function(e) {
        imagePreviewGrid.innerHTML = '';
        const files = Array.from(e.target.files);
        
        if (files.length > 5) {
            alert('يمكنك اختيار 5 صور كحد أقصى');
            e.target.value = '';
            return;
        }

        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-md-4 col-sm-6 mb-3';
                    colDiv.innerHTML = `
                        <div class="image-preview-container">
                            <img src="${e.target.result}" alt="Preview ${index + 1}">
                            <div class="image-name">${file.name}</div>
                        </div>
                    `;
                    imagePreviewGrid.appendChild(colDiv);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Form submission with loading state
    document.getElementById('orderForm').addEventListener('submit', function() {
        const submitBtn = this.querySelector('.btn-submit');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري التحديث...';
    });
</script>
@endpush