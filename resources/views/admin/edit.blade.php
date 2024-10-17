@extends('admin/layout')
@section('container')
 @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif   
<div class="container mt-5">
    <h2 class="mb-4">Edit Product</h2>  

    <form action="{{ route('products.update', \Illuminate\Support\Facades\Crypt::encrypt($product->id)) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="category_id">Category:</label>
            <select name="category_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="product_name">Product Name:</label>
            <input type="text" class="form-control" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="details">Details:</label>
            <textarea class="form-control" name="details" required>{{ old('details', $product->details) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="price">Price:</label>
            <input type="number" class="form-control" name="price" step="0.01" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="stock">Stock:</label>
            <input type="number" class="form-control" name="stock" value="{{ old('stock', $product->stock) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="image">Image:</label>
            <input type="file" class="form-control" name="image">
            @if($product->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" width="100">
                </div>
            @endif
        </div>

        <div class="form-group mb-3">
            <label>Status:</label>
            <div>
                <label><input type="radio" name="status" value="active" {{ $product->status == 'active' ? 'checked' : '' }} required> Active</label>
                <label><input type="radio" name="status" value="inactive" {{ $product->status == 'inactive' ? 'checked' : '' }}> Inactive</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
@endsection
