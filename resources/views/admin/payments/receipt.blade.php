@extends('layouts.app')

@section('content')
<div class="card">
<div class="card-body">

<h4 class="text-center">Restaurant Receipt</h4>
<hr>

Order: {{ $order->order_no }} <br>
Table: {{ $order->table->table_number }}

<hr>
@foreach($order->items as $item)
{{ $item->menuItem->name }} x {{ $item->quantity }}
<span class="float-end">${{ $item->subtotal }}</span><br>
@endforeach

<hr>
<strong>Total: ${{ $payment->total_amount }}</strong>

</div>
</div>
@endsection
