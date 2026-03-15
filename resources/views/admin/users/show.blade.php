
@extends('admin.layouts.app')

@section('content')
<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">User Details</h3>
            <p class="text-sm text-gray-500 mt-1">Viewing profile information for {{ $user->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}"
               class="text-sm text-gray-500 hover:text-gray-900 flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Users
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" 
               class="ml-4 px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                Edit User
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Profile Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 text-center">
                <div class="relative w-32 h-32 mx-auto mb-4">
                    @if($user->image)
                        <img src="{{ asset('storage/users/' . $user->image) }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover border-4 border-amber-600">
                    @else
                        <div class="w-full h-full rounded-full border-4 border-amber-600 bg-amber-100 flex items-center justify-center text-2xl font-bold text-amber-700">
                            {{ strtoupper(mb_substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <h4 class="text-xl font-bold text-gray-900">{{ $user->name }}</h4>
                <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 uppercase tracking-wider">
                    {{ $user->role->name ?? 'No Role' }}
                </span>
            </div>
        </div>

        {{-- Details Card --}}
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 lg:p-8">
                <h5 class="text-lg font-semibold text-gray-900 mb-6 border-b pb-4">Account Information</h5>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Full Name</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $user->name }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Email Address</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $user->email }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Role</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $user->role->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Created At</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection