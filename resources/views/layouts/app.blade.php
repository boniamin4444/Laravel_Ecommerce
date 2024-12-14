<!DOCTYPE html>
<html>
<head>
    <title>@yield('title','cogent')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .navbar {
            background-color: #4CAF50; /* Green background */
        }
        .navbar-light .navbar-nav .nav-link {
            color: white; /* White text color */
        }
        .navbar-light .navbar-nav .nav-link:hover {
            color: #FFD700; /* Golden color on hover */
        }
        .navbar-toggler-icon {
            background-color: white; /* White burger icon */
        }
        .navbar-brand {
            color: white; /* White brand color */
        }
        .navbar-brand:hover {
            color: #FFD700; /* Golden color on hover */
        }
        .dropdown-menu {
            background-color: #4CAF50; /* Green dropdown background */
        }
        .dropdown-item {
            color: white; /* White dropdown text */
        }
        .dropdown-item:hover {
            background-color: #FFD700; /* Golden background on hover */
            color: black; /* Black text on hover */
        }
    </style>
    
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{url('/')}}">cogent</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Left side menu -->
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/about') }}">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/faq') }}">FAQ</a>
        </li>
      </ul>

      <!-- Right side menu for login/register -->
      <ul class="navbar-nav ms-auto">
        @guest
          <li class="nav-item">
            <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
          </li>
        @else
          <!-- Notifications -->
          <li class="nav-item dropdown">
            <a href="{{ route('notifications.markAsRead')}}" class="nav-link" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">New&nbsp;<span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span></a>
            <div class="dropdown-menu dropdown-menu-end">
              @foreach(auth()->user()->unreadNotifications as $notification)
                <a href="{{ route('product.details', $notification->data['product_id'])}}" class="dropdown-item">
                  {{ $notification->data['product_name']}} was added! <span class="text-muted small">{{ $notification->created_at->diffForHumans() }}</span>
                </a>
              @endforeach
              @if(auth()->user()->unreadNotifications->isEmpty())
                <a href="#" class="dropdown-item text-muted">No New Notifications</a>
              @endif
            </div>
          </li>

          <!-- Cart -->
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

          <!-- Logout -->
          <li class="nav-item">
            <a href="{{ route('logout')}}" class="nav-link">LogOut</a>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4" style="height: 100vh">
  @yield('content')
</div>

<!-- Footer -->
<footer class="bg-light py-4 mt-5">
  <div class="container text-center">
    <p class="mb-0">&copy; {{ date('Y') }} cogent. All rights reserved.</p>
  </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  window.addEventListener('DOMContentLoaded', (event)=> {
    const alertElement = document.querySelectorAll('.alert');
    if(alertElement.length > 0) {
      alertElement.forEach(function(alert) {
        setTimeout(()=> {
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
    });
  });
</script>

</body>
</html>
