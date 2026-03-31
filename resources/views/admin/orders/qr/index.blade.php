@extends('admin.layouts.app')
@section('title', 'Create KHQR Order')

@push('styles')
<style>
    .select2-container--default .select2-selection--single {
        border: 1px solid #e5e7eb !important; border-radius: 0.5rem !important;
        background-color: #f9fafb !important; height: 38px !important; padding: 4px 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #111827 !important; font-size: 0.875rem !important; line-height: 1.5 !important; padding-left: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: transparent !important; box-shadow: 0 0 0 2px rgba(245,158,11,0.5) !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #f59e0b !important; color: white !important; }
    .select2-dropdown { border: 1px solid #e5e7eb !important; border-radius: 0.5rem !important; }
    .khqr-pulse { animation: khqr-glow 2.5s ease-in-out infinite; }
    @keyframes khqr-glow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245,158,11,0); }
        50% { box-shadow: 0 0 0 8px rgba(245,158,11,0.12); }
    }
</style>
@endpush

@section('content')
<div class="mx-auto">

    {{-- ── PAGE HEADER ── --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                    <i class="fas fa-qrcode text-amber-600 text-sm"></i>
                </span>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">New KHQR Order</h3>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 ml-10">Pick items on the left, then confirm the KHQR on the right</p>
        </div>
        <a href="{{ route('admin.orders.create') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-amber-600 font-medium transition-colors bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-2 shadow-sm hover:shadow">
            <i class="fas fa-arrow-left text-xs"></i> Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- ══════════════════════════════════════════
             LEFT — Menu Picker
        ══════════════════════════════════════════ --}}
        <div class="lg:col-span-8 space-y-5">

            {{-- Search --}}
            <div class="relative">
                <input id="search-dishes" type="text"
                       placeholder="Search for dishes, drinks…"
                       class="w-full pl-12 pr-5 py-3.5 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition shadow-sm text-sm">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>

            {{-- Category tabs --}}
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
            </div>

            {{-- Menu Grid --}}
            <div id="menu-items" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($menuItems as $item)
                <div class="menu-item group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-lg hover:-translate-y-1 cursor-pointer"
                     data-category="{{ $item->category_id ?? 'null' }}"
                     onclick="this.querySelector('.add-to-cart-btn').click()">

                    {{-- Image with hover overlay --}}
                    <div class="relative h-36 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <img src="{{ $item->display_image }}" alt="{{ $item->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-amber-600/80 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-lg">
                                <i class="fas fa-plus text-amber-600 text-lg"></i>
                            </div>
                        </div>
                        @if($item->rating > 0)
                        <div class="absolute top-2 left-2 flex items-center gap-0.5 bg-black/50 backdrop-blur-sm text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                            <i class="fas fa-star text-amber-400 text-[8px]"></i> {{ number_format($item->rating, 1) }}
                        </div>
                        @endif
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
                            {{ \Illuminate\Support\Str::limit($item->description ?? 'Signature dish', 38) }}
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
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════
             RIGHT — Cart + KHQR Panel
        ══════════════════════════════════════════ --}}
        <div class="lg:col-span-4">
            <div class="sticky top-6 space-y-4">

                {{-- ── Cart Summary card ── --}}
                <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-shopping-cart text-white text-sm"></i>
                            <h4 class="text-white font-bold text-sm">Order Items</h4>
                            <span id="cart-count-badge" class="text-amber-100 text-xs">(0)</span>
                        </div>
                        <button id="clear-cart-btn"
                                class="text-white/70 hover:text-white text-xs font-semibold px-2 py-1 rounded-md hover:bg-white/20 transition-all">
                            <i class="fas fa-trash-alt text-[10px]"></i> Clear
                        </button>
                    </div>

                    {{-- Items --}}
                    <div id="cart-items-container" class="px-4 py-3 space-y-2 max-h-52 overflow-y-auto min-h-[80px]">
                        <div class="flex flex-col items-center justify-center py-6 text-gray-400 dark:text-gray-600 text-center">
                            <i class="fas fa-shopping-cart text-2xl mb-2 text-gray-200 dark:text-gray-700"></i>
                            <p class="text-xs">Add items from the menu</p>
                        </div>
                    </div>

                    {{-- Totals --}}
                    <div class="bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 px-4 py-3 space-y-1">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Subtotal</span>
                            <span id="cart-subtotal" class="font-semibold text-gray-700 dark:text-gray-300">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Tax (10%)</span>
                            <span id="cart-tax" class="font-semibold text-gray-700 dark:text-gray-300">${{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">Total</span>
                            <span id="cart-grand-total" class="text-xl font-black text-amber-600">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- ── KHQR Payment card ── --}}
                <div class="rounded-2xl overflow-hidden shadow-lg border border-amber-200 dark:border-amber-800 bg-white dark:bg-gray-800">
                    {{-- KHQR Header --}}
                    <div class="px-5 py-3.5 flex items-center justify-between border-b border-amber-100 dark:border-amber-800 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-qrcode text-amber-600 text-lg"></i>
                            <h4 class="font-bold text-gray-900 dark:text-white">KHQR Payment</h4>
                        </div>
                        <span class="flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-2.5 py-1 text-[10px] font-bold text-emerald-700 dark:text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                        </span>
                    </div>

                    {{-- QR Code --}}
                    <div class="px-5 pt-5 pb-3">
                        <div class="khqr-pulse rounded-2xl border-2 border-amber-200 dark:border-amber-700 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/10 p-4 text-center">
                            <div class="inline-block p-3 bg-white rounded-xl shadow-md mb-3">
                                <img src="{{ $qrImageUrl }}" alt="KHQR Code" class="h-48 w-48 object-contain">
                            </div>
                            <p class="text-[10px] uppercase tracking-[0.3em] text-amber-600 dark:text-amber-400 font-bold">Reference</p>
                            <p class="mt-0.5 text-sm font-mono font-semibold text-gray-700 dark:text-gray-300">{{ $qrReference }}</p>
                            <p class="mt-3 text-[10px] uppercase tracking-[0.3em] text-amber-600 dark:text-amber-400 font-bold">Amount</p>
                            <p id="khqr-total" class="mt-0.5 text-3xl font-black text-gray-900 dark:text-white">${{ number_format($total, 2) }}</p>
                        </div>
                    </div>

                    {{-- Order Type + Customer --}}
                    <div class="px-5 pb-4 space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Order Type</label>
                            <div class="grid grid-cols-3 gap-1.5 bg-gray-100 dark:bg-gray-900 p-1 rounded-xl">
                                @foreach ($order_types as $type)
                                <label class="cursor-pointer">
                                    <input type="radio" name="order_type_radio" value="{{ $type }}" class="sr-only peer" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="block text-center text-[10px] font-semibold py-1.5 rounded-lg peer-checked:bg-white dark:peer-checked:bg-gray-700 peer-checked:text-amber-600 peer-checked:shadow-sm text-gray-500 transition-all select-none">
                                        @if($type === 'dine_in') <i class="fas fa-utensils mr-0.5"></i>
                                        @elseif($type === 'takeout') <i class="fas fa-shopping-bag mr-0.5"></i>
                                        @else <i class="fas fa-motorcycle mr-0.5"></i>
                                        @endif
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                            <select id="order_type" class="sr-only">
                                @foreach ($order_types as $type)
                                <option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="customer_id" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                                <i class="fas fa-user text-amber-500 mr-1"></i> Customer (optional)
                            </label>
                            <select id="customer_id"
                                    class="block w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors">
                                <option value="">Walk-in customer</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone ?? 'No phone' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="px-5 pb-5 space-y-2">
                        <button id="place-order-btn"
                                class="w-full py-3 px-4 rounded-xl font-bold text-sm text-white shadow-lg transition-all hover:shadow-xl hover:-translate-y-0.5"
                                style="background: linear-gradient(135deg,#f59e0b 0%,#d97706 100%)">
                            <i class="fas fa-qrcode mr-2"></i> Create KHQR Order
                        </button>
                        <a href="{{ route('admin.orders.create') }}"
                           class="block w-full py-2.5 px-4 rounded-xl text-center font-semibold text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                            Cancel
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<form id="order-form" action="{{ route('admin.orders.store') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="payment_method" value="khqr">
    <input type="hidden" name="paid_amount" id="paid_amount_hidden" value="{{ number_format($total, 2, '.', '') }}">
