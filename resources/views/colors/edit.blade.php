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
                            <form action="{{route('colors.update', $color->id)}}" method="POST" enctype="multipart/form-data"> <!--begin::Body-->
                                @method('PUT')
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">اسم اللون <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="name" placeholder="اسم اللون" required value="{{old('name', $color->name)}}">
                                            <div style="color: red;">
                                                {{$errors->first('name')}}
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
