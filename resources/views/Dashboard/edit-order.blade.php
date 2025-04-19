@extends('layout.app-dashboard')

@section('title', $title)

@section('css')
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
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <div class="card-title">Update Order</div>
                </div>
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
                                <input type="number" class="form-control" name="deposited" placeholder="المقدم" value="{{ old('deposited', $order->deposited) }}" step="1" min="0" max="100">
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
                                <label class="form-label">حالة دفع الطلب</label>
                                <select name="payment_status" class="form-control">
                                    <option value="">حالة دفع الطلب</option>
                                    <option value="تم الدفع" {{ old('payment_status', $order->payment_status) == "تم الدفع" ? 'selected' : '' }}>تم الدفع</option>
                                    <option value="انتظار الدفع" {{ old('payment_status', $order->payment_status) == "انتظار الدفع" ? 'selected' : '' }}>انتظار الدفع</option>
                                    <option value="لم يتم الدفع" {{ old('payment_status', $order->payment_status) == "لم يتم الدفع" ? 'selected' : '' }}>لم يتم الدفع</option>
                                </select>
                                @error('payment_status')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
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

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add new row
        document.getElementById('addRow').addEventListener('click', function() {
            const tbody = document.querySelector('#productTable tbody');
            const newRow = tbody.lastElementChild.cloneNode(true);
            
            // Clear values
            newRow.querySelectorAll('input').forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
            
            // Clear select values
            newRow.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });
            
            tbody.appendChild(newRow);
        });

        // Remove row
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                const tbody = document.querySelector('#productTable tbody');
                if (tbody.children.length > 1) {
                    e.target.closest('tr').remove();
                }
            }
        });
    });
</script>
@endsection
