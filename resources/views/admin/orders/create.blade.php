    @extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                New Order
            </h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">
                Select items, add to cart, and place the order
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
            ← Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- LEFT SIDE: Search + Categories + Menu -->
        <div class="lg:col-span-8 space-y-8">

            <!-- Search Bar -->
            <div class="relative">
                <input id="search-dishes" type="text"
                       placeholder="Search for dishes..."
                       class="w-full pl-12 pr-5 py-4 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-lg"></i>
                </div>
            </div>

            <!-- Categories Tabs -->
            <div class="flex flex-wrap gap-3 pb-4 border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                <button data-category="all"
                        class="category-tab px-5 py-2.5 rounded-full font-medium text-sm transition-all bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white shadow-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    All Items
                </button>

                @foreach($categories as $category)
                    <button data-category="{{ $category->id }}"
                            class="category-tab px-5 py-2.5 rounded-full font-medium text-sm transition-all text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <!-- Menu Items Grid -->
            <div id="menu-items" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($menuItems as $item)
                    <div class="menu-item bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-lg hover:scale-[1.02] group"
                         data-category="{{ $item->category_id }}">

                        <!-- Image -->
                        <div class="h-48 bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i class="fas fa-utensils text-6xl text-gray-300 dark:text-gray-600"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="p-5">
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-white line-clamp-1">
                                {{ $item->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2 min-h-10">
                                {{ Str::limit($item->description ?? 'Delicious signature dish', 70) }}
                            </p>

                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-xl font-bold text-emerald-600 dark:text-emerald-400">
                                    ${{ number_format($item->price, 2) }}
                                </div>

                                <button type="button"
                                        class="add-to-cart-btn px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-xl shadow-sm transition-all transform hover:scale-105 active:scale-95"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ addslashes($item->name) }}"
                                        data-price="{{ $item->price }}">
                                    <i class="fas fa-plus"></i>
                                    
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-utensils text-6xl opacity-30 mb-4"></i>
                        <p class="text-lg">No menu items available</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT SIDE: Cart (sticky) -->
        <div class="lg:col-span-4">
            <!-- resources/views/orders/partials/cart.blade.php -->
            @include('admin.orders.partials.cart')
        </div>

    </div>
</div>

<!-- Hidden form for submitting the order (items will be added via JS) -->
<form id="order-form" action="{{ route('admin.orders.store') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="total_amount" id="total_amount_hidden" value="0">
</form>

@endsection

@section('scripts')
<script>
// =============================================
// Cart Management - Dynamic POS Cart
// =============================================

// Cart state (persisted in localStorage)
let cart = JSON.parse(localStorage.getItem('restaurant_pos_cart')) || [];

// Tax rate (adjust as needed)
const TAX_RATE = 0.10;

// DOM references
// IDs updated to match the markup in this view
const cartContainer     = document.getElementById('cart-items-container');
const cartCountBadge    = document.getElementById('cart-count-badge');
const subtotalEl        = document.getElementById('cart-subtotal');
const taxEl             = document.getElementById('cart-tax');
const totalEl           = document.getElementById('cart-grand-total');
const placeOrderBtn     = document.getElementById('place-order-btn');
const clearCartBtn      = document.getElementById('clear-cart-btn');
// orderForm is looked up later when needed to avoid timing issues

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('restaurant_pos_cart', JSON.stringify(cart));
}

// Render / update cart UI
function renderCart() {
    cartContainer.innerHTML = '';

    if (cart.length === 0) {
        cartContainer.innerHTML = `
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <i class="fas fa-shopping-cart text-6xl opacity-30 mb-4"></i>
                <p class="text-lg font-medium">Your cart is empty</p>
                <p class="text-sm mt-2">Start adding items from the menu</p>
            </div>
        `;
        placeOrderBtn.disabled = true;
        cartCountBadge.textContent = '(0)';
        subtotalEl.textContent = '$0.00';
        taxEl.textContent = '$0.00';
        totalEl.textContent = '$0.00';
        return;
    }

    placeOrderBtn.disabled = false;
    let subtotal = 0;

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const itemRow = document.createElement('div');
        itemRow.className = 'flex justify-between items-center bg-gray-50 dark:bg-gray-700/40 p-4 rounded-xl border border-gray-100 dark:border-gray-700';
        itemRow.innerHTML = `
            <div class="flex-1 min-w-0">
                <div class="font-medium text-gray-900 dark:text-white truncate">
                    ${item.name}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                    $${item.price.toFixed(2)} × ${item.quantity}
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1">
                    <button type="button" class="text-gray-600 dark:text-gray-300 hover:text-amber-600 text-lg font-bold decrease-qty" data-index="${index}">-</button>
                    <span class="w-8 text-center font-medium">${item.quantity}</span>
                    <button type="button" class="text-gray-600 dark:text-gray-300 hover:text-amber-600 text-lg font-bold increase-qty" data-index="${index}">+</button>
                </div>
                <button type="button" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium remove-item" data-index="${index}">×</button>
            </div>
        `;
        cartContainer.appendChild(itemRow);
    });

    const tax = subtotal * TAX_RATE;
    const total = subtotal + tax;

    subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
    taxEl.textContent      = `$${tax.toFixed(2)}`;
    totalEl.textContent    = `$${total.toFixed(2)}`;
    cartCountBadge.textContent = `(${cart.length})`;
}

