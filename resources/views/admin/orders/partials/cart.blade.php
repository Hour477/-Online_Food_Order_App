<div class="sticky top-6 flex flex-col rounded-2xl overflow-hidden shadow-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800" style="max-height: calc(100vh - 5rem);">

    {{-- ── HEADER ── --}}
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-4 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-receipt text-white text-sm"></i>
            </div>
            <div>
                <h3 class="text-white font-bold text-base leading-tight">Order Summary</h3>
                <span id="cart-count-badge" class="text-amber-100 text-xs font-medium">0 items</span>
            </div>
        </div>
        <button id="clear-cart-btn"
                class="text-white/70 hover:text-white text-xs font-semibold flex items-center gap-1 px-2 py-1 rounded-md hover:bg-white/20 transition-all">
            <i class="fas fa-trash-alt text-[10px]"></i> Clear
        </button>
    </div>

    {{-- ── ORDER TYPE ── --}}
    <div class="px-4 pt-4 pb-2 flex-shrink-0">
        <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">
            Order Type <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-3 gap-1.5 bg-gray-100 dark:bg-gray-900 p-1 rounded-xl">
            @foreach ($order_types as $type)
            <label class="order-type-option cursor-pointer">
                <input type="radio" name="order_type_radio" value="{{ $type }}" class="sr-only peer" {{ $loop->first ? 'checked' : '' }}>
                <span class="block text-center text-xs font-semibold py-1.5 rounded-lg peer-checked:bg-white dark:peer-checked:bg-gray-700 peer-checked:text-amber-600 peer-checked:shadow-sm text-gray-500 dark:text-gray-400 transition-all select-none">
                    @if($type === 'dine_in') <i class="fas fa-utensils mr-0.5"></i>
                    @elseif($type === 'takeout') <i class="fas fa-shopping-bag mr-0.5"></i>
                    @else <i class="fas fa-motorcycle mr-0.5"></i>
                    @endif
                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                </span>
            </label>
            @endforeach
        </div>
        {{-- Hidden select kept for JS compatibility --}}
        <select id="order_type" name="order_type" class="sr-only">
            @foreach ($order_types as $type)
            <option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
            @endforeach
        </select>
    </div>

    {{-- ── CART ITEMS ── --}}
    <div id="cart-items-container" class="flex-1 overflow-y-auto px-4 py-2 space-y-2 min-h-[120px]">
        <div class="flex flex-col items-center justify-center py-8 text-center text-gray-400 dark:text-gray-600">
            <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-3">
                <i class="fas fa-shopping-cart text-2xl text-gray-300 dark:text-gray-600"></i>
            </div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Cart is empty</p>
            <p class="text-xs mt-0.5 text-gray-400 dark:text-gray-500">Add items from the menu</p>
        </div>
    </div>

    {{-- ── SUMMARY ── --}}
    <div class="flex-shrink-0 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-4 py-3 space-y-1.5">
        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
            <span>Subtotal</span>
            <span id="cart-subtotal" class="font-semibold text-gray-700 dark:text-gray-300">$0.00</span>
        </div>
        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
            <span>Tax (10%)</span>
            <span id="cart-tax" class="font-semibold text-gray-700 dark:text-gray-300">$0.00</span>
        </div>
        <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
            <span class="text-sm font-bold text-gray-900 dark:text-white">Total</span>
            <span id="cart-grand-total" class="text-xl font-black text-amber-600">$0.00</span>
        </div>
    </div>

    {{-- ── CUSTOMER ── --}}
    <div class="flex-shrink-0 px-4 pb-2 pt-3 border-t border-gray-100 dark:border-gray-700">
        <label for="customer_id" class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">
            <i class="fas fa-user text-amber-500 mr-1"></i> Customer (optional)
        </label>
        <select id="customer_id" name="customer_id"
                class="select2-customer block w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors"
                data-placeholder="Walk-in customer">
            <option value=""></option>
            @foreach($customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone ?? 'No phone' }})</option>
            @endforeach
        </select>
    </div>

    {{-- ── PAYMENT ── --}}
    <div class="flex-shrink-0 px-4 pb-3 space-y-2.5">
        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest pt-1">
            <i class="fas fa-credit-card text-amber-500 mr-1"></i> Payment
        </p>

        {{-- Method pills --}}
        <div class="grid grid-cols-3 gap-1.5">
            @foreach([['cash','Cash','fa-money-bill-wave'],['card','Card','fa-credit-card'],['khqr','KHQR','fa-qrcode']] as [$val,$label,$icon])
            <label class="cursor-pointer">
                <input type="radio" name="payment_method_radio" value="{{ $val }}" class="sr-only peer payment-method-radio">
                <span class="flex flex-col items-center gap-0.5 border border-gray-200 dark:border-gray-700 rounded-xl py-2 px-1 text-[10px] font-semibold text-gray-500 dark:text-gray-400 peer-checked:border-amber-500 peer-checked:bg-amber-50 dark:peer-checked:bg-amber-900/30 peer-checked:text-amber-600 transition-all hover:border-amber-300 select-none">
                    <i class="fas {{ $icon }} text-base"></i> {{ $label }}
                </span>
            </label>
            @endforeach
        </div>
        <select id="payment_method" name="payment_method" class="sr-only">
            <option value=""></option>
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="khqr">KHQR</option>
        </select>

        {{-- Paid / Change --}}
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label for="paid_amount" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Paid ($)</label>
                <input type="number" id="paid_amount" name="paid_amount" step="0.01" min="0"
                       class="block w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors"
                       placeholder="0.00">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Change ($)</label>
                <div class="flex items-center justify-center h-9 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 font-bold text-sm">
                    <span id="change_amount">$0.00</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ACTION BUTTONS ── --}}
    <div class="flex-shrink-0 px-4 pb-4 space-y-2">
        <button id="place-order-btn"
                class="w-full py-3 px-4 rounded-xl font-bold text-sm text-white shadow-lg transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                style="background: linear-gradient(135deg,#f59e0b 0%,#d97706 100%)"
                disabled>
            <i class="fas fa-check-circle mr-2"></i> Place Order
        </button>
        <button id="save-cart-btn"
                class="w-full py-2.5 px-4 rounded-xl font-semibold text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
            <i class="fas fa-save mr-2"></i> Save Cart
        </button>
    </div>
</div>

@push('scripts')
<script>
// Sync order type radio pills → hidden select
document.querySelectorAll('input[name="order_type_radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('order_type').value = this.value;
        document.getElementById('order_type').dispatchEvent(new Event('change'));
    });
});
// Sync payment method radio pills → hidden select
document.querySelectorAll('.payment-method-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('payment_method').value = this.value;
    });
});
</script>
@endpush
