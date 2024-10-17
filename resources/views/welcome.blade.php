@extends('layouts.app')
<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

     <style>
        .product-card
        {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card img
        {
            height: 200px;
            object-fit: cover;
        }

        .card-footer
        {
            background: none;
            border-top: none;
            text-align: left;
        }

    </style>
</head>
<body>

    @section('content')

    <div class="container mt-5">
        <div class="row">
            @if(Session::has('message'))
                <div class="alert alert-warning">
                    {{session('message')}}
                </div>
            @endif

        <!--Category Section -->        
        <div class="row">       
        <div class="col-md-3">
            <h5>Filter by Categories</h5>
            <form id="categoryFilterForm">
                @foreach($categories as $category)
                    <div class="form-check">
                        <input class="form-check-input category-checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}">
                        <label class="form-check-label" for="category-{{ $category->id }}">
                            {{ $category->category_name }}
                        </label>
                    </div>
                @endforeach
            </form>

            <!--Price Section -->
             <p></p>
            <h5>Filter by Price Range</h5>
            <div class="price-filter-section">
                <div class="form-check">
                    <input type="checkbox" name="price1" id="price1" class="form-check-input price-filter" value="100-500">
                    <label class="form-check-label">$100-$500</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="price2" id="price2" class="form-check-input price-filter" value="501-1000">
                    <label class="form-check-label">$501-$1000</label>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="col-md-9">
            <div id="productsList" class="row">
                @foreach($products as $product)
                <div class="col-md-4 mb-4">
                <div class="card">
                @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" width="250">
                @else
                    No Image
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product->product_name }}</h5>
                    <p class="card-text">{{ \Illuminate\Support\Str::limit($product->details, 100) }}</p>
                    <p><strong>Price:</strong> ${{ $product->price }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge {{ $product->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </p>
                    <div class="card-footer">
                            <button class="btn btn-primary add-to-cart" data-id="{{$product->id}}">Order Now</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>
<div class="d-flex justify-content-center pagination-links">
    {{ $products->links() }}
</div>
</div>
</div>

<!-- Login Modal -->

<div class="modal fade" tabindex="-1" id="loginModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
       <form id="loginForm" method="POST" action="{{ route('login')}}">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" id="email" required>            
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <button class="btn btn-primary" type="submit">Login</button>
           
       </form>
      </div>
    </div>
  </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
    
    let selectedProduct = null;

    $(document).on('click','.order-now-btn',function(){

        selectedProduct = $(this).data('product');
        $('#loginModal').modal('show');
    });

    //Handle login form submission

    $('#loginModal').on('submit', function(event){

        event.preventDefault();

        //Perform AJAX request for login

        $.ajax({

            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),

            success: function(response)
            {
                if(response.success)
                {
                    $('#loginModal').hide();
                    if(selectedProduct)
                    {
                        console.log('Ordered Products are:', selectedProduct);
                    }

                    selectedProduct = null;
                }

                else
                {
                    console.error('Login Failed');
                }
            },

            error: function(xhr)
            {
                console.error('Login Failed');
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){

        $('.category-checkbox,.price-filter').on('change',function(){

            filterProducts(1);
        });

        $(document).on('click','.pagination a', function(e){

            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            filterProducts(page);
        })

        function filterProducts(page)
        {
            var selectedcategories = [];
            var selectedPrice = [];

            $('.category-checkbox:checked').each(function()
            {

                selectedcategories.push($(this).val());
            });

            $('.price-filter:checked').each(function(){

                selectedPrice.push($(this).val());
            });

            //AJAX Request

            $.ajax({
                url: "{{ route('products.filter')}}?page=" + page,
                method: 'GET',
                data:
                {
                    categories:selectedcategories,
                    prices: selectedPrice
                },

                success: function(response)
                {
                    $('#productsList').html(response.html);
                    $('.pagination-links').html(response.pagination);
                },

                error: function(xhr, status, error)
                {
                    console.log(error)
                }
            });
        }
    });
</script>

<script>
    //To capture the data-id value and send it to the the addToCart function via AJAX

    $(document).on('click','.add-to-cart', function(e){

        e.preventDefault();
        var productId = $(this).data('id');
        var button = $(this);

        button.prop('disabled', true);

        $.ajax({

            url: '/cart/add',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },

            success: function(response)
            {
                if(response.success)
                {
                    alert('Product added to the cart');
                    $('#cart-total').text('(' +response.cartCount+')');
                }

                else
                {
                    alert('response.error');
                }

                button.prop('disabled', false);

            },

            error: function(xhr, status, error)
            {
                alert('An error occured. Please tray again');
                console.log('Ajax Error', error);
                button.prop('disabled', false);
            }
        });
    }); 
</script>
</body>
</html>

