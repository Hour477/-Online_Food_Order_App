@extends('admin.layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
        background-color: #f9fafb !important;
        height: 42px !important;
        padding: 4px 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #111827 !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
        padding-left: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #9ca3af !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: transparent !important;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.5) !important;
    }
    .select2-container--default .select2-results__option--selected {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #f59e0b !important;
        color: white !important;
    }
    .select2-dropdown {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
    }
</style>
@endpush

@section('content')
<div class="mx-auto">

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">New Order</h3>
            <p class="text-sm text-gray-500 mt-1">Select items, add to cart, and place the order</p>
        </div>
        <a href="{{ route('admin.orders.index') }}"
           class="text-sm text-gray-500 hover:text-gray-900 flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- LEFT SIDE: Search + Categories + Menu --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Search Bar --}}
            <div class="relative">
                <input id="search-dishes" type="text"
                       placeholder="Search for dishes..."
                       class="w-full pl-12 pr-5 py-3 border border-gray-200 bg-gray-50 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            {{-- Categories Tabs --}}
            <div class="flex flex-wrap gap-3 pb-4 border-b border-gray-200 overflow-x-auto">
                <button data-category="all"
                        class="category-tab px-5 py-2.5 rounded-full font-medium text-sm transition-all bg-amber-600 text-white shadow-md">
                    All Items
                </button>

                @foreach($categories as $category)
                    <button data-category="{{ $category->id }}"
                            class="category-tab px-5 py-2.5 rounded-full font-medium text-sm transition-all text-gray-700 hover:bg-gray-200 border border-gray-300">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            {{-- Menu Items Grid --}}
            <div id="menu-items" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($menuItems as $item)
                    <div class="menu-item bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:scale-[1.02] group"
                         data-category="{{ $item->category_id }}">

                        {{-- Image --}}
                        <div class="h-40 bg-gray-100 relative overflow-hidden">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-4">
                            <h3 class="font-semibold text-sm text-gray-900 line-clamp-1">
                                {{ $item->name }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2 min-h-8">
                                {{ Str::limit($item->description ?? 'Delicious signature dish', 50) }}
                            </p>

                            <div class="mt-3 flex items-center justify-between">
                                <div class="text-lg font-bold text-amber-600">
                                    ${{ number_format($item->price, 2) }}
                                </div>

                                <button type="button"
                                        class="add-to-cart-btn px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg shadow-sm transition-all transform hover:scale-105 active:scale-95"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ addslashes($item->name) }}"
                                        data-price="{{ $item->price }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-lg">No menu items available</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT SIDE: Cart (sticky) --}}
        <div class="lg:col-span-4">
            @include('admin.orders.partials.cart')
        </div>

    </div>
</div>

{{-- Hidden form for submitting the order --}}
<form id="order-form" action="{{ route('admin.orders.store') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="total_amount" id="total_amount_hidden" value="0">
</form>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// =============================================
// Cart Management - Dynamic POS Cart
// =============================================

// Cart state (persisted in localStorage)
let cart = JSON.parse(localStorage.getItem('restaurant_pos_cart')) || [];

// Tax rate (adjust as needed)
const TAX_RATE = 0.10;

// DOM references
const cartContainer     = document.getElementById('cart-items-container');
const cartCountBadge    = document.getElementById('cart-count-badge');
const subtotalEl        = document.getElementById('cart-subtotal');
const taxEl             = document.getElementById('cart-tax');
const totalEl           = document.getElementById('cart-grand-total');
const placeOrderBtn     = document.getElementById('place-order-btn');
const clearCartBtn      = document.getElementById('clear-cart-btn');
const paymentMethodEl   = document.getElementById('payment_method');
const paidAmountEl      = document.getElementById('paid_amount');
const changeAmountEl    = document.getElementById('change_amount');

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('restaurant_pos_cart', JSON.stringify(cart));
}

