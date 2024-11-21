@extends('inc.layout')
@section('content')
<h1 class="mb-4 text-center fw-bold text-uppercase">Dashboard</h1>
<div class="row text-center g-4">

    <!-- Products Card -->
    <div class="col-md-4">
        <a href="{{ route('inventory.product') }}" class="text-decoration-none">
            <div class="card shadow bg-success text-white border-0 rounded-3 d-flex align-items-center justify-content-center">
                <div class="card-body">
                    <i class="bi bi-box-seam-fill fs-1 mb-3"></i>
                    <h2 class="fw-bold mb-1 fs-3">{{ $productCount }}</h2>
                    <p class="fs-5 mb-0">Products</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Stock Value Card -->
    <div class="col-md-4">
        <a href="{{ route('inventory.product') }}" class="text-decoration-none">
            <div class="card shadow bg-info text-white border-0 rounded-3 d-flex align-items-center justify-content-center">
                <div class="card-body">
                    <i class="bi bi-currency-rupee fs-1 mb-3"></i>
                    <h2 class="fw-bold mb-1 fs-3">{{ number_format($stockValue, 2) }}</h2>
                    <p class="fs-5 mb-0">Stock Value</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Under Stock Products Card -->
    <div class="col-md-4">
        <a href="{{ route('inventory.product') }}" class="text-decoration-none">
            <div class="card shadow bg-danger text-white border-0 rounded-3 d-flex align-items-center justify-content-center">
                <div class="card-body">
                    <i class="bi bi-box-arrow-down fs-1 mb-3"></i>
                    <h2 class="fw-bold mb-1 fs-3">{{ $underStock }}</h2>
                    <p class="fs-5 mb-0">Under Stock Products</p>
                </div>
            </div>
        </a>
    </div>

</div>
@endsection
