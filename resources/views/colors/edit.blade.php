{{-- resources/views/colors/edit.blade.php --}}
@extends('layout.app-dashboard')

@section('title', $title)

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>تعديل اللون</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <form action="{{route('colors.update', $color->id)}}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">اسم اللون <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="name" placeholder="اسم اللون" required value="{{old('name', $color->name)}}">
                                            <div style="color: red;">
                                                {{$errors->first('name')}}
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">صورة اللون</label>
                                            <input type="file" class="form-control" name="photo" accept="image/*">
                                            <small class="text-muted">اتركه فارغاً إذا كنت لا تريد تغيير الصورة</small>
                                            
                                            @if($color->photo)
                                                <div class="mt-3">
                                                    <p class="form-label">الصورة الحالية:</p>
                                                    <img src="{{ asset('storage/' . $color->photo) }}" alt="{{ $color->name }}" width="100" class="rounded" style="object-fit: cover;">
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" name="delete_photo" id="delete_photo">
                                                        <label class="form-check-label" for="delete_photo">
                                                            حذف الصورة الحالية
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif

                                            <div style="color: red;">
                                                {{$errors->first('photo')}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection