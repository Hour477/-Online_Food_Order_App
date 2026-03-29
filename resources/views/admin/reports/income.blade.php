@extends('admin.layouts.app')
@section('title', 'Income Reports')



@section('content')
<div class="mx-auto">
    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Income Reports</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Financial breakdown by payment method and daily totals</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.export.pdf', ['type' => 'income', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('admin.reports.export.csv', ['type' => 'income', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium flex items-center gap-2">
                <i class="fas fa-file-excel"></i> CSV
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Payment Methods --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Payment Methods</h3>
                <div class="space-y-4">
                    @php $totalIncome = $paymentMethods->sum('total') ?: 1; @endphp
                    @foreach($paymentMethods as $method)
                        @php $percent = ($method->total / $totalIncome) * 100; @endphp
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-white dark:bg-gray-800 flex items-center justify-center text-amber-600 shadow-sm border border-gray-100 dark:border-gray-700">
                                    <i class="fas fa-{{ $method->payment_method === 'cash' ? 'money-bill-wave' : ($method->payment_method === 'qr' ? 'qrcode' : 'credit-card') }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white uppercase">{{ $method->payment_method }}</p>
                                    <p class="text-xs text-gray-500">{{ $method->count }} transactions</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-emerald-600">${{ number_format($method->total, 2) }}</p>
                                <p class="text-[10px] font-medium text-gray-400">{{ number_format($percent, 1) }}%</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Daily Totals --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daily Income Log</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50">
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-right">Total Income</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($dailyIncome as $day)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ date('d M, Y', strtotime($day->date)) }}
                                        <span class="text-xs text-gray-400 font-normal ml-2">({{ date('l', strtotime($day->date)) }})</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-emerald-600 text-right">${{ number_format($day->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-12 text-center text-gray-400">No income records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
