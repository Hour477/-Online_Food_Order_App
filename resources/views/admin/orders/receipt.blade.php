<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_no }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }

        .receipt {
            max-width: 320px;
            margin: 40px auto;
            background: white;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 
                        0 8px 10px -6px rgb(0 0 0 / 0.1);
            border: 1px solid #e5e7eb;
            border-radius: 16px;
        }

        /* Paper texture effect */
        .receipt::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                to bottom,
                rgba(0,0,0,0.02) 0%,
                rgba(0,0,0,0) 100%
            );
            pointer-events: none;
            z-index: 1;
        }

        @media print {
            /* Reset all animated elements to be fully visible when printing */
            * {
                animation: none !important;
                transition: none !important;
            }
            .receipt {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen py-12">

    <div class="receipt mx-auto relative">

        <!-- Header -->
        <div class="px-6 pt-8 pb-6 border-b border-dashed border-gray-300 text-center bg-white">
            <div class="text-2xl font-bold tracking-tight text-gray-900">
                {{ $settings['resturant_name'] ?? 'Restaurant Name' }}
            </div>
            @if(isset($settings['address']))
                <p class="text-xs text-gray-600 mt-1">{{ $settings['address'] }}</p>
            @endif
            @if(isset($settings['phone']))
                <p class="text-xs text-gray-600">Tel: {{ $settings['phone'] }}</p>
            @endif
        </div>

        <!-- Order Info -->
        <div class="px-6 py-5 text-sm border-b border-dashed border-gray-300 space-y-2.5 bg-white">
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700">Order No</span>
                <span class="font-semibold text-gray-900 text-lg">#{{ $order->order_no }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Date</span>
                <span class="text-gray-600">{{ $order->created_at->format('d M Y • H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Order Type</span>
                <span class="capitalize font-medium">{{ $order->order_type }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700">Status</span>
                <span class="px-4 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            @if($order->customer)
            <div class="flex justify-between pt-2">
                <span class="font-medium text-gray-700">Customer</span>
                <span class="text-gray-700">{{ $order->customer->name }}</span>
            </div>
            @endif
        </div>

        <!-- Items -->
        <div class="px-6 py-6 bg-white">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-300">
                        <th class="text-left font-semibold pb-4">Item</th>
                        <th class="text-center font-semibold pb-4 w-12">Qty</th>
                        <th class="text-right font-semibold pb-4">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->orderItems as $index => $item)
                    <tr class="py-4">
                        <td class="py-3 pr-4 text-gray-800 font-medium">
                            {{ $item->menuItem->name ?? 'Unknown Item' }}
                        </td>
                        <td class="py-3 text-center font-semibold text-gray-600">
                            x{{ $item->quantity }}
                        </td>
                        <td class="py-3 text-right font-semibold text-gray-900">
                            ${{ number_format($item->subtotal ?? ($item->price * $item->quantity), 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="px-6 pb-7 pt-2 bg-white border-t border-dashed border-gray-300">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">${{ number_format($order->subtotal ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tax (10%)</span>
                    <span class="font-medium">${{ number_format($order->tax ?? 0, 2) }}</span>
                </div>
            </div>

            <!-- Grand Total with animation -->
            <div class="grand-total mt-6 pt-5 border-t-2 border-gray-800 flex justify-between items-baseline text-xl">
                <span class="font-bold text-gray-900 tracking-tight">TOTAL</span>
                <span class="font-bold text-gray-900">
                    ${{ number_format($order->total_amount ?? 0, 2) }}
                </span>
            </div>
        </div>

        <!-- Payment Info -->
        @if($order->payments->isNotEmpty())
        <div class="mx-6 mb-6 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl p-5 text-sm shadow-inner">
            <div class="flex justify-between mb-3">
                <span class="font-medium text-gray-700">Payment Method</span>
                <span class="font-semibold uppercase tracking-widest text-emerald-600">
                    {{ $order->payments->first()->payment_method }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Paid</span>
                <span class="font-semibold">${{ number_format($order->payments->first()->paid_amount, 2) }}</span>
            </div>
            @if($order->payments->first()->change_amount > 0)
            <div class="flex justify-between mt-2 text-emerald-600 font-medium">
                <span>Change</span>
                <span>${{ number_format($order->payments->first()->change_amount, 2) }}</span>
            </div>
            @endif
        </div>
        @endif

        <!-- Footer -->
        <div class="text-center py-9 border-t border-dashed border-gray-300 bg-gray-50">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow mb-4">
                <span class="text-3xl">🙏</span>
            </div>
            <div class="text-xl font-semibold text-gray-800">Thank You!</div>
            <p class="text-gray-500 text-sm mt-1">Please visit us again soon</p>
            
            <div class="mt-8 text-[10px] text-gray-400">
                Printed {{ now()->format('d M Y, H:i') }}
            </div>
        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print text-center mt-10">
        <button onclick="window.print()" 
                class="px-10 py-4 bg-black hover:bg-gray-900 text-white font-semibold rounded-2xl transition-all duration-300 flex items-center gap-3 mx-auto shadow-lg hover:shadow-xl">
            <span>🖨️</span>
            <span>Print Receipt</span>
        </button>
    </div>

</body>
</html>