</form>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let cart = JSON.parse(localStorage.getItem('restaurant_pos_cart')) || [];
const TAX_RATE = 0.10;
const cartContainer  = document.getElementById('cart-items-container');
const subtotalEl     = document.getElementById('cart-subtotal');
const taxEl          = document.getElementById('cart-tax');
const totalEl        = document.getElementById('cart-grand-total');
const khqrTotalEl    = document.getElementById('khqr-total');
const countBadge     = document.getElementById('cart-count-badge');

function saveCart() {
    localStorage.setItem('restaurant_pos_cart', JSON.stringify(cart));
}

function renderCart() {
    cartContainer.innerHTML = '';
    let subtotal = 0;

    if (cart.length === 0) {
        cartContainer.innerHTML = `
            <div class="flex flex-col items-center justify-center py-6 text-center text-gray-400 dark:text-gray-600">
                <i class="fas fa-shopping-cart text-2xl mb-2 text-gray-200 dark:text-gray-700"></i>
                <p class="text-xs">Add items from the menu</p>
            </div>`;
        if (countBadge) countBadge.textContent = '(0)';
        subtotalEl.textContent = '$0.00';
        taxEl.textContent = '$0.00';
        totalEl.textContent = '$0.00';
        khqrTotalEl.textContent = '$0.00';
        document.getElementById('paid_amount_hidden').value = '0.00';
        return;
    }

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 bg-gray-50 dark:bg-gray-900/60 p-2.5 rounded-xl border border-gray-100 dark:border-gray-700 group';
        row.innerHTML = `
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 dark:text-white text-xs truncate">${item.name}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">$${item.price.toFixed(2)} each</p>
            </div>
            <div class="flex items-center gap-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-1 py-0.5">
                <button type="button" class="qty-btn w-5 h-5 flex items-center justify-center rounded text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition font-bold text-sm" data-index="${index}" data-action="decrease">−</button>
                <span class="w-5 text-center text-xs font-bold text-gray-900 dark:text-white">${item.quantity}</span>
                <button type="button" class="qty-btn w-5 h-5 flex items-center justify-center rounded text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition font-bold text-sm" data-index="${index}" data-action="increase">+</button>
            </div>
            <span class="text-xs font-bold text-amber-600 w-12 text-right">$${itemTotal.toFixed(2)}</span>
        `;
        cartContainer.appendChild(row);
    });

    const tax   = subtotal * TAX_RATE;
    const total = subtotal + tax;
    subtotalEl.textContent  = `$${subtotal.toFixed(2)}`;
    taxEl.textContent       = `$${tax.toFixed(2)}`;
    totalEl.textContent     = `$${total.toFixed(2)}`;
    khqrTotalEl.textContent = `$${total.toFixed(2)}`;
    if (countBadge) countBadge.textContent = `(${cart.reduce((s,i)=>s+i.quantity,0)})`;
    document.getElementById('paid_amount_hidden').value = total.toFixed(2);
}

