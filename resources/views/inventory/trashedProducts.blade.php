@extends('inc.layout')

@section('content')
<div class="card shadow-lg rounded">
    <div class="card-header bg-danger text-white text-center fs-4 fw-bold">
        Trashed Products
    </div>
    <div class="card-body">
        <div class="table-container" style="overflow-x:auto;">
            <table class="table table-hover table-bordered shadow-sm" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">SKU</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$product->name}}</td>
                        <td>{{$product->category->name}}</td>
                        <td>{{$product->sku}}</td>
                        <td>{{$product->price}}</td>
                        <td>
                            {{$product->quantity}}
                            @if($product->quantity < 5)
                                <span class="badge bg-danger rounded-pill">Low Stock!</span>
                            @endif
                        </td>
                        <td class="d-flex align-items-center gap-2">
                            @if($product->trashed())
                                <form action="{{ route('inventory.restoreproduct') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ encrypt($product->id) }}">
                                    <button class="btn btn-success btn-sm rounded-3 d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Restore product">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Restore
                                    </button>
                                </form>
                            @else
                            @endif

                            <!-- Permanent Delete Button -->
                            <form action="{{ route('inventory.forcedeleteproduct') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ encrypt($product->id) }}">
                                <button class="btn btn-dark btn-sm rounded-3 d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Permanently">
                                    <i class="bi bi-trash-fill me-1"></i> Permanent Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No Products Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
