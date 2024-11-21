@extends('inc.layout')

@section('content')
<div class="card shadow-lg rounded">
    <div class="card-header bg-success text-white text-center fs-4 fw-bold">
        Products Under "{{$category->name}}"
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
                    @forelse ($category->products as $product)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$product->name}}</td>
                        <td>{{$product->category->name}}</td>
                        <td>{{$product->sku}}</td>
                        <td>{{$product->price}}</td>
                        <td>
                            <form action="{{route('inventory.managestock')}}" method="POST" class="d-flex align-items-center gap-2">
                                @csrf
                                <input type="hidden" name="id" value="{{encrypt($product->id)}}">
                                <input type="number" class="form-control form-control-sm w-auto" name="quantity"  value="{{$product->quantity}}">
                                <button class="btn btn-warning btn-sm">Update Stock</button>
                            </form>
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
                                <button class="btn btn-info btn-sm rounded-3 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ encrypt($product->id) }}" data-category-id="{{ encrypt($product->category->id) }}" data-name="{{ $product->name }}" data-sku="{{ $product->sku }}" data-price="{{ $product->price }}" data-quantity="{{ $product->quantity }}" data-bs-placement="top" title="Edit product">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </button>

                                <form action="{{ route('inventory.deleteproduct') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ encrypt($product->id) }}">
                                    <button class="btn btn-danger btn-sm rounded-3 d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete product">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </form>
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

<!-- Add Product Modal -->
<div class="modal fade" id="addproductModal" tabindex="-1" aria-labelledby="addproductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h1 class="modal-title fs-5" id="addproductModalLabel">Add Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('inventory.addproduct')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="category_id" class="form-label fs-5">Category:</label>
                        <select class="form-control" name="category_id" id="category_id" required>
                            <option disabled selected>Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{encrypt($category->id)}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label fs-5">Product Name:</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter product name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku" class="form-label fs-5">SKU:</label>
                                <input type="text" class="form-control" name="sku" id="sku" placeholder="Enter SKU" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="form-label fs-5">Price:</label>
                                <input type="text" class="form-control" name="price" id="price" placeholder="Enter price" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity" class="form-label fs-5">Quantity:</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" min="5" value="5" placeholder="Enter quantity" required>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-success">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('inventory.updateproduct')}}" method="POST">
                    @csrf
                    <input type="hidden" id="edit-id" name="id">
                    <input type="hidden" id="edit-category-id" name="oldcategory_id">
                    <div class="form-group">
                        <label for="category_id" class="form-label fs-5">Category:</label>
                        <select class="form-control" name="category_id" id="edit-category_id" required>
                            <option disabled selected>Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{encrypt($category->id)}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label fs-5">Product Name:</label>
                                <input type="text" class="form-control" name="name" id="edit-name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku" class="form-label fs-5">SKU:</label>
                                <input type="text" class="form-control" name="sku" id="edit-sku" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="form-label fs-5">Price:</label>
                                <input type="text" class="form-control" name="price" id="edit-price" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity" class="form-label fs-5">Quantity:</label>
                                <input type="number" class="form-control" name="quantity" id="edit-quantity" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-warning">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
