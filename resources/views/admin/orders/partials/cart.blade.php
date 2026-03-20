<div class="bg-white shadow-sm rounded-xl border border-gray-200 p-5 sticky top-6">
    {{-- Cart Header --}}
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Cart Summary
            <span id="cart-count-badge" class="text-xs font-medium text-gray-400">
                (0 items)   
            </span>
        </h3>

        <button id="clear-cart-btn"
                class="text-xs text-red-500 hover:text-red-700 transition font-medium"
                {{ empty($cart ?? []) ? 'hidden' : '' }}>
            Clear All
        </button>
    </div>

    {{-- Order Type --}}
    <div class="mb-4">
        <label for="order_type" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
            Order Type <span class="text-red-500">*</span>
        </label>
        <select id="order_type" name="order_type" 
                class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900
                       px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
            @foreach ($order_types as $type)
                <option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
            @endforeach
        </select>
    </div>

    {{-- Cart Items List --}}
    <div id="cart-items-container" class="space-y-2 mb-5 max-h-72 overflow-y-auto">
        @if(empty($cart ?? []))
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-sm font-medium">Cart is empty</p>
                <p class="text-xs mt-1 text-gray-400">Add items from the menu</p>
            </div>
        @else
            @foreach($cart as $item)
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-200">
                    {{-- Item Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-900 text-sm truncate">
                            {{ $item['name'] ?? 'Unnamed Item' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            ${{ number_format($item['price'] ?? 0, 2) }} × {{ $item['quantity'] ?? 1 }}
                        </div>
                    </div>

                    {{-- Quantity & Remove --}}
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-2 py-1">
                            <button type="button" 
                                    class="text-gray-600 hover:text-amber-600 text-sm decrease-qty"
                                    data-id="{{ $item['id'] ?? '' }}">
                                −
                            </button>
                            <span class="w-5 text-center font-medium text-sm qty-display">
                                {{ $item['quantity'] ?? 1 }}
                            </span>
                            <button type="button" 
                                    class="text-gray-600 hover:text-amber-600 text-sm increase-qty"
                                    data-id="{{ $item['id'] ?? '' }}">
                                +
                            </button>
                        </div>

                        <button type="button" 
                                class="text-red-500 hover:text-red-700 text-xs remove-item"
                                data-id="{{ $item['id'] ?? '' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Order Summary --}}
    <div class="border-t border-gray-200 pt-4 space-y-2">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Subtotal</span>
            <span id="cart-subtotal" class="font-semibold text-gray-900">
                ${{ number_format($subtotal ?? 0, 2) }}
            </span>
        </div>

        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Tax (10%)</span>
            <span id="cart-tax" class="font-semibold text-gray-900">
                ${{ number_format(($subtotal ?? 0) * 0.10, 2) }}
            </span>
        </div>

        <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-200">
            <span class="text-gray-900">Total</span>
            <span id="cart-grand-total" class="text-amber-600">
                ${{ number_format(($subtotal ?? 0) * 1.10, 2) }}
            </span>
        </div>
    </div>

    {{-- Customer Select --}}
    <div class="mt-4">
        <label for="customer_id" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
            Select Customer
        </label>
        <select id="customer_id" name="customer_id" 
                class="select2-customer block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900
                       px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors"
                data-placeholder="Select customer (optional)">
            <option value=""></option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone ?? 'No phone' }})</option>
            @endforeach
        </select>
    </div>

    {{-- Table Select --}}
    <div class="mt-4" id="table-field-container">
        <label for="table_id" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
            Select Table <span class="text-red-500">*</span>
        </label>
        <select id="table_id" name="table_id" 
                class="select2-table block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900
                       px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors"
                data-placeholder="Select a table">
            <option value=""></option>
            @foreach($tables as $table)
                <option value="{{ $table->id }}">Table {{ $table->table_number }} ({{ $table->capacity }} seats)</option>
            @endforeach
        </select>
        @error('table_id')
            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Payment Section --}}
    <div class="border-t border-gray-200 pt-4 mt-4">
        <h4 class="text-sm font-bold text-gray-900 mb-3">Payment</h4>
        <div class="space-y-3">
            <div>
                <label for="payment_method" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                    Payment Method
                </label>
                <select id="payment_method" name="payment_method" 
                        class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900
                               px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    <option value="">-- Select --</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="aba">ABA</option>
                    <option value="wing">Wing</option>
                    <option value="bakong">Bakong</option>
                </select>
            </div>
            <div>
                <label for="paid_amount" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                    Paid Amount
                </label>
                <input type="number" id="paid_amount" name="paid_amount" step="0.01" 
                       class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900
                              px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors" 
                       placeholder="Enter amount paid">
            </div>
            <div class="flex justify-between text-sm bg-gray-50 px-3 py-2 rounded-lg">
                <span class="text-gray-500">Change</span>
                <span id="change_amount" class="font-semibold text-gray-900">$0.00</span>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="mt-5 space-y-2">
        <button id="place-order-btn"
                class="w-full py-3 px-4 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Place Order
        </button>
        
        <button id="save-cart-btn"
                class="w-full py-3 px-4 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Save Cart
        </button>
    </div>
</div>
