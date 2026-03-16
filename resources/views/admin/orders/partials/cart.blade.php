<div class="bg-white dark:bg-gray-900 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6 sticky top-6">
    <!-- Cart Header -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-3">
            <i class="fas fa-shopping-cart text-blue-600 dark:text-blue-400"></i>
            Cart Summary
            <span id="cart-count-badge" class="text-sm font-medium text-gray-500 dark:text-gray-400">
                (0 items)   
            </span>
        </h3>

        <button id="clear-cart-btn"
                class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500 transition"
                {{ empty($cart ?? []) ? 'hidden' : '' }}>
            Clear All
        </button>
    </div>
    {{-- Order-type  --}}
    <div class="mb-6">
        <label for="order_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Order Type <span class="text-red-500">*</span>
        </label>
       
        <select id="order_type" name="order_type" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2 transition-colors">
            @foreach ($order_types as $type)
                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </div>

    <!-- Cart Items List -->
    <div id="cart-items-container" class="space-y-3 mb-6 max-h-96 overflow-y-auto">
            @if(empty($cart ?? []))
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <i class="fas fa-shopping-cart text-4xl opacity-40 mb-3"></i>
                    <p class="text-base font-medium">Cart is empty</p>
                    <p class="text-sm mt-1">Add items from the menu</p>
                </div>
            @else
                @foreach($cart as $item)
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800/60 p-3 rounded-md border border-gray-200 dark:border-gray-700">
                        <!-- Item Info -->
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                {{ $item['name'] ?? 'Unnamed Item' }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                ${{ number_format($item['price'] ?? 0, 2) }} × {{ $item['quantity'] ?? 1 }}
                            </div>
                        </div>

                        <!-- Quantity & Remove -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1">
                                <button type="button" 
                                        class="text-gray-600 dark:text-gray-300 hover:text-blue-600 text-base decrease-qty"
                                        data-id="{{ $item['id'] ?? '' }}">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="w-6 text-center font-medium text-sm qty-display">
                                    {{ $item['quantity'] ?? 1 }}
                                </span>
                                <button type="button" 
                                        class="text-gray-600 dark:text-gray-300 hover:text-blue-600 text-base increase-qty"
                                        data-id="{{ $item['id'] ?? '' }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <button type="button" 
                                    class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500 text-sm remove-item"
                                    data-id="{{ $item['id'] ?? '' }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Order Summary -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-3">
            <div class="flex justify-between text-base">
                <span class="text-gray-700 dark:text-gray-300">Subtotal</span>
                <span id="cart-subtotal" class="font-semibold text-gray-900 dark:text-gray-100">
                    ${{ number_format($subtotal ?? 0, 2) }}
                </span>
            </div>

            <div class="flex justify-between text-base">
                <span class="text-gray-700 dark:text-gray-300">Tax (10%)</span>
                <span id="cart-tax" class="font-semibold text-gray-900 dark:text-gray-100">
                    ${{ number_format(($subtotal ?? 0) * 0.10, 2) }}
                </span>
            </div>

            <div class="flex justify-between text-lg font-bold pt-3 border-t border-gray-200 dark:border-gray-700">
                <span class="text-gray-900 dark:text-gray-100">Total</span>
                <span id="cart-grand-total" class="text-blue-600 dark:text-blue-400">
                    ${{ number_format(($subtotal ?? 0) * 1.10, 2) }}
                </span>
            </div>
        </div>
        <div class="mt-4">
            <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Customer
            </label>
            <select id="customer_id" name="customer_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2 transition-colors">
                <option value="">-- Optional --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone ?? 'No phone' }})</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4" id="table-field-container">
            <label for="table_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Table <span class="text-red-500">*</span>
            </label>
            <select id="table_id" name="table_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2 transition-colors">
                <option value="">-- Select a table --</option>
                @foreach($tables as $table)
                    <option value="{{ $table->id }}">Table {{ $table->table_number }} ({{ $table->capacity }} seats)</option>
                @endforeach
            </select>
            @error('table_id')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <!-- Action Buttons -->
        <div class="mt-6 space-y-3">

            <button id="place-order-btn"
                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow transition disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-check-circle mr-2"></i>
                Place Order
            </button>
            


            <button id="save-cart-btn"
                    class="w-full py-3 px-4 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-md shadow transition disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-save mr-2"></i>
                Save Cart
            </button>
        </div>
    </div>
