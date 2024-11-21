@extends('inc.layout')
@section('content')

<div class="d-flex justify-content-end gap-3 align-items-center py-3">
    <a href="{{route('inventory.trashedcategories')}}" class="text-decoration-none">
        <button class="btn btn-danger d-flex align-items-center">
            <i class="bi bi-trash me-2"></i>
            <span>Trashed Categories</span>
        </button>
    </a>
    <a href="{{route('inventory.categorycsv')}}" class="text-decoration-none">
        <button class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-file-earmark-arrow-down me-2"></i>
            <span>Generate CSV</span>
        </button>
    </a>
</div>


<div class="d-flex justify-content-center">
    <div class="card rounded shadow-sm col-lg-10">
        <div class="card-header bg-success text-white fs-4 d-flex justify-content-between align-items-center">
            <span>Categories</span>
            <button type="button" class="btn btn-primary btn-sm" data-bs-placement="top" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Add Category">
                <i class="bi bi-plus-circle"></i> Add
            </button>
        </div>
        <div class="card-body">
            <div class="table-container" style="overflow: auto">
                <table class="table table-striped table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col" colspan="2" class="text-center">Name</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-center">{{$category->name}}</td>
                            <td>  <a href="{{route('inventory.categoryproducts',encrypt($category->id))}}" title="Products under {{$category->name}}"><span class="badge bg-primary sm rounded-pill">show products</span></a></td>
                            <td class="d-flex justify-content-center align-items-center gap-2">
                                @if($category->trashed())
                                    <form action="{{route('inventory.restorecategory')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{encrypt($category->id)}}">
                                        <button class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Restore Category">
                                            <i class="bi bi-arrow-clockwise"></i> Restore
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-info btn-sm" data-bs-toggle='modal' data-bs-target="#editModal" data-id="{{encrypt($category->id)}}" data-name="{{$category->name}}" data-bs-placement="top" title="Edit Category">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <form action="{{route('inventory.deletecategory')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{encrypt($category->id)}}">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Category" onclick="return confirm('Are you sure you want to Trash this Category {{$category->name}}?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                                <form action="{{route('inventory.forcedeletecategory')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{encrypt($category->id)}}">
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Permanently" onclick="return confirm('Are you sure you want to delete this category {{$category->name}} permanently?')">
                                        <i class="bi bi-trash"></i> Delete Permanently
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @empty
                            <tr><td colspan=3 class="text-center">No Data Found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-success text-white">
            <h1 class="modal-title fs-5" id="addCategoryModalLabel">Add Category</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('inventory.addcategory')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label fs-4">Category:</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter category">
            </div>
            <div class="d-flex justify-content-end m-1">
                <button class="btn btn-success">Add Category</button>
            </div>
            </form>
        </div>
        </div>
    </div>
</div>

<!--Edit Category Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('inventory.updatecategory')}}" method="POST" >
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </div>
            </form>
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
            var name = button.getAttribute('data-name');

            // Update the modal's input fields
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
        });
    });
</script>
@endsection

