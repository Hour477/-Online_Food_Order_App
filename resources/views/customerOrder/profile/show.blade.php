@extends('customerOrder.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        {{-- Header / Cover --}}
        <div class="h-32 bg-gradient-to-r from-amber-500 to-amber-600"></div>
        
        <div class="px-8 pb-8">
            <div class="relative flex justify-between items-end -mt-16 mb-6">
                {{-- Profile Image --}}
                <div class="relative inline-block">
                    <img src="{{ $user->display_image }}" 
                         alt="{{ $user->name }}" 
                         class="w-32 h-32 rounded-2xl border-4 border-white shadow-md object-cover bg-gray-50">
                    <div class="absolute bottom-2 right-2 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pb-2">
                    <a href="{{ route('customerOrder.profile.edit') }}" 
                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-amber-600 text-white rounded-xl font-bold shadow-lg shadow-amber-200 hover:bg-amber-700 transition transform hover:-translate-y-0.5 active:scale-95">
                        <i class="fa-solid fa-user-pen"></i>
                        {{ __('app.edit_profile') }}
                    </a>
                </div>
            </div>

            {{-- User Info --}}
            <div class="mb-10">
                <h1 class="text-3xl font-black text-gray-900 mb-1 battambang-bold">{{ $user->name }}</h1>
                <p class="text-gray-500 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-600">
                        <i class="fa-solid fa-crown text-[10px]"></i>
                    </span>
                    {{ $user->role->name ?? 'Customer' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Personal Details --}}
                <div class="space-y-6">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-4 border-b border-gray-100 pb-2">
                        <i class="fa-solid fa-address-card text-amber-600"></i>
                        {{ __('app.personal_information') }}
                    </h2>
                    
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 border border-gray-100 group hover:bg-white hover:shadow-md transition duration-300">
                        <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-amber-600 flex-shrink-0 group-hover:bg-amber-600 group-hover:text-white transition">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ __('app.email') }}</p>
                            <p class="text-gray-900 font-semibold">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 border border-gray-100 group hover:bg-white hover:shadow-md transition duration-300">
                        <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-amber-600 flex-shrink-0 group-hover:bg-amber-600 group-hover:text-white transition">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ __('app.phone') }}</p>
                            <p class="text-gray-900 font-semibold">{{ $user->phone ?? __('app.not_provided') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Account Stats/Quick Links --}}
                <div class="space-y-6">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-4 border-b border-gray-100 pb-2">
                        <i class="fa-solid fa-clock-rotate-left text-amber-600"></i>
                        {{ __('app.account_summary') }}
                    </h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-100 text-center">
                            <p class="text-2xl font-black text-amber-600 mb-1">
                                {{ $user->likedMenuItems()->count() }}
                            </p>
                            <p class="text-[10px] uppercase tracking-wider text-amber-400 font-bold">{{ __('app.favorites') }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-100 text-center">
                            <p class="text-2xl font-black text-amber-600 mb-1">
                                {{ $user->orders()->count() }}
                            </p>
                            <p class="text-[10px] uppercase tracking-wider text-amber-400 font-bold">{{ __('app.total_orders') }}</p>
                        </div>
                    </div>

                    <a href="{{ route('customerOrder.orders.history') }}" 
                       class="flex items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-md transition group">
                        <span class="text-gray-700 font-bold">{{ __('app.view_order_history') }}</span>
                        <i class="fa-solid fa-arrow-right text-gray-400 group-hover:text-amber-600 transition translate-x-0 group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
