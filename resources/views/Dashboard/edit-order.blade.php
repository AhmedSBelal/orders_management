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
    </style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <div class="card-title">Update Order</div>
                </div>
                @include('layout.messages')
                <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">اسم المرسل اليه <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="client_name" placeholder="اسم المرسل اليه" required value="{{ old('client_name', $order->client_name) }}">
                                @error('client_name')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">العنوان <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="location" placeholder="العنوان" required value="{{ old('location', $order->location) }}">
                                @error('location')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">حالة الطلب <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="">اختر حالة الطلب</option>
                                    @foreach(\App\Enums\OrderStatuses::cases() as $status)
                                        <option value="{{ $status->value }}" {{ old('status', $order->status) == $status->value ? 'selected' : '' }}>
                                            {{ $status->value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control" name="client_phone" placeholder="رقم الهاتف" value="{{ old('client_phone', $order->client_phone) }}">
                                @error('client_phone')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">المحافظه</label>
                                <input type="text" class="form-control" name="city" placeholder="المحافظه" value="{{ old('city', $order->city) }}">
                                @error('city')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">المقدم</label>
                                <input type="number" class="form-control" name="deposited" placeholder="المقدم" value="{{ old('deposited', $order->deposited) }}" step="0.01" min="0"">
                                @error('deposited')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">جاى عن طريق</label>
                                <input type="text" class="form-control" name="come_from" placeholder="جاى عن طريق" value="{{ old('come_from', $order->come_from) }}">
                                @error('come_from')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">المبلغ بعد الخصم</label>
                                <input type="number" class="form-control" name="total_price_after_discount" placeholder="المبلغ بعد الخصم" value="{{old('total_price_after_discount', $order->total_price_after_discount)}}" step="0.001" min="0"">
                                @error('total_price_after_discount')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">ملاحظات</label>
                                <textarea class="form-control" name="notes" placeholder="أضف ملاحظاتك هنا">{{ old('notes', $order->notes) }}</textarea>
                                @error('notes')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
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
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-submit">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.getElementById('addRow').addEventListener('click', function () {
        const table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
        const lastRow = table.rows[table.rows.length - 1];
        const newRow = lastRow.cloneNode(true);

        // Get values from last row
        const lastColor = lastRow.querySelector('.color-select').value;
        const lastSize = lastRow.querySelector('input[name="sizes[]"]').value;
        const lastQuantity = lastRow.querySelector('input[name="quantities[]"]').value;

        // Clear product selection but keep color and size
        newRow.querySelector('.product-select').selectedIndex = 0;
        newRow.querySelector('.color-select').value = lastColor;
        newRow.querySelector('input[name="sizes[]"]').value = lastSize;
        newRow.querySelector('input[name="quantities[]"]').value = lastQuantity || 1; // Default to 1 if empty
        
        // Reset the done checkbox
        const checkbox = newRow.querySelector('input[type="checkbox"]');
        checkbox.checked = false;
        
        // Update is_done checkbox index
        let match = checkbox.name.match(/\d+/);
        let index = match ? parseInt(match[0]) + 1 : 0;
        checkbox.name = `is_done[${index}]`;

        table.appendChild(newRow);
    });

    // Remove row functionality
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
</script>
@endpush
