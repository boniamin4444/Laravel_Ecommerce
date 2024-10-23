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

<form action="{{ route('inventory.store') }}" method="post">
	@csrf
	<div class="form-group">
		<label>Category</label>
		<select name="category_id" id="category" class="form-control">
			<option value="">Select Category</option>
			@foreach($categories as $category)
				<option value="{{ $category->id }}">{{ $category->category_name}}</option>
			@endforeach
		</select>
	</div>

	<div class="form-group">
		<label>Products</label>
		<select name="product_id" id="product" class="form-control">
			<option value="">Select Product</option>
		</select>
	</div>

	<div class="form-group">
		<label>Stock In</label>
		<input type="number" name="stock_in" class="form-control">
	</div>

	<div class="form-group">
		<label>VAT (%)</label>
		<input type="number" name="vat" id="vat" class="form-control">
	</div>

	<div class="form-group">
		<label>Calculated VAT (%)</label>
		<input type="number" name="calculated_vat" id="calculated_vat" class="form-control" readonly>
	</div>

	<button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">

	$(document).ready(function(){

		$('#category').on('change', function(){

			var categoryId = $(this).val();
			if(categoryId)
			{
				$.ajax({

					url: '/get-products/' +categoryId,
					type: "GET",
					dataType: "json",
					success: function(data)
					{
						$('#product').empty();
						$('#product').append('<option value="">Select Product</option>');
						$.each(data, function(key, value)
						{	
							$('#product').append('<option value="'+ value.id+'" data-price="'+value.price+'">'+value.product_name+'</option>');
						});
					}

				});
			}

			else
			{
				$('#product').empty();
			}
		});

		$('#product').on('change', function(){

			var selectedProduct = $(this).find(':selected');
			var price = selectedProduct.data('price');
			var vat = parseFloat($('#vat').val());

			if(vat && price)
			{
				var calculatedVat = (price * vat) / 100;
				$('#calculated_vat').val(calculatedVat.toFixed(2));
			}  
		});

		$('#vat').on('input', function(){

			var vat = parseFloat($(this).val());
			var selectedProduct = $('#product').find(':selected');
			var price = selectedProduct.data('price');

			if(vat && price)
			{
				var calculatedVat = (price * vat) / 100;
				$('#calculated_vat').val(calculatedVat.toFixed(2));
			}

			else
			{
				$('#calculatedVat').val('');
			}
		});
	});
</script>