// Add to cart
document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.dataset.id;
        const existing = cart.find(i => i.id === id);
        if (existing) { existing.quantity += 1; }
        else { cart.push({ id, name: button.dataset.name, price: parseFloat(button.dataset.price), quantity: 1 }); }
        saveCart(); renderCart();
    });
});

// Qty / remove
cartContainer.addEventListener('click', event => {
    const btn = event.target.closest('.qty-btn');
    if (!btn) return;
    const index = Number(btn.dataset.index);
    if (btn.dataset.action === 'increase') { cart[index].quantity += 1; }
    else if (cart[index].quantity > 1) { cart[index].quantity -= 1; }
    else { cart.splice(index, 1); }
    saveCart(); renderCart();
});

// Clear cart
document.getElementById('clear-cart-btn')?.addEventListener('click', () => {
    if (confirm('Clear the entire cart?')) { cart = []; saveCart(); renderCart(); }
});

// Category tabs
document.querySelectorAll('.category-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.category-tab').forEach(t => {
            t.classList.remove('bg-amber-500','text-white','shadow-md','scale-105');
            t.classList.add('text-gray-600','bg-white','dark:bg-gray-800','border','border-gray-200','hover:bg-amber-50','hover:border-amber-300','hover:text-amber-600');
        });
        this.classList.add('bg-amber-500','text-white','shadow-md','scale-105');
        this.classList.remove('text-gray-600','bg-white','dark:bg-gray-800','border','border-gray-200','hover:bg-amber-50','hover:border-amber-300','hover:text-amber-600');
        const cat = this.dataset.category;
        document.querySelectorAll('.menu-item').forEach(item => {
            item.classList.toggle('hidden', !(cat === 'all' || item.dataset.category === cat));
        });
    });
});

// Search
document.getElementById('search-dishes')?.addEventListener('input', function() {
    const q = this.value.trim().toLowerCase();
    document.querySelectorAll('#menu-items .menu-item').forEach(item => {
        const title = item.querySelector('h3')?.textContent.toLowerCase() || '';
        item.classList.toggle('hidden', !title.includes(q));
    });
});

// Order type radio sync
document.querySelectorAll('input[name="order_type_radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('order_type').value = this.value;
    });
});

// Place order
document.getElementById('place-order-btn')?.addEventListener('click', () => {
    if (cart.length === 0) { alert('Please add at least one item to the cart.'); return; }

    const orderForm = document.getElementById('order-form');
    orderForm.querySelectorAll('input[name^="items"], input[name="customer_id"], input[name="order_type"]').forEach(el => el.remove());

    cart.forEach((item, index) => {
        const c = document.createElement('div');
        c.innerHTML = `
            <input type="hidden" name="items[${index}][menu_item_id]" value="${item.id}">
            <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            <input type="hidden" name="items[${index}][price]" value="${item.price}">`;
        orderForm.appendChild(c);
    });

    const custEl = document.createElement('input');
    custEl.type = 'hidden'; custEl.name = 'customer_id';
    custEl.value = document.getElementById('customer_id').value;
    orderForm.appendChild(custEl);

    const typeEl = document.createElement('input');
    typeEl.type = 'hidden'; typeEl.name = 'order_type';
    typeEl.value = document.getElementById('order_type').value;
    orderForm.appendChild(typeEl);

    orderForm.submit();

    // Clear cart immediately
    cart = [];
    saveCart();
    renderCart();

    // Optionally go back to the main POS screen after placing KHQR order
    setTimeout(() => {
        window.location.href = "{{ route('admin.orders.create') }}";
    }, 500);
});

renderCart();
</script>
@endpush