// Category filtering tabs
if (document.querySelectorAll('.category-tab').length) {
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.category-tab').forEach(t => {
                t.classList.remove('bg-amber-600', 'text-white', 'shadow-md');
                t.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
            });

            this.classList.add('bg-amber-600', 'text-white', 'shadow-md');
            this.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');

            const category = this.dataset.category;
            document.querySelectorAll('.menu-item').forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    });
}

// Search filter for dishes
const searchInput = document.getElementById('search-dishes');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        document.querySelectorAll('#menu-items .menu-item').forEach(item => {
            const title = item.querySelector('h3')?.textContent.toLowerCase() || '';
            if (title.includes(query)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    });
}

// Add item to cart
document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', () => {
        const id    = button.dataset.id;
        const name  = button.dataset.name;
        const price = parseFloat(button.dataset.price);

        const existingItem = cart.find(item => item.id === id);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }

        renderCart();
        saveCart();
    });
});

// Cart item controls (increase / decrease / remove)
cartContainer.addEventListener('click', e => {
    const target = e.target;

    if (!target.dataset.index) return;

    const index = parseInt(target.dataset.index);

    if (target.classList.contains('increase-qty')) {
        cart[index].quantity += 1;
    } else if (target.classList.contains('decrease-qty')) {
        if (cart[index].quantity > 1) {
            cart[index].quantity -= 1;
        } else {
            cart.splice(index, 1);
        }
    } else if (target.classList.contains('remove-item')) {
        cart.splice(index, 1);
    }

    renderCart();
    saveCart();
});

// Clear entire cart
clearCartBtn?.addEventListener('click', () => {
    if (confirm('Are you sure you want to clear the entire cart?')) {
        cart = [];
        renderCart();
        saveCart();
    }
});

// Prepare form data before submit
const submitFormHandler = e => {
    const orderFormEl = document.getElementById('order-form');
    if (!orderFormEl) return;

    // Remove old hidden fields
    orderFormEl.querySelectorAll('input[name^="items"]').forEach(el => el.remove());

    // Add current cart items as hidden inputs
    cart.forEach((item, index) => {
        const container = document.createElement('div');
        container.innerHTML = `
            <input type="hidden" name="items[${index}][menu_item_id]" value="${item.id}">
            <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            <input type="hidden" name="items[${index}][price]" value="${item.price}">
        `;
        orderFormEl.appendChild(container);
    });

    // also copy customer, table, and order_type selections into hidden fields
    const customerIdSelect = document.getElementById('customer_id');
    const tableIdSelect = document.getElementById('table_id');
    const orderTypeSelect = document.getElementById('order_type');
    
    if (customerIdSelect) {
        let el = document.getElementById('customer_id_hidden');
        if (!el) {
            el = document.createElement('input');
            el.type = 'hidden';
            el.name = 'customer_id';
            el.id = 'customer_id_hidden';
            orderFormEl.appendChild(el);
        }
        el.value = customerIdSelect.value || null;
    }
    if (tableIdSelect) {
        // Only send table_id for dine-in orders
        const orderType = orderTypeSelect?.value || 'dine_in';
        if (orderType === 'dine_in') {
            let el = document.getElementById('table_id_hidden');
            if (!el) {
                el = document.createElement('input');
                el.type = 'hidden';
                el.name = 'table_id';
                el.id = 'table_id_hidden';
                orderFormEl.appendChild(el);
            }
            el.value = tableIdSelect.value || null;
        } else {
            // Remove table_id hidden input for non-dine-in orders
            const el = document.getElementById('table_id_hidden');
            if (el) el.remove();
        }
    }
    if (orderTypeSelect) {
        let el = document.getElementById('order_type_hidden');
        if (!el) {
            el = document.createElement('input');
            el.type = 'hidden';
            el.name = 'order_type';
            el.id = 'order_type_hidden';
            orderFormEl.appendChild(el);
        }
        el.value = orderTypeSelect.value;
    }

    // set total hidden if not already set (in case user submits via form directly)
    const totalHidden = document.getElementById('total_amount_hidden');
    const subtotal = parseFloat(subtotalEl.textContent.replace('$', '')) || 0;
    const tax = parseFloat(taxEl.textContent.replace('$', '')) || 0;
    if (totalHidden) totalHidden.value = (subtotal + tax).toFixed(2);
};

