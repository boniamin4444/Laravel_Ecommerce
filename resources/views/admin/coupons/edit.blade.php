@extends('admin/layout')
@section('container')
{{ session('success') }}
<div class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<h2>Edit Coupon</h2>

			<form action="{{ route('coupons.update', $coupon->id)}}" method="post">
				@csrf
				@method('PUT')
				<div class="mb-3">
					<label>Coupon Code</label>
					<input type="text" name="coupon_code" class="form-control" value="{{ $coupon->coupon_code}}" required>
				@error('coupon_code')
					<div class="text-danger">{{ $message }}</div>
				@enderror
				</div>

				<div class="mb-3">
					<label>Expiration Date</label>
					<input type="datetime-local" name="expire_date" class="form-control" value="{{ $coupon->expire_date}}" required>
				@error('expiration_date')
					<div class="text-danger">{{ $message }}</div>
				@enderror
				</div>

				<div class="mb-3">
					<label>Value</label>
					<input type="number" name="values" class="form-control" value="{{ $coupon->value}}" required>
				@error('values')
					<div class="text-danger">{{ $message }}</div>
				@enderror
				</div>

				<div class="mb-3">
					<label>Status</label>
					<select name="status" class="form-control">
						<option value="active" {{ $coupon->status ? 'selected' : ''}}>Active</option>
						<option value="inactive" {{ !$coupon->status ? 'selected' : ''}}>Expired</option>
					</select>				
				</div>
				<button type="submit" class="btn btn-primary">Update</button>
			</form>
		</div>
	</div>
</div>
@endsection