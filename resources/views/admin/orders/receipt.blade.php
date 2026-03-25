<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .receipt {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #ccc;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .restaurant-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .restaurant-info {
            font-size: 10px;
            color: #666;
        }
        .order-info {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }
        .order-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .order-info-label {
            font-weight: bold;
        }
        .items-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px solid #ccc;
            padding: 5px 0;
        }
        .items-table td {
            padding: 5px 0;
        }
        .items-table .qty {
            width: 30px;
            text-align: center;
        }
        .items-table .price {
            text-align: right;
        }
        .totals {
            border-top: 2px dashed #ccc;
            padding-top: 10px;
            margin-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 1px solid #ccc;
            padding-top: 8px;
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px dashed #ccc;
            font-size: 10px;
            color: #666;
        }
        .thank-you {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .payment-info {
            background: #f5f5f5;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="restaurant-name">{{ $settings['resturant_name'] ?? 'Restaurant' }}</div>
            @if(isset($settings['address']))
                <div class="restaurant-info">{{ $settings['address'] }}</div>
            @endif
            @if(isset($settings['phone']))
                <div class="restaurant-info">Tel: {{ $settings['phone'] }}</div>
            @endif
            @if(isset($settings['email']))
                <div class="restaurant-info">{{ $settings['email'] }}</div>
            @endif
        </div>

        <!-- Order Info -->
        <div class="order-info">
            <div class="order-info-row">
                <span class="order-info-label">Order No:</span>
                <span>{{ $order->order_no }}</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Date:</span>
                <span>{{ $order->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Type:</span>
                <span>{{ ucfirst($order->order_type) }}</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Status:</span>
                <span class="status-badge status-completed">{{ ucfirst($order->status) }}</span>
            </div>
          
            @if($order->customer)
                <div class="order-info-row">
                    <span class="order-info-label">Customer:</span>
                    <span>{{ $order->customer->name }}</span>
                </div>
            @endif
            @if($order->user)
                <div class="order-info-row">
                    <span class="order-info-label">Served by:</span>
                    <span>{{ $order->user->name }}</span>
                </div>
            @endif
        </div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="qty">Qty</th>
                    <th class="price">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->menuItem->name ?? 'Unknown Item' }}</td>
                        <td class="qty">{{ $item->quantity }}</td>
                        <td class="price">${{ number_format($item->subtotal ?? ($item->price * $item->quantity), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>${{ number_format($order->subtotal ?? 0, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Tax (10%):</span>
                <span>${{ number_format($order->tax ?? 0, 2) }}</span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>${{ number_format($order->total_amount ?? 0, 2) }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        @if($order->payments->isNotEmpty())
            <div class="payment-info">
                <div class="order-info-row">
                    <span class="order-info-label">Payment Method:</span>
                    <span>{{ strtoupper($order->payments->first()->payment_method) }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Paid Amount:</span>
                    <span>${{ number_format($order->payments->first()->paid_amount, 2) }}</span>
                </div>
                @if($order->payments->first()->change_amount > 0)
                    <div class="order-info-row">
                        <span class="order-info-label">Change:</span>
                        <span>${{ number_format($order->payments->first()->change_amount, 2) }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">Thank You!</div>
            <div>Please come again</div>
            <div style="margin-top: 10px; font-size: 9px;">
                Printed: {{ now()->format('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>
