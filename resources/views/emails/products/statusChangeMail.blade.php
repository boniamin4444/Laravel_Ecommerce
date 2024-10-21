@component('mail::message')
# Order Status Updated

Your order with ID {{ $order->order_id }}
has been updated to {{ $order->status }}
Your Product Name is : {{ $order->product->product_name }}
your Order price is : {{ $order->total_discounted_price }}

Thank you for your business!

@endcomponent
