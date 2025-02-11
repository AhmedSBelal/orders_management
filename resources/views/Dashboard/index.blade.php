@extends('layout.app-dashboard')

@section('title', $title)

@section('css')


    <style>
        .hidden {
            display: none;
        }
    </style>

@endsection

    @section('content')

        <!-- Modal Structure -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">تأكيد الحذف</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="text-lg mb-4">هل انت متأكد من حذف هذا الاوردر؟</p>
                    </div>
                    <div class="modal-footer">
                        <button onclick="confirmDeletion()" class="btn btn-danger">نعم، امسح</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لا</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">إضافة وإنشاء طلبية</p>
                                    </div>
                                </div>
                                <div class="col-4 text-start">
                                    <a href="{{route('orders.create')}}">
                                        <div class="icon icon-shape bg-primary shadow text-center border-radius-md">
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">طلبيات تحت التجهيز</p>
                                    </div>
                                </div>
                                <div class="col-4 text-start">
                                    <a href="{{route('in-processing')}}">
                                        <div class="icon icon-shape bg-primary shadow text-center border-radius-md">
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <br>

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Search Order</h3>
                        </div>
                        <form action="" method="GET">
                            @csrf
                            <div class="card-body row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">العنوان</label>
                                    <input type="text" class="form-control" name="location" placeholder="العنوان" value="{{request('location')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">اسم المرسل اليه</label>
                                    <input type="text" class="form-control" name="client_name" placeholder="اسم المرسل اليه" value="{{request('client_name')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control" name="client_phone" placeholder="رقم الهاتف" value="{{request('client_phone')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">حالة الطلب</label>
                                    <select name="status" id="form-label" class="form-control">
                                        <option value="">اختر حالة الطلب</option>
                                        @foreach(\App\Enums\OrderStatuses::cases() as $status)
                                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                                {{ $status->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">السعر</label>
                                    <input type="text" class="form-control" name="total_price" placeholder="السعر" value="{{request('total_price')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">المقدم</label>
                                    <input type="text" class="form-control" name="deposited" placeholder="المقدم" value="{{request('deposited')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">جاى عن طريق</label>
                                    <input type="text" class="form-control" name="come_from" placeholder="جاى عن طريق" value="{{request('come_from')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">حالة دفع الطلب </label>
                                    <select name="payment_status" id="" class="form-control">
                                        <option value="">حالة دفع الطلب </option>
                                        <option value="تم الدفع" {{request('payment_status') == "تم الدفع" ? 'selected' : ''}}>تم الدفع</option>
                                        <option value="انتظار الدفع" {{request('payment_status') == "انتظار الدفع" ? 'selected' : ''}}>انتظار الدفع</option>
                                        <option value="لم يتم الدفع" {{request('payment_status') == "لم يتم الدفع" ? 'selected' : ''}}>لم يتم الدفع</option>
                                    </select>
                                    <div style="color: red;">
                                        {{$errors->first('payment_status')}}
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">تاريخ الاضافة</label>
                                    <input type="date" class="form-control" name="created_at" value="{{request('created_at')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">تاريخ التعديل</label>
                                    <input type="date" class="form-control" name="updated_at" value="{{request('updated_at')}}">
                                </div>
                                <div class="form-group col-md-3 mb-3 d-flex align-items-end gap-2">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                    <a href="{{route('orders.index')}}" class="btn btn-success">Reset</a>
                                </div>
                            </div>
                        </form> <!--end::Form-->
                    </div> <!-- /.card -->
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Orders Table</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الرقم</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">العنوان</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">اسم المرسل اليه</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">رقم الهاتف</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">حالة الطلب</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">السعر</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المقدم</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">عمليات على الطلب</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="align-middle text-center">
                                                <div class="d-flex px-2 py-1">{{Str::limit($loop->iteration, 20)}}</div>
                                            </td>
                                            <td class="align-middle text-center">
                                               {{Str::limit($order->location, 20)}}
                                            </td>
                                            <td class="align-middle text-center">
                                                {{Str::limit($order->client_name, 20)}}
                                            </td>
                                            <td class="align-middle text-center">
                                                {{Str::limit($order->client_phone, 20)}}
                                            </td>
                                            <td class="align-middle text-center">
                                                {{Str::limit($order->status, 20)}}
                                            </td>
                                            <td class="align-middle text-center">
                                                {{Str::limit($order->total_price, 20)}}
                                            </td>
                                            <td class="align-middle text-center">
                                                {{Str::limit($order->deposited, 20)}}
                                            </td>
                                            <td class="align-middle text-center d-flex p-2">
                                                <a class="btn btn-success ms-2" href="{{route('orders.edit', $order->id)}}">Edit</a>
                                                <!-- Delete Button to Trigger Modal -->
                                                <form id="delete-order-form-{{ $order->id }}" action="{{ route('orders.destroy', $order->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger" onclick="showConfirmationModal({{ $order->id }})">حذف</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


@endsection

@section('js')

    <script>
        let deleteFormId = null;

        function showConfirmationModal(orderId) {
            deleteFormId = orderId;
            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        }

        function confirmDeletion() {
            if (deleteFormId !== null) {
                document.getElementById(`delete-order-form-${deleteFormId}`).submit();
            }
        }
    </script>

@stop
