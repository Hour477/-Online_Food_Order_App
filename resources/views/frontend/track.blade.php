@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950 px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Delivery Tracking</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Order {{ $order->order_no }} | {{ ucfirst($order->status) }}</p>
            </div>
            <a href="{{ route('frontend.dashboard') }}" class="rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm">Back</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-3">
                <div id="delivery-map" class="h-[480px] rounded-xl"></div>
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-4">
                <h2 class="font-semibold text-gray-900 dark:text-white">Delivery Details</h2>
                <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <li><strong>Customer:</strong> {{ $customer->name }}</li>
                    <li><strong>Order No:</strong> {{ $order->order_no }}</li>
                    <li><strong>Total:</strong> ${{ number_format((float) $order->total_amount, 2) }}</li>
                    <li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
                </ul>
                <p class="mt-4 text-xs text-gray-500">
                    Demo tracking map is enabled. You can later connect this to live rider GPS coordinates.
                </p>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const restaurant = [11.5564, 104.9282];
    const customer = [11.5650, 104.9200];
    let rider = [11.5600, 104.9250];

    const map = L.map('delivery-map').setView(restaurant, 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker(restaurant).addTo(map).bindPopup('Restaurant');
    L.marker(customer).addTo(map).bindPopup('Customer Location');
    const riderMarker = L.marker(rider).addTo(map).bindPopup('Delivery Rider');
    L.polyline([restaurant, customer], { color: '#4f46e5', weight: 4 }).addTo(map);

    setInterval(() => {
        rider = [rider[0] + (Math.random() - 0.5) * 0.0008, rider[1] + (Math.random() - 0.5) * 0.0008];
        riderMarker.setLatLng(rider);
    }, 4000);
});
</script>
@endsection

