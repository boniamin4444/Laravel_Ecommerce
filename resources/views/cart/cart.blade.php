@extends('layouts.app')
@section('content')

<div class="container mt-5">
	@if(session('error'))
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		{{ session('error')}}
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
	@endif


	@if(session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('success')}}
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
	@endif
</div>

<div class="container mt-5">
	<h2>Cart</h2>
	<div class="row mb-2">
		<div class="col-md-1">
			<span class="float-left">
				<a href="{{ url('/') }}" class="btn btn-warning btn-sm">
					Back
				</a>
			</span>
		</div>

		<div class="col-md-2">
		  <span class="float-right">
			<form method="post" action="{{ route('cart.clear') }}">
			@csrf
			<button type="submit" class="btn btn-warning btn-sm">Clear Cart</button>
			</form>
		  </span>
		</div>
	</div>

	
	@if(session('cart'))
		<table class="table table-bordered">
			<thead>
				<tr>
				<th>Product name</th>
				<th>Image</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Total</th>
				<th>Action</th>
				</tr>
			</thead>
			<tbody>

				@php $subtotal = 0; @endphp

				@foreach(session('cart') as $id => $details)
					<tr>
						<td>{{ $details['name'] }}</td>
						<td><img src="{{ asset('storage/'.$details['image']) }}" width="100"></td>
						<td>{{ $details['quantity'] }}</td>
						<td>{{ $details['price'] }}</td>
						<td>{{ $details['price'] * $details['quantity'] }}</td>

						<td>
							<button class="btn btn-danger remove-from-cart" data-id="{{ $id }}">Remove</button>
						</td>
					</tr>

					@php $subtotal += $details['quantity'] * $details['price']; @endphp					
				@endforeach
			</tbody>
		</table>
		<h4>Subtotal: $<span id="subtotal">{{ $subtotal }}</span></h4>
		
		<!-- Coupon Section -->

		<form id="coupon-form" method="post" action="{{ route('cart.applyCoupon')}}">

			@csrf
			<div class="form-group">
				<label>Coupon Code:</label>
				<input type="text" name="coupon_code" class="form-control" placeholder="Enter Coupon Code">
			</div><br>
			<button type="submit" class="btn btn-primary">Apply Coupon</button>			
		</form>

		<div>
			<h4>Discount: $<span id="discount">{{ session('discount',0) }}</span></h4>
			<h4>Total after discount: $<span id="total">{{ $subtotal - session('discount',0)}}</span></h4>
		</div>

		<form action="{{ route('cart.placeOrder')}}" method="post">
			@csrf
			<button type="submit" class="btn btn-success">Place Order</button>
		</form>		
	@else
	<h3>Your Cart is Empty</h3>
	@endif
</div>
@endsection