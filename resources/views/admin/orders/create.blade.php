@extends('admin.layouts.app')
@section('title', 'Create Order')

@section('content')
@push('styles')

<style>
    .select2-container--default .select2-selection--single {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
        background-color: #f9fafb !important;
        height: 42px !important;
        padding: 4px 8px !important;
    }
    .dark .select2-container--default .select2-selection--single {
        border-color: #374151 !important;
        background-color: #374151 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #111827 !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
        padding-left: 8px !important;
    }
    .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #f9fafb !important;
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
    .dark .select2-container--default .select2-results__option--selected {
        background-color: #92400e !important;
        color: #fef3c7 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #f59e0b !important;
        color: white !important;
    }
    .select2-dropdown {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
    }
    .dark .select2-dropdown {
        border-color: #374151 !important;
        background-color: #1f2937 !important;
        color: #f9fafb !important;
    }
    .dark .select2-results__option {
        color: #f9fafb !important;
    }
</style>
@endpush

@section('content')
<div class="mx-auto">

    {{-- Header --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                <i class="fas fa-plus text-amber-600 text-sm"></i>
            </span>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">New Order</h3>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 ml-10">Select items, add to cart, and place the order</p>
    </div>
    <a href="{{ route('admin.orders.all') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 font-medium transition-colors bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-2 shadow-sm hover:shadow">
        <i class="fas fa-arrow-left text-xs"></i> Back
    </a>
</div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- LEFT SIDE: Search + Categories + Menu --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Search Bar --}}
        <div class="relative">
            <input id="search-dishes" type="text"
                   placeholder="Search for dishes, drinks…"
                   class="w-full pl-12 pr-5 py-3.5 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition shadow-sm text-sm">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                <kbd class="hidden sm:inline text-[10px] text-gray-300 dark:text-gray-600 border border-gray-200 dark:border-gray-700 rounded px-1.5 py-0.5">⌘K</kbd>
            </div>
        </div>

        {{-- Categories Tabs --}}
        <div class="flex flex-wrap gap-2 pb-4 border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
            <button data-category="all"
                    class="category-tab px-4 py-2 rounded-full font-semibold text-xs transition-all bg-amber-500 text-white shadow-md shadow-amber-200 dark:shadow-amber-900/40 scale-105">
                <i class="fas fa-th-large mr-1"></i> All
            </button>
            @foreach($categories as $category)
            <button data-category="{{ $category->id }}"
                    class="category-tab px-4 py-2 rounded-full font-semibold text-xs transition-all text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 border border-gray-200 dark:border-gray-700 hover:border-amber-300 hover:text-amber-600">
                {{ $category->name }}
            </button>
            @endforeach
            <button data-category="null"
                    class="category-tab px-4 py-2 rounded-full font-semibold text-xs transition-all text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 border border-gray-200 dark:border-gray-700 hover:border-amber-300 hover:text-amber-600">
                <i class="fas fa-question-circle mr-1"></i> Uncategorized
            </button>
        </div>

        {{-- Menu Items Grid --}}
        <div id="menu-items" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($menuItems as $item)
            <div class="menu-item group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-lg hover:-translate-y-1 cursor-pointer"
                 data-category="{{ $item->category_id ?? 'null' }}"
                 onclick="this.querySelector('.add-to-cart-btn').click()">

                {{-- Image --}}
                <div class="relative h-36 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                    <img src="{{ $item->display_image }}" alt="{{ $item->name }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-amber-600/80 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-lg">
                            <i class="fas fa-plus text-amber-600 text-lg"></i>
                        </div>
                    </div>
                    {{-- Rating badge --}}
                    @if($item->rating > 0)
                    <div class="absolute top-2 left-2 flex items-center gap-0.5 bg-black/50 backdrop-blur-sm text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                        <i class="fas fa-star text-amber-400 text-[8px]"></i> {{ number_format($item->rating, 1) }}
                    </div>
                    @endif
                    {{-- Category badge --}}
                    @if($item->category)
                    <div class="absolute top-2 right-2 bg-amber-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full truncate max-w-[70px]">
                        {{ $item->category->name }}
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-3">
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white line-clamp-1">{{ $item->name }}</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 line-clamp-1">
                        {{ Str::limit($item->description ?? 'Signature dish', 38) }}
                    </p>
                    <div class="mt-2.5 flex items-center justify-between">
                        <span class="text-base font-black text-amber-600">${{ number_format($item->price, 2) }}</span>
                        <button type="button"
                                class="add-to-cart-btn w-7 h-7 rounded-full bg-amber-500 hover:bg-amber-600 text-white flex items-center justify-center shadow transition-all hover:scale-110 active:scale-95"
                                data-id="{{ $item->id }}"
                                data-name="{{ addslashes($item->name) }}"
                                data-price="{{ $item->price }}"
                                onclick="event.stopPropagation()">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400">
                <i class="fas fa-utensils text-5xl mb-4 text-gray-200 dark:text-gray-700"></i>
                <p class="text-lg font-semibold">No menu items available</p>
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
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-sm font-medium">Your cart is empty</p>
                <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">Start adding items from the menu</p>
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
        itemRow.className = 'flex items-center gap-3 bg-gray-50 dark:bg-gray-900/60 p-3 rounded-xl border border-gray-100 dark:border-gray-700 group';
        itemRow.innerHTML = `
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 dark:text-white text-sm truncate">${item.name}</p>
                <p class="text-xs text-gray-400 mt-0.5">$${item.price.toFixed(2)} each</p>
            </div>
            <div class="flex items-center gap-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-1.5 py-1">
                <button type="button" class="decrease-qty w-5 h-5 flex items-center justify-center rounded text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition text-sm font-bold" data-index="${index}">−</button>
                <span class="w-6 text-center text-sm font-bold text-gray-900 dark:text-white">${item.quantity}</span>
                <button type="button" class="increase-qty w-5 h-5 flex items-center justify-center rounded text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition text-sm font-bold" data-index="${index}">+</button>
            </div>
            <span class="text-xs font-bold text-amber-600 w-14 text-right">$${itemTotal.toFixed(2)}</span>
            <button type="button" class="remove-item w-6 h-6 flex items-center justify-center rounded-full text-gray-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition opacity-0 group-hover:opacity-100" data-index="${index}">
                <svg class="w-3.5 h-3.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
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
                t.classList.remove('bg-amber-500', 'text-white', 'shadow-md', 'shadow-amber-200', 'scale-105');
                t.classList.add('text-gray-600', 'dark:text-gray-300', 'bg-white', 'dark:bg-gray-800',
                    'border', 'border-gray-200', 'hover:bg-amber-50', 'hover:border-amber-300', 'hover:text-amber-600');
            });

            this.classList.add('bg-amber-500', 'text-white', 'shadow-md', 'scale-105');
            this.classList.remove('text-gray-600', 'dark:text-gray-300', 'bg-white', 'dark:bg-gray-800',
                'border', 'border-gray-200', 'hover:bg-amber-50', 'hover:border-amber-300', 'hover:text-amber-600');

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
   
    // Add payment fields
    if (paymentMethodEl.value) {
        let paymentMethodHidden = orderFormEl.querySelector('input[name="payment_method"]');
        if (!paymentMethodHidden) {
            paymentMethodHidden = document.createElement('input');
            paymentMethodHidden.type = 'hidden';
            paymentMethodHidden.name = 'payment_method';
            orderFormEl.appendChild(paymentMethodHidden);
        }
        paymentMethodHidden.value = paymentMethodEl.value;

        let paidAmountHidden = orderFormEl.querySelector('input[name="paid_amount"]');
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
    if (paymentMethodEl?.value === 'khqr') {
        window.location.href = '{{ route('admin.orders.qr') }}';
        return;
    }

    const orderFormEl = document.getElementById('order-form');
    if (!orderFormEl) return;

    // calculate total for hidden field
    const subtotal = parseFloat(subtotalEl.textContent.replace('$', '')) || 0;
    const tax = parseFloat(taxEl.textContent.replace('$', '')) || 0;
    const grandTotal = subtotal + tax;

    if (paymentMethodEl?.value === 'cash' || paymentMethodEl?.value === 'card') {
        const paid = parseFloat(paidAmountEl.value) || 0;
        if (paid < grandTotal) {
            alert('Error: Paid amount ($' + paid.toFixed(2) + ') must be greater than or equal to the total order amount ($' + grandTotal.toFixed(2) + ').');
            // Re-enable or just return to let them fix it
            return;
        }
    }

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
        let el = document.getElementById('table_id_hidden');
        if (!el) {
            el = document.createElement('input');
            el.type = 'hidden';
            el.name = 'table_id';
            el.id = 'table_id_hidden';
            orderFormEl.appendChild(el);
        }
        el.value = tableIdSelect.value || null;
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
        let paymentMethodHidden = orderFormEl.querySelector('input[name="payment_method"]');
        if (!paymentMethodHidden) {
            paymentMethodHidden = document.createElement('input');
            paymentMethodHidden.type = 'hidden';
            paymentMethodHidden.name = 'payment_method';
            orderFormEl.appendChild(paymentMethodHidden);
        }
        paymentMethodHidden.value = paymentMethodEl.value;

        let paidAmountHidden = orderFormEl.querySelector('input[name="paid_amount"]');
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

    // Immediately clear cart on the POS page for the next order
    cart = [];
    saveCart();
    renderCart();
    
    // Optional: focus back on search
    document.getElementById('search-dishes')?.focus();
});
</script>
@endpush
