<!DOCTYPE html>
<html>
<head>
	<title>@yield('title','cogent')</title>
	 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
  <a class="navbar-brand" href="{{url('/')}}">cogent</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto">
    	@guest
    		<li class="nav-item">
    			<a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
    		</li>
    		<li class="nav-item">
    			<a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
    		</li>
        @else
            
            <li class="nav-item dropdown">
                <a href="{{ route('notifications.markAsRead')}}" class="nav-link" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">New&nbsp;<span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span></a>

                <div class="dropdown-menu dropdown-menu-end">
                    @foreach(auth()->user()->unreadNotifications as $notification)

                    <a href="{{ route('product.details', $notification->data['product_id'])}}" class="dropdown-item">
                        {{ $notification->data['product_name']}} was added! <span class="text-muted small">{{
                            $notification->created_at->diffForHumans()
                        }}</span>
                    </a>
                    

                    @endforeach

                    @if(auth()->user()->unreadNotifications->isEmpty())
                        <a href="#" class="dropdown-item text-muted">No New Notifications</a>
                    @endif                   
                </div>
            </li>


            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="cartDropdown" role="button" data-bs-toggle="dropdown">
                    Cart <span class="badge bg-success" id="cart-total">({{ session()->has('cart') ? count(session('cart')) : 0}})</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a href="{{route('cart.view')}}" class="dropdown-item">View Cart</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout')}}" class="nav-link">LogOut</a>
            </li>
    	@endguest
    </ul>
  </div>
 </div>
</nav>

<div class="container mt-4">

	@yield('content')

</div>

<!--Login Modal -->

<div class="modal fade" id="loginModal" tabindex="-1" @if(session('login_errors')) style="display:block;" @endif>

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>         
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('login.post')}}" method="post">
        	@csrf
        	<div class="form-group mb-3">
        		<label>Email Address:</label>
        		<input type="text" name="email" class="form-control" value="{{ old('email')}}">
        	</div>
        	<div class="form-group mb-3">
        		<label>Password:</label>
        		<input type="password" name="password" class="form-control">
        	</div>

        	<!-- Display error -->

        	@if(session('login_errors'))
        		<div class="alert alert-danger alert-dismissible fade show" role="alert">
        			{{ session('login_errors')}}
        			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        		</div>
        	@endif

        	<button type="submit" class="btn btn-primary">Login</button>
	       </form>
      </div>      
    </div>
  </div>
</div>

<!--Register Modal -->

<div class="modal fade" id="registerModal" tabindex="-1">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Register</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('register.post')}}" method="post">
        	@csrf
        	<div class="form-group mb-3">
        		<label>User Name:</label>
        		<input type="text" name="name" class="form-control @error('name','register') is-invalid @enderror" name="name" value="{{ old('name')}}">

        		@error('name','register')
        			<div class="invalid-feedback">
        				{{ $message }}
        			</div>
        		@enderror
        	</div>

        	<div class="form-group mb-3">
        		<label>Email:</label>
        		<input type="text" name="email" class="form-control @error('email','register') is-invalid @enderror" name="email" value="{{ old('email')}}">

        		@error('email','register')
        			<div class="invalid-feedback">
        				{{ $message }}
        			</div>
        		@enderror
        	</div>
        	<div class="form-group mb-3">
        		<label>Password:</label>
        		<input type="password" class="form-control @error('password','register') is-invalid @enderror" name="password">

        		@error('password','register')
        			<div class="invalid-feedback">
        				{{ $message }}
        			</div>
        		@enderror
        	</div>

        	<div class="form-group mb-3">
        		<label>Confirm Password:</label>
        		<input type="password" name="password_confirmation" class="form-control @error('password_confirmation','register') is-invalid @enderror">

        		@error('password_confirmation','register')
        			<div class="invalid-feedback">
        				{{ $message }}
        			</div>
        		@enderror
        	</div>

        	<button type="submit" class="btn btn-primary">Register</button>        	
	       </form>
      </div>      
    </div>
  </div>
</div>

<footer class="bg-light py-4 mt-5">
	<div class="container text-center">
		<p class="mb-0">
			&copy; {{date('Y')}} cogent. All rights reserved.
		</p>
	</div>
</footer>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	window.addEventListener('DOMContentLoaded', (event)=>{

		const alertElement = document.querySelectorAll('.alert');
		
		if(alertElement.length > 0)
		{
			alertElement.forEach(function(alert)
			{
			setTimeout(()=>{
			bootstrap.Alert.getOrCreateInstance(alert).close();
				}, 5000);
			});

		}

		@if(session('login_errors'))
		
			var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
			loginModal.show();
		@endif

		@if($errors->register->any())		
			var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
			registerModal.show();
		@endif
		
	});

</script>
<script>
	document.addEventListener('DOMContentLoaded', function(){
		@if(session('showLoginModal'))

			var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

			loginModal.show();
		@endif
	});
</script>

<!--Add to cart logic-->
<script>
    $(document).ready(function(){

        var isAuthenticated = {{ Auth::check() ? 'true' : 'false'}};

        function addToCart(productId)
        {
            if(!isAuthenticated)
            {
                $('#loginModal').modal('show');
                return;
            }

            $.ajax({

                url: "{{ route('cart.add') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId
                },

                success: function(response)
                {
                    alert(response.status);
                    window.location.reload();
                },
                error: function(xhr)
                {
                    console.log(xhr.responseText);
                }
            });
        }

        $(document).on('click','.add-to-cart', function(e){

            e.preventDefault();
            var productId = $(this).data('id');
            addToCart(productId);
        });

        $(document).on('click','.remove-from-cart',function(e){

            e.preventDefault();
            var productId = $(this).data('id');

            $.ajax({

                url: "{{ route('cart.remove')}}",
                method: "DELETE",
                data: 
                {
                    _token: "{{ csrf_token() }}",
                    product_id:productId 
                },

                success: function(response)
                {
                    alert(response.status);
                    window.location.reload();
                },
                error: function(xhr)
                {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
</body>
</html>