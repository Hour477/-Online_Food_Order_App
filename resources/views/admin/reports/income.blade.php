@extends('admin.layouts.app')

@section('content')
<div class="mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Income Report</h1>
            <p class="mt-1 text-gray-600">Daily payment totals</p>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="text-sm font-medium text-amber-600 hover:text-amber-700">
            ← Back to Reports
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6">
        <form action="{{ route('admin.reports.income') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">From date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">To date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700">Apply</button>
                <a href="{{ route('admin.reports.income') }}" class="ml-2 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">Reset</a>
            </div>
        </form>
    </div>

    {{-- Grand total --}}
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
        <span class="text-sm text-emerald-800">Total revenue (filtered):</span>
        <span class="text-2xl font-bold text-emerald-700">${{ number_format($grandTotal ?? 0, 2) }}</span>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Transactions</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($income as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 text-right">{{ $row->count }}</td>
                        <td class="px-4 py-3 text-sm text-right font-medium text-emerald-600">${{ number_format($row->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">No income data for the selected period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
