@extends('layouts.app')
@section('content')
<div class="container mt-4">
	<div class="card">
		<div class="card-header">
			<h3>{{ $product->product_name }}</h3>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" alt="{{ $product->product_name }}">
				</div>
				<div class="col-md-6">
					<h5>Details:</h5>
					<p>{{ $product->details }}</p>

					<h5>Price:</h5>
					<p>{{ $product->price }}</p>
										 
					@if($product->inventory && $product->inventory->calculated_vat > 0)
                        <p class="card-text">
                            VAT: ${{ $product->inventory->calculated_vat}}
                        </p>
                    @else
                        <span class="badge badge-warning text-dark">VAT not applicable</span>
                    @endif

					<h5>Status</h5>
					<p>{{ $product->status == 1 ? 'available' : 'Not Available'}}</p>

					<h5>Stock</h5>
					<p>{{ $product->stock }} units available</p>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<a href="{{ url('/')}}" class="btn btn-primary">Back to Products</a>
		</div>
	</div>
</div>
@endsection