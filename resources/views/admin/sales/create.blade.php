@extends('admin/layout')
@section('container')

{{ session('success') }}


    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user">Select User</label>
            <select name="user_id" id="user" class="form-control" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="category">Select Category</label>
            <select name="category_id" id="category" class="form-control" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="product">Select Product</label>
            <select name="product_id" id="product" class="form-control" required>
                <option value="">Select Product</option>
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" required>
        </div>

        <div class="form-group">
            <label for="calculated_vat">Calculated VAT</label>
            <input type="text" name="calculated_vat" id="calculated_vat" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Record Sale</button>
    </form>
</div>

<script>
document.getElementById('category').addEventListener('change', function() {
    var categoryId = this.value;
    fetch(`/sales/fetch-products?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            var productSelect = document.getElementById('product');
            productSelect.innerHTML = '<option value="">Select Product</option>';
            data.forEach(product => {
                productSelect.innerHTML += `<option value="${product.id}">${product.name}</option>`;
            });
        });
});

document.getElementById('product').addEventListener('change', function() {
    var productId = this.value;
    fetch(`/inventories/${productId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('calculated_vat').value = data.calculated_vat || 0;
        });
});
</script>
@endsection
