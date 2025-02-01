@extends('layout.app-dashboard')

@section('title', $title)

@section('content')


    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 ">
                        <li class="breadcrumb-item text-sm ps-2"><span class="opacity-5 text-dark">لوحات القيادة</span></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">ادارة الاوردرات</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">ادارة الاوردرات</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 px-0" id="navbar">
                    <ul class="navbar-nav me-auto ms-0 justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <form action="{{route('logout')}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm mb-0 ms-3">تسجيل الخروج</button>
                            </form>
                        </li>
                        <li class="nav-item d-xl-none pe-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0">
                                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown ps-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell cursor-pointer"></i>
                            </a>
                            <ul class="dropdown-menu  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="{{asset('assets/img/team-2.jpg')}}" class="avatar avatar-sm  ms-3 ">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">رسالة جديدة</span> من لور
                                                </h6>
                                                <p class="text-xs text-secondary mb-0 ms-auto">
                                                    <i class="fa fa-clock me-1"></i>
                                                    منذ 13 دقيقة
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="{{asset('assets/img/small-logos/logo-spotify.svg')}}" class="avatar avatar-sm bg-gradient-dark  ms-3 ">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">البوم جديد</span> بواسطة ترافيس سكوت
                                                </h6>
                                                <p class="text-xs text-secondary mb-0 ms-auto">
                                                    <i class="fa fa-clock me-1"></i>
                                                    يوم 1
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="avatar avatar-sm bg-gradient-secondary  ms-3  my-auto">
                                                <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <title>credit-card</title>
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                            <g transform="translate(1716.000000, 291.000000)">
                                                                <g transform="translate(453.000000, 454.000000)">
                                                                    <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                                                    <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    اكتمل الدفع بنجاح
                                                </h6>
                                                <p class="text-xs text-secondary mb-0 ms-auto">
                                                    <i class="fa fa-clock me-1"></i>
                                                    2 أيام
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
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
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">الطلبات المنتهية</p>
                                    </div>
                                </div>
                                <div class="col-4 text-start">
                                    <a href="">
                                        <div class="icon icon-shape bg-primary shadow text-center border-radius-md"></div>
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
                                    <label class="form-label">الموقع</label>
                                    <input type="text" class="form-control" name="location" placeholder="الموقع" value="{{request('location')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">اسم العميل</label>
                                    <input type="text" class="form-control" name="client_name" placeholder="اسم العميل" value="{{request('client_name')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">رقم العميل</label>
                                    <input type="text" class="form-control" name="client_phone" placeholder="رقم العميل" value="{{request('client_phone')}}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">حالة الطلب</label>
                                    <select name="status" id="form-label" class="form-control">
                                        <option value="">اختر حالة الطلب</option>
                                        <option value="قيد التنفيذ" {{ request('status') == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                                        <option value="وصل" {{ request('status') == 'وصل' ? 'selected' : '' }}>وصل</option>
                                        <option value="مرتجع" {{ request('status') == 'مرتجع' ? 'selected' : '' }}>مرتجع</option>
                                        <option value="مرتجع" {{ request('status') == 'تم الالغاء' ? 'selected' : '' }}>تم الالغاء</option>
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
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الموقع</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">اسم العميل</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">رقم العميل</th>
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
                                                <a class="btn btn-success ms-2" href="{{route('orders.show', $order->id)}}">Show</a>
                                                <form action="{{route('orders.destroy', $order->id)}}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
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

    </main>

    <div class="fixed-plugin">
        <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
            <i class="fa fa-cog py-2"> </i>
        </a>
        <div class="card shadow-lg ">
            <div class="card-header pb-0 pt-3 ">
                <div class="float-end">
                    <h5 class="mt-3 mb-0">اعداداتك</h5>
                    <p>شاهد خصائصك المتاحة</p>
                </div>
                <div class="float-start mt-4">
                    <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <!-- End Toggle Button -->
            </div>
            <hr class="horizontal dark my-1">
            <div class="card-body pt-sm-3 pt-0">
                <!-- Sidebar Backgrounds -->
                <div>
                    <h6 class="mb-0">Sidebar Colors</h6>
                </div>
                <a href="javascript:void(0)" class="switch-trigger background-color">
                    <div class="badge-colors my-2 text-end">
                        <span class="badge filter bg-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
                    </div>
                </a>
                <!-- Sidenav Type -->
                <div class="mt-3">
                    <h6 class="mb-0">Sidenav Type</h6>
                    <p class="text-sm">Choose between 2 different sidenav types.</p>
                </div>
                <div class="d-flex">
                    <button class="btn btn-primary w-100 px-3 mb-2 active" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
                    <button class="btn btn-primary w-100 px-3 mb-2 me-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
                </div>
                <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
                <!-- Navbar Fixed -->
                <div class="mt-3">
                    <h6 class="mb-0">Navbar Fixed</h6>
                </div>
                <div class="form-check form-switch ps-0">
                    <input class="form-check-input mt-1 float-end me-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
                </div>
            </div>
        </div>
    </div>

@stop
