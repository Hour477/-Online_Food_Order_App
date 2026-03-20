@extends('admin.layouts.app')

@section('content')
<div class="mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Reports</h1>
        <p class="mt-1 text-gray-600">View orders and income reports</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('admin.reports.orders') }}"
           class="group block p-6 bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-amber-300 transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-amber-100 text-amber-600 group-hover:bg-amber-200 transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 group-hover:text-amber-700 transition-colors">Orders Report</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Filter by date and status, export order list</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.income') }}"
           class="group block p-6 bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-amber-300 transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200 transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 group-hover:text-emerald-700 transition-colors">Income Report</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Daily revenue totals and payment summary</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
