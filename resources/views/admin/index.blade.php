@extends('admin/layout')
@section('container')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
 {{session('success')}}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@else
@endif
   
  
     
    
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Product List</h2>            
            <div>
                 <a href="{{url('admin/dashboard')}}">
                    <button type="button" class="btn btn-success">
                       Back
                    </button>
                </a>
            </div>
            <div class="mb-4" style="margin-top: 10px;">
                <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
            </div>

            @if ($products->count())
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->category ? $product->category->category_name : 'No Category' }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" width="50">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('products.edit',\Illuminate\Support\Facades\Crypt::encrypt($product->id) ) }}" class="btn btn-warning btn-sm">Edit</a>
                                    
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination links -->
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="alert alert-warning">
                    No products found.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
