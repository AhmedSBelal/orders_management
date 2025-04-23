@extends('layout.app-dashboard')

@section('title', $title)

@section('content')


    <div class="container-fluid py-4">

        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">إضافة وإنشاء لون</p>
                                </div>
                            </div>
                            <div class="col-4 text-start">
                                <a href="{{route('colors.create')}}">
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
                        <h3 class="card-title">Search Color</h3>
                    </div>
                    <form action="" method="GET">
                        @csrf
                        <div class="card-body row">
                            <div class="col-md-2 mb-3">
                                <label class="form-label">اسم المنتج</label>
                                <input type="text" class="form-control" name="name" placeholder="اسم المنتج" value="{{request('name')}}">
                            </div>
                            <div class="form-group col-md-3 mb-3 d-flex align-items-end gap-2">
                                <button class="btn btn-primary" type="submit">Search</button>
                                <a href="{{route('colors.index')}}" class="btn btn-success">Reset</a>
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
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">اسم اللون</th>
                                    <td></td>
{{--                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">عمليات على اللون</th>--}}
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($colors as $color)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="d-flex px-2 py-1">{{Str::limit($loop->iteration, 20)}}</div>
                                        </td>
                                        <td class="align-middle text-center">
                                            {{Str::limit($color->name, 20)}}
                                        </td>
                                        <td class="align-middle text-center d-flex p-2">
                                            <a class="btn btn-success ms-2" href="{{route('colors.edit', $color->id)}}">Edit</a>
                                            <form action="{{route('colors.destroy', $color->id)}}" method="POST">
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

@endsection
