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

@section('content')

<div class="container-fluid py-4">

            <div class="col-md-12"> <!--begin::Quick Example-->
                <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
                    <div class="card-header">
                        <div class="card-title">Add New Order</div>
                    </div> <!--end::Header--> <!--begin::Form-->
                    <form action="{{route('orders.store')}}" method="POST" enctype="multipart/form-data"> <!--begin::Body-->
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">العنوان <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="location" placeholder="العنوان" required value="{{old('location')}}">
                                    <div style="color: red;">
                                        {{$errors->first('location')}}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">اسم المرسل اليه <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="client_name" placeholder="اسم المرسل اليه" required value="{{old('client_name')}}">
                                    <div style="color: red;">
                                        {{$errors->first('client_name')}}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">حالة الطلب <span style="color: red;">*</span></label>
                                    <select name="status" id="" class="form-control" required>
                                        <option value="">اختر حالة الطلب</option>
                                        @foreach(\App\Enums\OrderStatuses::cases() as $status)
                                            <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                                {{ $status->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div style="color: red;">
                                        {{$errors->first('status')}}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">رقم الهاتف </label>
                                    <input type="text" class="form-control" name="client_phone" placeholder="رقم الهاتف" value="{{old('client_phone')}}">
                                    <div style="color: red;">
                                        {{$errors->first('client_phone')}}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">المحافظه</label>
                                    <input type="text" class="form-control" name="city" placeholder="المحافظه" value="{{old('city')}}">
                                    <div style="color: red;">
                                        {{$errors->first('city')}}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">مكتب البريد </label>
                                    <input type="text" class="form-control" name="post_office" placeholder="مكتب البريد" value="{{old('post_office')}}">
                                    <div style="color: red;">
                                        {{$errors->first('post_office')}}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">المقدم</label>
                                    <input type="number" class="form-control" name="deposited" placeholder="المقدم" value="{{old('deposited', 0)}}"  step="1" min="0" max="100">
                                    <div style="color: red;">
                                        {{$errors->first('deposited')}}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">جاى عن طريق </label>
                                    <input type="text" class="form-control" name="come_from" placeholder="جاى عن طريق" value="{{old('come_from')}}">
                                    <div style="color: red;">
                                        {{$errors->first('come_from')}}
                                    </div>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label class="form-label">حالة دفع الطلب </label>
                                    <select name="payment_status" id="" class="form-control" >
                                        <option value="">حالة دفع الطلب </option>
                                        <option value="تم الدفع" {{old('status') == "تم الدفع" ? 'selected' : ''}}>تم الدفع</option>
                                        <option value="انتظار الدفع" {{old('status') == "انتظار الدفع" ? 'selected' : ''}}>انتظار الدفع</option>
                                        <option value="لم يتم الدفع" {{old('status') == "لم يتم الدفع" ? 'selected' : ''}}>لم يتم الدفع</option>
                                    </select>
                                    <div style="color: red;">
                                        {{$errors->first('payment_status')}}
                                    </div>
                                </div>

{{--                                <div class="mb-3 col-md-6">--}}
{{--                                    <label class="form-label">Note </label>--}}
{{--                                    <textarea type="text" class="form-control" name="note" value="{{old('note')}}" placeholder="Note"></textarea>--}}
{{--                                    <div style="color: red;">--}}
{{--                                        {{$errors->first('note')}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                            </div>

{{--                            table       --}}
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Products and Colors</label>
                                <table class="table table-bordered" id="productTable">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Numbers</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Initial Row -->
                                    <tr>
                                        <td>
                                            <select class="form-control product-select" name="products[]" required>
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
                                            <input type="number" step="0.01" class="form-control" name="sizes[]" placeholder="المقاس" value="{{request('size')}}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="quantities[]" placeholder="عدد" value="{{request('quantity', 1)}}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary" id="addRow">Add Row</button>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div> <!--end::Footer-->
                    </form> <!--end::Form-->
                </div> <!--end::Quick Example--> <!--begin::Input Group-->
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
