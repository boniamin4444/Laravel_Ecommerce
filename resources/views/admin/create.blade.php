@extends('admin/layout')
@section('container')
 @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container mt-4">
       <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf <!-- This is important to prevent CSRF attacks -->
    
    <div class="form-group">
        <label for="category">Category:</label>
        <select name="category_id" class="form-control">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label for="product_name">Product Name:</label>
        <input type="text" class="form-control" name="product_name" required>
    </div>

    <div class="form-group">
        <label for="details">Details:</label>
        <textarea class="form-control" name="details" required></textarea>
    </div>

    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" class="form-control" name="price" step="0.01" required>
    </div>

    <div class="form-group">
        <label for="stock">Stock:</label>
        <input type="number" class="form-control" name="stock" required>
    </div>

    <div class="form-group">
        <label for="image">Image:</label>
        <input type="file" class="form-control" name="image" required>
    </div>

    <div class="form-group">
        <label>Status:</label>
        <div>
            <label><input type="radio" name="status" value="active" required> Active</label>
            <label><input type="radio" name="status" value="inactive"> Inactive</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
    </form>       
</div>
@endsection
