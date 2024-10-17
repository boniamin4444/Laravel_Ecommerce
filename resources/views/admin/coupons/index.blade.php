@extends('admin/layout')
@section('container')

{{ session('success') }}

<div class="container">
	<h2>Manage Coupons</h2>

	<form action="{{ route('coupons.store')}}" method="post">
		@csrf
		<div class="form-group">
			<label>Coupon Code</label>
			<input type="text" name="coupon_code" class="form-control" required>
		</div>

		<div class="form-group">
			<label>Expire Date</label>
			<input type="date" name="expire_date" class="form-control" required>
		</div>

		<div class="form-group">
			<label>Value %</label>
			<input type="number" name="values" class="form-control" required>
		</div>

		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="active">Active</option>
				<option value="inactive">Inactive</option>
			</select>
		</div>
		<button type="submit" class="btn btn-primary">Create Coupon</button>
	</form>

	<table class="table mt-4">
		<thead>
			<tr>
				<th>Coupon Code</th>
				<th>Expire Date</th>
				<th>Value %</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($coupons as $coupon)
				<tr>
					<td>{{ $coupon->coupon_code }}</td>
					<td>{{ $coupon->expire_date }}</td>
					<td>{{ $coupon->value }}</td>
					<td>{{ $coupon->status }}</td>
					<td>
						<a href="{{ route('coupons.edit', $coupon->id)}}" class="btn btn-primary">Edit</a>
						<form action="{{ route('coupons.destroy',$coupon->id)}}" method="post" style="display: inline-block;">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn btn-danger">Delete</button>
						</form>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endsection