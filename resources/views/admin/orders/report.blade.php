@extends('admin/layout')
@section('container')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="container">
   <h2>Orders Report</h2>
   <form id="filter-form">
    @csrf
    <div class="row mb-4">
        <label for="start_date">From Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control">
    </div>

    <div class="row mb-4">
         <label for="end_date">To Date</label>
        <input type="date" name="end_date" id="end_date" class="form-control">
    </div>

    <div class="row mb-4">
        <input type="submit" class="btn btn-primary" value="Show Report">
    </div>
   </form>

   <table class="table" id="orders-table">
        <thead>
            <tr>
                <th>Order Id</th>
                <th>Product Name</th>
                <th>Customer Name</th>
                <th>Order Amount</th>
                <th>Total Discount Price</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be appended here via AJAX -->
        </tbody>
   </table>
   <button id="print-report" class="btn btn-success">Print</button>
</div>

<style>
    @media print {
        #filter-form, #print-report {
            display: none;
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {
        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            let startDate = $('#start_date').val(); // Correct ID
            let endDate = $('#end_date').val(); // Correct ID

            $.ajax({
                url: "{{ route('orders.report.fetch') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    $('#orders-table tbody').empty();

                    if (response.length > 0) {
                        $.each(response, function(index, order) {
                            $('#orders-table tbody').append(
                                `
                                <tr>
                                    <td>${order.order_id}</td>
                                    <td>${order.product.product_name}</td>
                                    <td>${order.user.name}</td>
                                    <td>${order.order_amount}</td>
                                    <td>${order.total_discount_price}</td>
                                    <td>${order.quantity}</td>
                                </tr>
                                `
                            );
                        });
                    } else {
                        $('#orders-table tbody').append('<tr><td colspan="6">No orders found</td></tr>');
                    }
                },
                error: function() {
                    alert('There was an error fetching orders');
                }
            });
        });

        $('#print-report').on('click', function() {
            window.print();
        });
    });
</script>
@endsection
