@extends('layout.app-dashboard')

@section('title', $title)

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white pb-0">
                        <h6>منتجات قيد الطلب</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <ul class="list-group">
                                @foreach($data as $productName => $colors)
                                    <li class="list-group-item bg-light">
                                        <strong class="text-primary">المنتج: {{$productName}}</strong>
                                    </li>
                                    @foreach($colors as $colorName => $sizes)
                                        <li class="list-group-item">
                                            <span class="badge bg-info text-dark">اللون: {{$colorName}}</span>
                                            <ul class="list-group mt-2">
                                                @foreach($sizes as $sizeNumber => $quantity)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center ">
                                                        <span>المقاس: <strong>{{$sizeNumber}}</strong></span>
                                                        <span class="badge bg-success rounded-pill">العدد: {{$quantity}}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