// attach when form exists
const orderFormEl = document.getElementById('order-form');
if (orderFormEl) {
    orderFormEl.addEventListener('submit', submitFormHandler);
}

// Initialize cart on page load
renderCart();

// =============================================
// Conditional Fields - Order Type Logic
// =============================================

const orderTypeSelect = document.getElementById('order_type');
const tableFieldContainer = document.getElementById('table-field-container');
const tableSelect = document.getElementById('table_id');

function updateFieldsForOrderType() {
    const selectedType = orderTypeSelect?.value || 'dine_in';
    
    if (selectedType === 'dine_in') {
        // Show table field for dine-in orders
        if (tableFieldContainer) tableFieldContainer.style.display = 'block';
        if (tableSelect) tableSelect.required = true;
    } else {
        // Hide/disable table field for takeout/delivery
        if (tableFieldContainer) tableFieldContainer.style.display = 'none';
        if (tableSelect) {
            tableSelect.required = false;
            tableSelect.value = ''; // Clear the selection
        }
    }
}

// Run on page load
updateFieldsForOrderType();

// Listen for order type changes
orderTypeSelect?.addEventListener('change', updateFieldsForOrderType);

// Submit handler for place order button
placeOrderBtn?.addEventListener('click', () => {
    const orderFormEl = document.getElementById('order-form');
    if (!orderFormEl) return;

    // calculate total for hidden field
    const subtotal = parseFloat(subtotalEl.textContent.replace('$', '')) || 0;
    const tax = parseFloat(taxEl.textContent.replace('$', '')) || 0;
    const grandTotal = subtotal + tax;
    const totalHidden = document.getElementById('total_amount_hidden');
    if (totalHidden) {
        totalHidden.value = grandTotal.toFixed(2);
    }

    // also copy selected customer, table, and order_type into hidden inputs
    const customerIdSelect = document.getElementById('customer_id');
    const tableIdSelect = document.getElementById('table_id');
    const orderTypeSelect = document.getElementById('order_type');
    
    if (customerIdSelect) {
        let el = document.getElementById('customer_id_hidden');
        if (!el) {
            el = document.createElement('input');
            el.type = 'hidden';
            el.name = 'customer_id';
            el.id = 'customer_id_hidden';
            orderFormEl.appendChild(el);
        }
        el.value = customerIdSelect.value || null;
    }
    if (tableIdSelect) {
        // Only send table_id for dine-in orders
        const orderType = orderTypeSelect?.value || 'dine_in';
        if (orderType === 'dine_in') {
            let el = document.getElementById('table_id_hidden');
            if (!el) {
                el = document.createElement('input');
                el.type = 'hidden';
                el.name = 'table_id';
                el.id = 'table_id_hidden';
                orderFormEl.appendChild(el);
            }
            el.value = tableIdSelect.value || null;
        } else {
            // Remove table_id hidden input for non-dine-in orders
            const el = document.getElementById('table_id_hidden');
            if (el) el.remove();
        }
    }
    if (orderTypeSelect) {
        let el = document.getElementById('order_type_hidden');
        if (!el) {
            el = document.createElement('input');
            el.type = 'hidden';
            el.name = 'order_type';
            el.id = 'order_type_hidden';
            orderFormEl.appendChild(el);
        }
        el.value = orderTypeSelect.value;
    }

    // Ensure hidden item inputs and other hidden fields are added BEFORE submitting.
    // Prefer requestSubmit() which triggers form submit handlers and validation.
    if (typeof orderFormEl.requestSubmit === 'function') {
        // call the prepare handler first to guarantee inputs exist
        if (typeof submitFormHandler === 'function') submitFormHandler();
        orderFormEl.requestSubmit();
    } else {
        // Fallback for older browsers: populate then call native submit
        if (typeof submitFormHandler === 'function') submitFormHandler();
        orderFormEl.submit();
    }
});
</script>
@endsection
