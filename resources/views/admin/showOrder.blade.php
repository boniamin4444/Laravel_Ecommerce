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
                            <th>Product id</th>
                            <th>Price</th>
                            <th>quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->product_id }}</td>
                                <td>${{ number_format($order->total_discounted_price, 2) }}</td>
                                <td>{{ $order->quantity}}</td>

                                <td>
                                    <form action="#" method="POST" style="display:inline;">
                                        @csrf
                                        @method('post')
                                        <div class="form-group">
                                            <label for="order">Status:</label>
                                            <select name="order_id" class="form-control">
                                                <option value="$order->id">{{ $order->status }}</option>
                                                <option value="completed ">completed</option>
                                                <option value="canceled">canceled</option>
                                                <option value="delivered">delivered</option>
                                            </select>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary">Update</button>
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
