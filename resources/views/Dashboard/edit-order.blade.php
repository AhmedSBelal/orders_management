@extends('layout.app-dashboard')

@section('title', $title)

@section('css')
    <style>
        #productTable {
            margin-bottom: 15px;
        }

        #productTable th,
        #productTable td {
            vertical-align: middle;
        }

        .remove-row {
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
        <div class="container-fluid py-4">
            <div class="col-md-12">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header" style="margin-bottom: -50px">
                        <div class="card-title">
                            <img src="{{ asset('media/agyad_maka.jpeg') }}" alt="Update Icon" width="170" height="170" class="me-2">
{{--                            Update Order--}}
                        </div>
                    </div>
                    <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">العنوان <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="location" placeholder="العنوان" required value="{{ old('location', $order->location) }}">
                                        <div style="color: red;">
                                            {{ $errors->first('location') }}
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">اسم المرسل اليه <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="client_name" placeholder="اسم المرسل اليه " required value="{{ old('client_name', $order->client_name) }}">
                                        <div style="color: red;">
                                            {{ $errors->first('client_name') }}
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">حالة الطلب <span style="color: red;">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="">اختر حالة الطلب</option>
                                            @foreach(\App\Enums\OrderStatuses::cases() as $status)
                                                <option value="{{ $status->value }}" {{ old('status', $order->status) == $status->value ? 'selected' : '' }}>
                                                    {{ $status->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div style="color: red;">
                                            {{ $errors->first('status') }}
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">رقم الهاتف</label>
                                        <input type="text" class="form-control" name="client_phone" placeholder="رقم الهاتف" value="{{ old('client_phone', $order->client_phone) }}">
                                        <div style="color: red;">
                                            {{ $errors->first('client_phone') }}
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">المحافظه</label>
                                        <input type="text" class="form-control" name="city" placeholder="المحافظه" value="{{old('city', $order->city)}}">
                                        <div style="color: red;">
                                            {{$errors->first('city')}}
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">مكتب البريد </label>
                                        <input type="text" class="form-control" name="post_office" placeholder="مكتب البريد" value="{{old('post_office', $order->post_office)}}">
                                        <div style="color: red;">
                                            {{$errors->first('post_office')}}
                                        </div>
                                    </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">المقدم</label>
                                    <input type="number" class="form-control" name="deposited" placeholder="المقدم" value="{{old('deposited', $order->deposited)}}" step="0.01">
                                    <div style="color: red;">
                                        {{$errors->first('deposited')}}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">جاى عن طريق </label>
                                    <input type="text" class="form-control" name="come_from" placeholder="جاى عن طريق" value="{{old('come_from', $order->come_from)}}">
                                    <div style="color: red;">
                                        {{$errors->first('come_from')}}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label class="form-label">حالة دفع الطلب <span style="color: red;">*</span></label>
                                    <select name="payment_status" id="" class="form-control" required>
                                        <option value="">حالة دفع الطلب </option>
                                        <option value="تم الدفع" {{old('status', $order->payment_status) == "تم الدفع" ? 'selected' : ''}}>تم الدفع</option>
                                        <option value="انتظار الدفع" {{old('status', $order->payment_status) == "انتظار الدفع" ? 'selected' : ''}}>انتظار الدفع</option>
                                        <option value="لم يتم الدفع" {{old('status', $order->payment_status) == "لم يتم الدفع" ? 'selected' : ''}}>لم يتم الدفع</option>
                                    </select>
                                    <div style="color: red;">
                                        {{$errors->first('payment_status')}}
                                    </div>
                                </div>

                            <!-- Products and Colors Table -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Products and Colors</label>
                                <table class="table table-bordered" id="productTable">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Quantity</th>
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
                                                <input type="text" class="form-control" name="sizes[]" placeholder="Size" value="{{ $product->pivot->size }}" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="quantities[]" placeholder="Quantity" value="{{ $product->pivot->quantity }}" required>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch d-flex px-4">
                                                    <input type="hidden" name="is_done[{{ $loop->index }}]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="is_done[{{ $loop->index}}]" value="1" {{ $product->pivot->is_done ? 'checked' : '' }}>
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
                                                <input type="number" class="form-control" name="sizes[]" placeholder="Size" min="1" step="0.01" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="quantities[]" placeholder="Quantity" min="1" required>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch d-flex px-4">
                                                    <input class="form-check-input" name="is_done[]" type="checkbox">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary" id="addRow">Add Row</button>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@section('js')
    <script>
        document.getElementById('addRow').addEventListener('click', function () {
            // Clone the first row
            const table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
            const newRow = table.rows[0].cloneNode(true);

            // Clear selected values in the new row
            newRow.querySelector('.product-select').selectedIndex = 0;
            newRow.querySelector('.color-select').selectedIndex = 0;
            newRow.querySelector('input[name="sizes[]"]').value = '';
            newRow.querySelector('input[name="quantities[]"]').value = '';

            // Add the new row to the table
            table.appendChild(newRow);
        });

        // Remove row functionality
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-row')) {
                const row = event.target.closest('tr');
                if (document.getElementById('productTable').getElementsByTagName('tbody')[0].rows.length > 1) {
                    row.remove();
                } else {
                    alert("You cannot remove the last row.");
                }
            }
        });
    </script>
@endsection