// Render / update cart UI
function renderCart() {
    cartContainer.innerHTML = '';

    if (cart.length === 0) {
        cartContainer.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-sm font-medium">Your cart is empty</p>
                <p class="text-xs mt-1 text-gray-400">Start adding items from the menu</p>
            </div>
        `;
        placeOrderBtn.disabled = true;
        cartCountBadge.textContent = '(0 items)';
        subtotalEl.textContent = '$0.00';
        taxEl.textContent = '$0.00';
        totalEl.textContent = '$0.00';
        updateChange();
        return;
    }

    placeOrderBtn.disabled = false;
    let subtotal = 0;

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const itemRow = document.createElement('div');
        itemRow.className = 'flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-200';
        itemRow.innerHTML = `
            <div class="flex-1 min-w-0">
                <div class="font-medium text-gray-900 text-sm truncate">
                    ${item.name}
                </div>
                <div class="text-xs text-gray-500 mt-0.5">
                    $${item.price.toFixed(2)} × ${item.quantity}
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-2 py-1">
                    <button type="button" class="text-gray-600 hover:text-amber-600 text-sm font-bold decrease-qty" data-index="${index}">−</button>
                    <span class="w-6 text-center font-medium text-sm">${item.quantity}</span>
                    <button type="button" class="text-gray-600 hover:text-amber-600 text-sm font-bold increase-qty" data-index="${index}">+</button>
                </div>
                <button type="button" class="text-red-500 hover:text-red-700 text-xs font-medium remove-item" data-index="${index}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        `;
        cartContainer.appendChild(itemRow);
    });

    const tax = subtotal * TAX_RATE;
    const total = subtotal + tax;

    subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
    taxEl.textContent      = `$${tax.toFixed(2)}`;
    totalEl.textContent    = `$${total.toFixed(2)}`;
    cartCountBadge.textContent = `(${cart.length} items)`;
    updateChange();
}

// Calculate and update change amount
function updateChange() {
    const total = parseFloat(totalEl.textContent.replace('$', '')) || 0;
    const paid = parseFloat(paidAmountEl.value) || 0;
    const change = paid - total;
    
    if (changeAmountEl) {
        changeAmountEl.textContent = `$${change > 0 ? change.toFixed(2) : '0.00'}`;
    }
}

if (paidAmountEl) {
    paidAmountEl.addEventListener('input', updateChange);
}

// Observe changes to the total amount and update the change
const observer = new MutationObserver(updateChange);
if (totalEl) {
    observer.observe(totalEl, { childList: true, subtree: true });
}

// Category filtering tabs
if (document.querySelectorAll('.category-tab').length) {
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.category-tab').forEach(t => {
                t.classList.remove('bg-amber-600', 'text-white', 'shadow-md');
                t.classList.add('text-gray-700', 'hover:bg-gray-200', 'border', 'border-gray-300');
            });

            this.classList.add('bg-amber-600', 'text-white', 'shadow-md');
            this.classList.remove('text-gray-700', 'hover:bg-gray-200', 'border', 'border-gray-300');

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
    // Add payment fields
    if (paymentMethodEl.value) {
        let paymentMethodHidden = document.querySelector('input[name="payment_method"]');
        if (!paymentMethodHidden) {
            paymentMethodHidden = document.createElement('input');
            paymentMethodHidden.type = 'hidden';
            paymentMethodHidden.name = 'payment_method';
            orderFormEl.appendChild(paymentMethodHidden);
        }
        paymentMethodHidden.value = paymentMethodEl.value;

        let paidAmountHidden = document.querySelector('input[name="paid_amount"]');
        if (!paidAmountHidden) {
            paidAmountHidden = document.createElement('input');
            paidAmountHidden.type = 'hidden';
            paidAmountHidden.name = 'paid_amount';
            orderFormEl.appendChild(paidAmountHidden);
        }
        paidAmountHidden.value = paidAmountEl.value;
    }

    // set total hidden if not already set
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
    
    // Add payment fields
    if (paymentMethodEl.value) {
        let paymentMethodHidden = document.querySelector('input[name="payment_method"]');
        if (!paymentMethodHidden) {
            paymentMethodHidden = document.createElement('input');
            paymentMethodHidden.type = 'hidden';
            paymentMethodHidden.name = 'payment_method';
            orderFormEl.appendChild(paymentMethodHidden);
        }
        paymentMethodHidden.value = paymentMethodEl.value;

        let paidAmountHidden = document.querySelector('input[name="paid_amount"]');
        if (!paidAmountHidden) {
            paidAmountHidden = document.createElement('input');
            paidAmountHidden.type = 'hidden';
            paidAmountHidden.name = 'paid_amount';
            orderFormEl.appendChild(paidAmountHidden);
        }
        paidAmountHidden.value = paidAmountEl.value;
    }

    // Ensure hidden item inputs and other hidden fields are added BEFORE submitting.
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
@endpush
