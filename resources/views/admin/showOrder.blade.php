@extends('admin/layout')
@section('container')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Order List</h2>            
            <div>
                <a href="{{ url('admin/dashboard') }}">
                    <button type="button" class="btn btn-success">Back</button>
                </a>
            </div>
            
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingOrders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->product->product_name }}</td>
                            <td>${{ number_format($order->total_discounted_price, 2) }}</td>
                            <td>{{ $order->quantity }}</td>

                            <td>
                                <form action="{{ route('admin.showOrder.status.change', ['order_id' => $order->order_id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    
                                    <div class="form-group">
                                        <label for="order">Status:</label>
                                        <select name="status" class="form-control">
                                            <option value="{{ $order->order_id }}" selected>{{ ucfirst($order->status) }}</option>
                                            <option value="completed">Completed</option>
                                            <option value="canceled">Canceled</option>
                                            <option value="delivered">Delivered</option>
                                        </select>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination links -->
            <div class="d-flex justify-content-center">
                {{ $pendingOrders->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
