@extends('admin/layout')
@section('container')

{{ session('success') }}

<div class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<h2>Create Coupon</h2>

			<form method="post" action="{{ route('coupons.store')}}">
				@csrf
				<div class="mb-3">
					<label>Coupon Code</label>
					<input type="text" name="coupon_code" class="form-control" value="{{ old('coupon_code')}}" required>

					@error('coupon_code')
					 	<div class="text-danger">
					 		{{ $message }}
					 	</div>
					 @enderror
				</div>

				<div class="mb-3">
					<label>Expiration Date</label>
					<input type="datetime-local" name="expire_date" class="form-control" value="{{ old('expire_date')}}" required>

					@error('expire_date')
					 	<div class="text-danger">
					 		{{ $message }}
					 	</div>
					 @enderror
				</div>

				<div class="mb-3">
					<label>Values</label>
					<input type="number" name="values" class="form-control" value="{{ old('value')}}" required>

					@error('values')
					 	<div class="text-danger">
					 		{{ $message }}
					 	</div>
					 @enderror
				</div>
				<button type="submit" class="btn btn-success">Create</button>
			</form>
		</div>
	</div>
</div>
@endsection