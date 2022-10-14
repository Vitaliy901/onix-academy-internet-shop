@component('mail::message')
# Hello {{ $name }}!

Your order has been successfully created and is being processed.

@component('mail::table')
## Order: {{ $order->id }}
| Item | Quantity | Price | Amout |
| :--- |:---:| :---:| :---:|
@foreach ($order->products as $product)
| {{ $product->name }} | {{ $product->orderItem->quantity }} | {{ $product->orderItem->price }} | {{ $product->orderItem->total_price }} |
@endforeach
| **Total** | | | **{{ $order->total_cost }}** |
@endcomponent
{{ $order->created_at->format('H:i d.m.Y') }}

@component('mail::button', ['url' => $url, 'color' => 'green'])
Your order
@endcomponent

Thank you for choosing our, {{ config('app.name') }}!
@endcomponent