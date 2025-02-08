@extends('layout.app-dashboard')

@section('title', $title)
<style>
    ul {
        list-style: none;
        padding-left: 1rem;
    }
    li {
        margin-bottom: 0.5rem;
    }
    strong {
        color: #007bff;
    }
</style>

@section('content')


        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>منتاجات قيد الطلب</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <ul class="list-group">
                                    @foreach($data as $productName => $colors)
                                        <li class="list-group-item">
                                            <strong>المنتج : {{$productName}}</strong>
                                        @foreach($colors as $colorName => $sizes)
                                            <li class="list-group-item">
                                                <span>اللون : {{$colorName}}</span>
                                                <ul class="list-group mt-1">
                                                    @foreach($sizes as $sizeNumber => $quantity)
                                                        <li class="list-group-item">
                                                            <span>
                                                                المقاس :  {{$sizeNumber}}
                                                            </span>
                                                            <br>
                                                             العدد : {{$quantity}}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                            @endforeach
                                            </li>
                                        @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@stop
