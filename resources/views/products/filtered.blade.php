@if($products->count() > 0)
	@foreach($products as $product)

		<div class="col-md-4">
			<div class="card mb-4">
				<img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" alt="{{ $product->product_name}}">
				<div class="card-body">
					<h5 class="card-title">{{ $product->product_name }}</h5>
					<p class="card-text">{{ $product->details }}</p>
					<p class="card-text">{{ $product->price }}</p><p class="card-text">
						<span class="{{ $product->status == 'active' ? 'badge-success' : 'badge-danger' }}">
							{{ ucfirst($product->status) }}
						</span>
					</p>
				</div>
				<div class="card-footer">
					<button class="btn btn-primary add-to-cart" data-id="{{$product->id}}">Order Now</button>
				</div>
			</div>
		</div>
	@endforeach

	@else
		<p>No products found for the selected filter</p>
	@endif