@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Kitchen Orders
        </h1>

        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600 dark:text-gray-400">
                Last updated: <span id="last-updated">{{ now()->format('H:i:s') }}</span>
            </span>
            <button type="button" 
                    onclick="location.reload()" 
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                Refresh
            </button>
        </div>
    </div>

    <!-- Status Tabs / Filters -->
    <div class="flex flex-wrap gap-3 mb-6">
        <button class="status-filter px-5 py-2.5 rounded-lg font-medium transition-colors
                bg-gray-100 text-gray-800 hover:bg-gray-200
                dark:bg-gray-900/40 dark:text-gray-300 dark:hover:bg-gray-900/60"
                data-status="all">
                All 
        </button>

        <button class="status-filter px-5 py-2.5 rounded-lg font-medium transition-colors
                       bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                       dark:bg-yellow-900/40 dark:text-yellow-300 dark:hover:bg-yellow-900/60"
                data-status="pending">
            Pending
        </button>
        <button class="status-filter px-5 py-2.5 rounded-lg font-medium transition-colors
                       bg-blue-100 text-blue-800 hover:bg-blue-200
                       dark:bg-blue-900/40 dark:text-blue-300 dark:hover:bg-blue-900/60"
                data-status="preparing">
            Preparing
        </button>
        <button class="status-filter px-5 py-2.5 rounded-lg font-medium transition-colors
                       bg-green-100 text-green-800 hover:bg-green-200
                       dark:bg-green-900/40 dark:text-green-300 dark:hover:bg-green-900/60"
                data-status="ready">
            Ready
        </button>
        <button class="status-filter px-5 py-2.5 rounded-lg font-medium transition-colors
                       bg-red-100 text-red-800 hover:bg-red-200
                       dark:bg-red-900/40 dark:text-red-300 dark:hover:bg-red-900/60"
                data-status="cancelled">
            Cancelled
        </button>
        <button class="status-filter px-5 py-2.5 rounded-lg font-medium transition-colors
                       bg-gray-100 text-gray-800 hover:bg-gray-200
                       dark:bg-gray-900/40 dark:text-gray-300 dark:hover:bg-gray-900/60"
                data-status="completed">
            Completed
        </button>
       
    </div>

    <!-- Orders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="orders-container">

        @forelse($orders as $order)
            <div class="order-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden
                        transition-all duration-200 hover:shadow-md
                        {{ $order->status === 'preparing' ? 'border-l-4 border-l-blue-500' : 'border-l-4 border-l-yellow-500' }}"
                 data-status="{{ $order->status }}">

                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                #{{ $order->order_no }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $order->created_at?->diffForHumans() ?? '—' }}
                            </p>
                        </div>

                        <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                                     {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-300' : '' }}
                                     {{ $order->status === 'preparing' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-300' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <!-- Order Items -->
                    <div class="space-y-2 mb-4">
                        @forelse($order->items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-800 dark:text-gray-200">
                                    {{ $item->quantity }}× {{ $item->menuItem?->name ?? 'Unknown item' }}
                                </span>
                                @if($item->notes)
                                    <span class="text-xs text-gray-500 dark:text-gray-400 italic">
                                        ({{ $item->notes }})
                                    </span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                                No items in this order
                            </p>
                        @endforelse
                    </div>

                    <!-- Notes -->
                    @if($order->notes)
                        <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg text-sm text-gray-700 dark:text-gray-300 border-l-4 border-l-amber-500">
                            <strong>Note:</strong> {{ $order->notes }}
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex gap-3 mt-4">
                        @if($order->status === 'pending')
                            <form action="{{ route('orders.status', $order->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="preparing">
                                <button type="submit"
                                        class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    Start Preparing
                                </button>
                            </form>
                        @elseif($order->status === 'preparing')
                            <form action="{{ route('orders.status', $order->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="ready">
                                <button type="submit"
                                        class="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                    Mark Ready
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('orders.status', $order->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit"
                                    onclick="return confirm('Cancel this order?')"
                                    class="w-full py-2.5 px-4 bg-red-100 hover:bg-red-200 text-red-800 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 font-medium rounded-lg transition-colors">
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <div class="text-6xl mb-4 text-gray-300 dark:text-gray-600">🍽️</div>
                <h3 class="text-xl font-medium text-gray-600 dark:text-gray-300 mb-2">
                    No orders in kitchen right now
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    New orders will appear here automatically
                </p>
            </div>
        @endforelse

    </div>

</div>

@endsection

@section('scripts')
<script>
    // Optional: Auto-refresh every 30 seconds
    // setInterval(() => location.reload(), 30000);

    // Simple status filter (client-side)
    document.querySelectorAll('.status-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            const status = this.dataset.status;
            document.querySelectorAll('.order-card').forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection