{{-- views/Dashboard/create-order.blade.php --}}
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

        /* Live Search Styles */
        .search-results-container {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }

        .search-results-header {
            font-weight: bold;
            color: #495057;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .similar-count {
            background: #17a2b8;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.875rem;
        }

        .search-result-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
            transition: all 0.3s;
            position: relative;
        }

        .search-result-card:hover {
            border-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
            transform: translateY(-2px);
        }

        .similarity-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .result-header {
            font-weight: bold;
            color: #344767;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .order-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .result-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin: 10px 0;
        }

        .info-item {
            font-size: 0.875rem;
            color: #495057;
            padding: 6px 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .info-item strong {
            color: #212529;
        }

        .result-products {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e9ecef;
        }

        .product-item {
            background: #f8f9fa;
            padding: 8px 12px;
            margin: 6px 0;
            border-radius: 6px;
            font-size: 0.875rem;
            color: #495057;
        }

        .update-btn {
            width: 100%;
            margin-top: 12px;
            background: linear-gradient(45deg, #0d6efd, #0dcaf0);
            border: none;
            padding: 10px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .update-btn:hover {
            background: linear-gradient(45deg, #0b5ed7, #0aa2c0);
            transform: scale(1.02);
        }

        .search-loading {
            text-align: center;
            padding: 30px;
            color: #6c757d;
        }

        .no-results {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-style: italic;
        }

        .search-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .search-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #6c757d;
            margin-left: 5px;
            animation: pulse 1.5s infinite ease-in-out;
        }

        .search-indicator.active {
            background-color: #28a745;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }

        @media (max-width: 768px) {
            .result-info {
                grid-template-columns: 1fr;
            }

            .similarity-badge {
                position: static;
                display: inline-block;
                margin-bottom: 10px;
            }
        }

        /* Image Upload Styles */
        .image-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .image-upload-area:hover {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }

        .image-upload-area.dragover {
            border-color: #28a745;
            background-color: #e8f5e8;
        }

        .image-preview-container {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .image-preview-container:hover {
            transform: scale(1.05);
        }

        .image-preview-container img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .image-remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(220, 53, 69, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .image-preview-container:hover .image-remove-btn {
            opacity: 1;
        }

        .no-images {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .no-images i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">اضافة طلب جديد</div>
                    </div>
                    @include('layout.messages')

                    <form action="{{route('orders.store')}}" method="POST" enctype="multipart/form-data" id="orderForm">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="mb-3 col-md-6 col-12">
                                    <label class="form-label">اسم المرسل اليه <span class="text-danger">*</span></label>
                                    <div class="search-controls">
                                        <input type="text" class="form-control" name="client_name" id="client_name"
                                            placeholder="اسم المرسل اليه" required value="{{old('client_name')}}">
                                        <span class="search-indicator" id="searchIndicator"></span>
                                    </div>
                                    @error('client_name')
                                        <div class="text-danger">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6 col-12">
                                    <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                    <div class="search-controls">
                                        <input type="text" class="form-control" name="client_phone" id="client_phone"
                                            placeholder="رقم الهاتف" value="{{old('client_phone')}}" required>
                                        <span class="search-indicator" id="searchIndicatorPhone"></span>
                                    </div>
                                    @error('client_phone')
                                        <div class="text-danger">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Search Results Container -->
                                <div class="col-12" id="searchResultsContainer"></div>

                                <div class="mb-3 col-md-6 col-12">
                                    <label class="form-label">العنوان <span class="text-danger">*</span></label>
                                    <div class="search-controls">
                                        <input type="text" class="form-control" name="location" id="location"
                                            placeholder="العنوان" required value="{{old('location')}}">
                                        <span class="search-indicator" id="searchIndicatorLocation"></span>
                                    </div>
                                    @error('location')
                                        <div class="text-danger">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6 col-12">
                                    <label class="form-label">المحافظه <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="city" placeholder="المحافظه"
                                        value="{{old('city')}}" required>
                                    @error('city')
                                        <div class="text-danger">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6 col-12">
                                    <label class="form-label">حالة الطلب <span class="text-danger">*</span></label>
                                    <select name="status_id" class="form-control" required>
                                        <option value="">اختر حالة الطلب</option>
                                        @foreach(\App\Models\OrderStatus::all() as $status)
                                            <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status_id')
                                        <div class="text-danger">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6 col-12">
                                    <label class="form-label">المقدم</label>
                                    <input type="number" class="form-control" name="deposited" placeholder="المقدم"
                                        value="{{old('deposited', 0)}}" step="1" min="0">
                                    @error('deposited')
                                        <div class="text-danger">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6 col-12">
                                    <label class="form-label">جاى عن طريق</label>
                                    <input type="text" class="form-control" name="come_from" placeholder="جاى عن طريق"
                                        value="{{old('come_from')}}">
                                    @error('come_from')
                                        <div class="text-danger">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6 col-12">
                                    <label class="form-label">المبلغ بعد الخصم</label>
                                    <input type="number" class="form-control" name="total_price_after_discount"
                                        placeholder="المبلغ بعد الخصم" value="{{old('total_price_after_discount', 0)}}"
                                        step="0.001" min="0">
                                    @error('total_price_after_discount')
                                        <div class="text-danger">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label class="form-label">ملاحظات</label>
                                    <textarea class="form-control" name="notes"
                                        placeholder="أضف ملاحظاتك هنا">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Upload Section -->
                                <div class="mb-4 col-12">
                                    <label class="form-label h5">
                                        <i class="fas fa-images"></i> صور الطلب <span class="text-danger">*</span>
                                        <small class="text-muted">(يمكنك إضافة حتى 5 صور)</small>
                                    </label>
                                    <div class="border rounded p-3 bg-light">
                                        <div class="d-flex align-items-center justify-content-center w-100 mb-3">
                                            <label for="photoUpload" class="btn btn-outline-primary cursor-pointer mb-0">
                                                <i class="fas fa-cloud-upload-alt me-2"></i>
                                                اختر الصور
                                            </label>
                                            <input type="file" name="photos[]" id="photoUpload" class="d-none" 
                                                   multiple accept="image/*" required>
                                            <small class="text-muted ms-3">يمكنك اختيار عدة صور في نفس الوقت</small>
                                            <br>
                                            <small class="text-muted ms-3"> (صيغ مقبولة: jpeg, png, jpg, gif. الحد الأقصى: 2 ميجابايت)</small>
                                        </div>
                                        
                                        <!-- Image Preview Grid -->
                                        <div id="imagePreviewGrid" class="row g-2">
                                            <!-- Images will be displayed here -->
                                        </div>
                                    </div>
                                    @error('photos')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    @error('photos.*')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

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
                                                <tr>
                                                    <td>
                                                        <select class="form-control product-select" name="products[]"
                                                            required>
                                                            <option value="">Select Product</option>
                                                            @foreach($products as $product)
                                                                <option value="{{$product->id}}">{{$product->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control color-select" name="colors[]" required>
                                                            <option value="">Select Color</option>
                                                            @foreach($colors as $color)
                                                                <option value="{{$color->id}}">{{$color->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" class="form-control" name="sizes[]"
                                                            placeholder="المقاس" value="{{request('size')}}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="quantities[]"
                                                            placeholder="عدد" value="{{request('quantity', 1)}}">
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch d-flex justify-content-center">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="is_done[0]" value="1">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-row">Remove</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="addRow">Add Row</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-outline-secondary me-2" id="clearForm">
                                <i class="fas fa-eraser"></i> مسح الحقول
                            </button>
                            <button type="submit" class="btn btn-primary btn-submit">
                                <i class="fas fa-save"></i> حفظ الطلب
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

        // Debounce function to improve performance
        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        let currentSearchResults = [];
        let isSearchActive = true;

        function performSearch() {
            if (!isSearchActive) return;

            const clientName = document.getElementById('client_name').value.trim();
            const clientPhone = document.getElementById('client_phone').value.trim();
            const location = document.getElementById('location').value.trim();

            // البحث فقط إذا كان هناك على الأقل 2 أحرف
            if (clientName.length < 2 && clientPhone.length < 2 && location.length < 2) {
                document.getElementById('searchResultsContainer').innerHTML = '';
                updateSearchIndicators(false);
                return;
            }

            // إظهار Loading
            updateSearchIndicators(true);
            document.getElementById('searchResultsContainer').innerHTML = `
            <div class="search-results-container">
                <div class="search-loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-3">جاري البحث عن طلبات مشابهة...</p>
                    <small class="text-muted">البحث عن: "${clientPhone}"</small>
                </div>
            </div>
        `;

            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // إرسال الطلب
            fetch('{{ route("orders.search") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    client_name: clientName,
                    client_phone: clientPhone,
                    location: location
                })
            })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        currentSearchResults = data.data;
                        displaySearchResults(data.data);
                        updateSearchIndicators(false, true);
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    document.getElementById('searchResultsContainer').innerHTML = `
                <div class="search-results-container">
                    <div class="no-results">
                        <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                        <p class="mt-3 text-danger">حدث خطأ أثناء البحث</p>
                        <small class="text-muted d-block mb-3">${error.message}</small>
                        <button class="btn btn-sm btn-outline-primary mt-2" onclick="performSearch()">
                            <i class="fas fa-redo"></i> إعادة المحاولة
                        </button>
                    </div>
                </div>
            `;
                    updateSearchIndicators(false);
                });
        }

        // Update search indicators
        function updateSearchIndicators(isLoading, hasResults = false) {
            const indicators = document.querySelectorAll('.search-indicator');
            indicators.forEach(indicator => {
                if (isLoading) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                    if (hasResults) {
                        indicator.style.backgroundColor = '#28a745';
                    } else {
                        indicator.style.backgroundColor = '#6c757d';
                    }
                }
            });
        }

        // Display Search Results
        function displaySearchResults(results) {
            const container = document.getElementById('searchResultsContainer');

            if (results.length === 0) {
                container.innerHTML = '';
                return;
            }

            let html = `
                <div class="search-results-container">
                    <div class="search-results-header">
                        <span>
                            <i class="fas fa-search"></i> طلبات مشابهة موجودة
                        </span>
                        <span class="similar-count">${results.length} طلب</span>
                    </div>
            `;

            results.forEach((order, index) => {
                const similarityPercent = Math.round((order.similarity_score / 200) * 100);

                html += `
                    <div class="search-result-card">
                        <div class="similarity-badge">
                            ${similarityPercent}% متشابه
                        </div>

                        <div class="result-header">
                            <span class="order-badge">#${order.id}</span>
                            <strong>${order.client_name}</strong>
                            <span style="margin-right: auto; color: #6c757d; font-size: 0.875rem;">
                                ${order.created_at}
                            </span>
                        </div>

                        <div class="result-info">
                            <div class="info-item">
                                <strong>📱 الهاتف:</strong> ${order.client_phone}
                            </div>
                            <div class="info-item">
                                <strong>📍 العنوان:</strong> ${order.location}
                            </div>
                            <div class="info-item">
                                <strong>🏙️ المحافظة:</strong> ${order.city}
                            </div>
                            <div class="info-item">
                                <strong>📊 الحالة:</strong> ${order.status}
                            </div>
                            <div class="info-item">
                                <strong>💰 السعر:</strong> ${order.total_price_after_discount} جنيه
                            </div>
                            ${order.deposited ? `
                            <div class="info-item">
                                <strong>💵 المقدم:</strong> ${order.deposited}
                            </div>
                            ` : ''}
                            ${order.come_from ? `
                            <div class="info-item">
                                <strong>👤 جاي عن طريق:</strong> ${order.come_from}
                            </div>
                            ` : ''}
                        </div>

                        ${order.notes ? `
                        <div class="info-item" style="margin-top: 10px;">
                            <strong>📝 ملاحظات:</strong> ${order.notes}
                        </div>
                        ` : ''}

                        <div class="result-products">
                            <strong style="color: #495057;">📦 المنتجات:</strong>
                `;

                order.products.forEach(product => {
                    const doneStatus = product.is_done ? '✅' : '⏳';
                    html += `
                        <div class="product-item">
                            ${doneStatus} <strong>${product.product_name}</strong> 
                            <span style="color: #6c757d;">(${product.color_name})</span>
                            ${product.size ? `- مقاس: <strong>${product.size}</strong>` : ''}
                            - عدد: <strong>${product.quantity}</strong>
                        </div>
                    `;
                });

                html += `
                        </div>

                        <button type="button" class="btn btn-primary update-btn" onclick="fillFormFromOrder(${index})">
                            <i class="fas fa-edit"></i> استخدام بيانات هذا الطلب
                        </button>
                        <button type="button" class="btn btn-outline-secondary update-btn" onclick="redirectToEdit('${order.edit_url}')">
                            <i class="fas fa-external-link-alt"></i> تعديل هذا الطلب
                        </button>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;

            // Scroll to results
            setTimeout(() => {
                container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }

        // Fill form from selected order
        function fillFormFromOrder(index) {
            const order = currentSearchResults[index];
            if (!order) return;

            // Stop search when an order is selected
            isSearchActive = false;

            // Fill form fields
            document.getElementById('client_name').value = order.client_name;
            document.getElementById('client_phone').value = order.client_phone;
            document.getElementById('location').value = order.location;

            // Clear search results
            document.getElementById('searchResultsContainer').innerHTML = '';
            updateSearchIndicators(false);

            // Scroll to top of form
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Show success message
            const successAlert = document.createElement('div');
            successAlert.className = 'alert alert-success alert-dismissible fade show';
            successAlert.innerHTML = `
                <i class="fas fa-check-circle"></i> تم ملء الحقول ببيانات الطلب #${order.id}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert after the card header
            const cardHeader = document.querySelector('.card-header');
            cardHeader.parentNode.insertBefore(successAlert, cardHeader.nextSibling);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                successAlert.remove();
            }, 5000);
        }

        // Redirect to Edit
        function redirectToEdit(url) {
            window.location.href = url;
        }

        // Clear form function
        function clearForm() {
            document.getElementById('orderForm').reset();
            document.getElementById('searchResultsContainer').innerHTML = '';
            isSearchActive = true;
            updateSearchIndicators(false);
        }

        // Event Listeners for Live Search with debounce
        const debouncedSearch = debounce(performSearch, 600);

        document.getElementById('client_name').addEventListener('input', debouncedSearch);
        document.getElementById('client_phone').addEventListener('input', debouncedSearch);
        document.getElementById('location').addEventListener('input', debouncedSearch);

        // Clear form button
        document.getElementById('clearForm').addEventListener('click', clearForm);

        // Product Table Functions
        document.getElementById('addRow').addEventListener('click', function () {
            const table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
            const lastRow = table.rows[table.rows.length - 1];
            const newRow = lastRow.cloneNode(true);

            const lastProduct = lastRow.querySelector('.product-select').value;
            const lastColor = lastRow.querySelector('.color-select').value;
            const lastSize = lastRow.querySelector('input[name="sizes[]"]').value;
            const lastQuantity = lastRow.querySelector('input[name="quantities[]"]').value;

            newRow.querySelector('.product-select').value = lastProduct;
            newRow.querySelector('.color-select').value = lastColor;
            newRow.querySelector('input[name="sizes[]"]').value = lastSize;
            newRow.querySelector('input[name="quantities[]"]').value = lastQuantity || 1;

            const checkbox = newRow.querySelector('input[type="checkbox"]');
            checkbox.checked = false;
            const match = checkbox.name.match(/\d+/);
            const index = match ? parseInt(match[0]) + 1 : 0;
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

        // Image Upload - Preview Only (No custom FormData submission)
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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري الحفظ...';
        });
    </script>

@endpush