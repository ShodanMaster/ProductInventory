@extends('inc.layout')
@section('content')

<div class="d-flex justify-content-end gap-3 align-items-center py-3">
    <a href="{{route('inventory.trashedproducts')}}" class="text-decoration-none">
        <button class="btn btn-danger d-flex align-items-center">
            <i class="bi bi-trash me-2"></i>
            <span>Trashed Products</span>
        </button>
    </a>
    <a href="{{route('inventory.productcsv')}}" class="text-decoration-none">
        <button class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-file-earmark-arrow-down me-2"></i>
            <span>Generate CSV</span>
        </button>
    </a>
</div>


<div class="card rounded shadow-sm mt-2">
    <div class="card-header bg-success text-white fs-4 d-flex justify-content-between align-items-center">
        <span>Products</span>
        <button type="button" class="btn btn-primary btn-sm" data-bs-placement="top" data-bs-toggle="modal" data-bs-target="#addproductModal" title="Add product">
            <i class="bi bi-plus-circle"></i> Add
        </button>
    </div>
    <div class="card-body">
        <div class="table-container" style="overflow: auto">
            <table class="table table-striped table-hover" id="dataTable">
                <thead>
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
                    @if(!$product->category->trashed())
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
                                        <button class="btn btn-warning btn-sm">update stock</button>
                                    </form>
                                    @if($product->quantity < 5) <span class="badge bg-danger">low Stock!</span> @endif

                                </td>
                                <td class="d-flex align-items-center gap-2">
                                    <button class="btn btn-info btn-sm d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ encrypt($product->id) }}" data-category-id="{{ encrypt($product->category->id) }}" data-name= "{{ $product->name }}" data-sku="{{ $product->sku }}" data-price="{{ $product->price }}" data-quantity="{{ $product->quantity }}" data-bs-placement="top" title="Edit product">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>

                                    <form action="{{ route('inventory.deleteproduct') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ encrypt($product->id) }}">
                                        <button class="btn btn-warning btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete product" onclick="return confirm('Are you sure you want to Trash this product {{$product->name}}?')">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </form>

                                <!-- Permanent Delete Button -->
                                <form action="{{ route('inventory.forcedeleteproduct') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ encrypt($product->id) }}">
                                    <button class="btn btn-danger btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Permanently" onclick="return confirm('Are you sure you want to delete this product {{$product->name}} permanently?')">
                                        <i class="bi bi-trash me-1"></i> Permanently Delete
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endif
                    @empty
                        <tr><td colspan=7 class="text-center">No Data Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--Add product Modal -->
<div class="modal fade" id="addproductModal" tabindex="-1" aria-labelledby="addproductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-success text-white">
            <h1 class="modal-title fs-5" id="addproductModalLabel">Add product</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('inventory.addproduct')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="category_id" class="form-label fs-4">Category:</label>
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
                            <label for="name" class="form-label fs-4">Product:</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter product" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku" class="form-label fs-4">SKU:</label>
                            <input type="text" class="form-control" name="sku" id="sku" placeholder="Enter SKU" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price" class="form-label fs-4">Price:</label>
                            <input type="text" class="form-control" name="price" id="price" placeholder="Enter price" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quantity" class="form-label fs-4">Quantity:</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" min=1 placeholder="Enter Quantity" required>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end m-1">
                    <button class="btn btn-success">Add product</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

<!--Edit product Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="editModalLabel">Edit product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('inventory.updateproduct')}}" method="POST">
                    @csrf
                    <input type="hidden" id="edit-id" name="id">
                    <input type="hidden" id="edit-category-id" name="oldcategory_id">
                    <div class="form-group">
                        <label for="category_id" class="form-label fs-4">Category:</label>
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
                                <label for="name" class="form-label fs-4">Product:</label>
                                <input type="text" class="form-control" name="name" id="edit-name" placeholder="Enter product" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku" class="form-label fs-4">SKU:</label>
                                <input type="text" class="form-control" name="sku" id="edit-sku" placeholder="Enter SKU" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="form-label fs-4">Price:</label>
                                <input type="text" class="form-control" name="price" id="edit-price" placeholder="Enter price" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity" class="form-label fs-4">Quantity:</label>
                                <input type="number" class="form-control" name="quantity" min=1 id="edit-quantity" placeholder="Enter Quantity" required>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end m-1">
                        <button class="btn btn-success">Update product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget;
            // Extract info from data-* attributes
            var id = button.getAttribute('data-id');
            var oldcategory_id = button.getAttribute('data-category-id');
            var name = button.getAttribute('data-name');
            var sku = button.getAttribute('data-sku');
            var price = button.getAttribute('data-price');
            var quantity = button.getAttribute('data-quantity');

            // Update the modal's input fields
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-category-id').value = oldcategory_id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-sku').value = sku;
            document.getElementById('edit-price').value = price;
            document.getElementById('edit-quantity').value = quantity;
        });
    });
</script>
@endsection

