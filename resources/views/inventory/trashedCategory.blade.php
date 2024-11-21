@extends('inc.layout')

@section('content')
<div class="card shadow-lg rounded">
    <div class="card-header bg-danger text-white text-center fs-4 fw-bold">
        Trashed Categories
    </div>
    <div class="card-body">
        <div class="table-container" style="overflow-x:auto;">
            <table class="table table-hover table-bordered shadow-sm" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Name</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$category->name}}</td>

                        <td class="d-flex align-items-center gap-2">
                            @if($category->trashed())
                                <form action="{{ route('inventory.restorecategory') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ encrypt($category->id) }}">
                                    <button class="btn btn-success btn-sm rounded-3 d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Restore product">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Restore
                                    </button>
                                </form>
                            @else
                            @endif

                            <!-- Permanent Delete Button -->
                            <form action="{{ route('inventory.forcedeletecategory') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ encrypt($category->id) }}">
                                <button class="btn btn-dark btn-sm rounded-3 d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Permanently">
                                    <i class="bi bi-trash-fill me-1"></i> Permanent Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No Categories Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
