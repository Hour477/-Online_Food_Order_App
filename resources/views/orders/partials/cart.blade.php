<div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-6 sticky top-6">
    <!-- Cart Header -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-3">
            <i class="fas fa-shopping-cart text-emerald-600"></i>
            Cart Summary
            <span id="cart-count-badge" class="text-sm font-medium text-gray-500 dark:text-gray-400">
                (0 items)   
            </span>
        </h3>

        <button id="clear-cart-btn"
                class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition"
                {{ empty($cart ?? []) ? 'hidden' : '' }}>
            Clear All
        </button>
    </div>
    {{-- Order-type  --}}
    <div class="mb-6 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
        <label for="order_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Order Type <span class="text-red-500">*</span>
        </label>
       
        <select id="order_type" name="order_type" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 transition-colors">
            @foreach ($order_types as $type)
                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </div>

    <!-- Cart Items List -->
    <div id="cart-items-container" class="space-y-4 mb-8 max-h-[45vh] overflow-y-auto pr-2">
            @if(empty($cart ?? []))
                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <i class="fas fa-shopping-cart text-6xl opacity-30 mb-4"></i>
                    <p class="text-lg font-medium">Cart is empty</p>
                    <p class="text-sm mt-2">Add delicious items from the menu</p>
                </div>
            @else
                @foreach($cart as $item)
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/40 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                        <!-- Item Info -->
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 dark:text-white line-clamp-1">
                                {{ $item['name'] ?? 'Unnamed Item' }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                ${{ number_format($item['price'] ?? 0, 2) }} x {{ $item['quantity'] ?? 1 }}
                            </div>
                        </div>

                        <!-- Quantity & Remove -->
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1">
                                <button type="button" 
                                        class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 text-lg font-bold decrease-qty"
                                        data-id="{{ $item['id'] ?? '' }}">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="w-8 text-center font-medium qty-display">
                                    {{ $item['quantity'] ?? 1 }}
                                </span>
                                <button type="button" 
                                        class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 text-lg font-bold increase-qty"
                                        data-id="{{ $item['id'] ?? '' }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <button type="button" 
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium remove-item"
                                    data-id="{{ $item['id'] ?? '' }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Order Summary -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
            <div class="flex justify-between text-lg">
                <span class="text-gray-700 dark:text-gray-300">Subtotal</span>
                <span id="cart-subtotal" class="font-bold text-gray-900 dark:text-white">
                    ${{ number_format($subtotal ?? 0, 2) }}
                </span>
            </div>

            <div class="flex justify-between text-lg">
                <span class="text-gray-700 dark:text-gray-300">Tax (10%)</span>
                <span id="cart-tax" class="font-bold text-gray-900 dark:text-white">
                    ${{ number_format(($subtotal ?? 0) * 0.10, 2) }}
                </span>
            </div>

            <div class="flex justify-between text-2xl font-bold pt-4 border-t border-gray-200 dark:border-gray-700">
                <span class="text-gray-900 dark:text-white">Total</span>
                <span id="cart-grand-total" class="text-emerald-600 dark:text-emerald-400">
                    ${{ number_format(($subtotal ?? 0) * 1.10, 2) }}
                </span>
            </div>
        </div>
        <div class="mt-4">
            <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Customer
            </label>
            <select id="customer_id" name="customer_id" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 transition-colors">
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
            <select id="table_id" name="table_id" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 transition-colors">
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
        <div class="mt-8 space-y-4">

            <button id="place-order-btn"
                    class="w-full py-4 px-6 bg-linear-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold text-lg rounded-xl shadow-lg transition-all transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-check-circle mr-2"></i>
                PLACE ORDER NOW
            </button>
            


            <button id="save-cart-btn"
                    class="w-full py-4 px-6 bg-gray-600 hover:bg-gray-700 text-white font-bold text-lg rounded-xl shadow-lg transition-all transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-save mr-2"></i>
                Save Cart
            </button>
        </div>

    </div>
