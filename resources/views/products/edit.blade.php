@extends('layout.app-dashboard')

@section('title', $title)

@section('content')


    <div class="container-fluid py-4">

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Orders Table</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <form action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data"> <!--begin::Body-->
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">اسم المنتج <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="name" placeholder="اسم المنتج" required value="{{old('name', $product->name)}}">
                                            <div style="color: red;">
                                                {{$errors->first('name')}}
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">سعر المنتج<span style="color: red;">*</span></label>
                                            <input type="number" step="0.001" class="form-control" name="price" placeholder="سعر المنتج" required value="{{old('price', $product->price)}}">
                                            <div style="color: red;">
                                                {{$errors->first('price')}}
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> تكلفه المنتج<span style="color: red;">*</span></label>
                                            <input type="number" step="0.001" class="form-control" name="cost" placeholder="تكلفه المنتج" required value="{{old('cost', $product->cost)}}">
                                            <div style="color: red;">
                                                {{$errors->first('cost')}}
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">نوع المنتج <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="type" placeholder="نوع المنتج" required value="{{old('type', $product->type)}}">
                                            <div style="color: red;">
                                                {{$errors->first('type')}}
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">اسم الخياط</label>
                                            <input type="text" class="form-control" name="tailor_name" placeholder="اسم الخياط" value="{{old('tailor_name', $product->tailor_name)}}">
                                            <div style="color: red;">
                                                {{$errors->first('tailor_name')}}
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">وصف المنتج</label>
                                            <textarea class="form-control" name="description" placeholder="وصف المنتج">{{ old('description', $product->description) }}</textarea>
                                            <div style="color: red;">
                                                {{ $errors->first('description') }}
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div> <!--end::Footer-->
                            </form> <!--end::Form-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
