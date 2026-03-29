@extends('admin.layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="mx-auto">
    {{-- Soft Delete Indicator --}}
    

    {{-- Customer Details Header --}}
    <div class="mb-6 flex bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Customer Details</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View comprehensive information about {{ $customer->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
            <a href="{{ route('admin.customers.edit', $customer->id) }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700 transition-colors shadow-sm">
                <i class="fas fa-edit mr-2"></i>
                Edit Customer
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-center">
                    <div class="relative inline-block mb-4">
                        <img src="{{ $customer->user?->display_image ?? asset('assets/img/placeholder.png') }}" 
                             alt="{{ $customer->user?->name ?? 'N/A' }}" 
                             class="w-32 h-32 rounded-2xl border-4 border-amber-50 dark:border-amber-900/20 shadow-sm object-cover">
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $customer->name }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Customer since {{ $customer->created_at->format('M d, Y') }}</p>
                    
                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-center gap-4">
                            <div class="text-center">
                                <span class="block text-xl font-bold text-gray-900 dark:text-white">{{ $customer->orders->count() }}</span>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Orders</span>
                            </div>
                            <div class="w-px h-8 bg-gray-200 dark:bg-gray-700"></div>
                            <div class="text-center">
                                <span class="block text-xl font-bold text-gray-900 dark:text-white">${{ number_format($customer->orders->sum('total_amount'), 2) }}</span>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Spent</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($customer->trashed() || ($customer->user && $customer->user->trashed()))
            <div class="m-6 bg-red-50 border-l-4 border-red-500 p-4  rounded-lg shadow-sm">
                <div class="flex items-center ">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-bold text-red-800">
                            @if($customer->trashed())
                                Customer Account Deleted
                            @else
                                Linked User Account Deleted
                            @endif
                        </h3>
                        <div class="mt-1 text-sm text-red-700">
                            <p>
                                @if($customer->trashed())
                                    This customer profile was deleted on <strong>{{ $customer->deleted_at->format('M d, Y @ H:i') }}</strong>.
                                @else
                                    The linked user account was deleted on <strong>{{ $customer->user->deleted_at->format('M d, Y @ H:i') }}</strong>.
                                @endif
                                Administrators should review this record for historical purposes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
            </div>
            
            
        </div>

        {{-- Information Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Personal Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <i class="fas fa-user text-amber-600"></i>
                    <h5 class="font-semibold text-gray-900 dark:text-white">Personal Information</h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Full Name</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $customer->name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Email Address</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $customer->user->email ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">User </span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $customer->user->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Phone Number</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $customer->phone ?: 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Location</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">
                                {{ implode(', ', array_filter([$customer->address, $customer->city])) ?: 'No address provided' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-amber-600"></i>
                    <h5 class="font-semibold text-gray-900 dark:text-white">Account Security</h5>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-lg bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600">
                            <i class="fas fa-user-lock text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Linked System User</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                @if($customer->user)
                                    Account linked to user ID #{{ $customer->user->id }} ({{ $customer->user->email }})
                                @else
                                    No linked system user found
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection