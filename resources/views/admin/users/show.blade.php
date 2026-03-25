
@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="mx-auto">
    {{-- User Details Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">User Details</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Viewing profile information for {{ $user->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700 transition-colors shadow-sm">
                <i class="fas fa-edit mr-2"></i>
                Edit User
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-center">
                    <div class="relative inline-block mb-4">
                        <img src="{{ $user->display_image }}" 
                             alt="{{ $user->name }}" 
                             class="w-32 h-32 rounded-2xl border-4 border-amber-50 dark:border-amber-900/20 shadow-sm object-cover">
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 uppercase tracking-wider mt-2">
                        {{ $user->role->name ?? 'No Role' }}
                    </span>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">Member since {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Details Card --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Account Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <i class="fas fa-id-card text-amber-600"></i>
                    <h5 class="font-semibold text-gray-900 dark:text-white">Account Information</h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Full Name</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $user->name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Email Address</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $user->email }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Role Type</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $user->role->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Account Created</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $user->created_at->format('M d, Y @ H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity Overview (Placeholder or future extension) --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <i class="fas fa-history text-amber-600"></i>
                    <h5 class="font-semibold text-gray-900 dark:text-white">System Activity</h5>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-lg bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Last Profile Update</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Your profile was last updated on {{ $user->updated_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

