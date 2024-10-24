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

<table class="table">
	<thead>
		<tr>
			<th>Order ID</th>
			<th>Product Name</th>
			<th>User Name</th>
			<th>Order Amount</th>
			<th>Quantity</th>
			<th>Status</th>
		</tr>
	</thead>

	<tbody>
		@foreach($orders as $order)
			<tr>
				<td>{{ $order->order_id }}</td>
				<td>{{ $order->product->product_name }}</td>
				<td>{{ $order->user->name }}</td>
				<td>{{ $order->order_amount }}</td>
				<td>{{ $order->quantity }}</td>
				<td>
					@if($order->status == 'pending')
						<span class="badge bg-warning">New</span>
					@endif
				</td>
				<td>
					<form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
						@csrf
						@method('PUT')
						<select name="status" onchange="this.form.submit()">
							<option value="pending" {{ $order->status == 'pending' ? 'selected' : ''}}>Pending</option>

							<option value="completed" {{ $order->status == 'completed' ? 'selected' : ''}}>Completed</option>

							<option value="delivered" {{ $order->status == 'delivered' ? 'selected' : ''}}>Delivered</option>

							<option value="canceled" {{ $order->status == 'canceled' ? 'selected' : ''}}>Canceled</option>
						</select>
					</form>
				</td>
			</tr>
			@endforeach

	</tbody>
</table>

@endsection