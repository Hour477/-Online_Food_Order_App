@extends('admin.layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="mb-10 text-center sm:text-left">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
            Add New Table
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
            Create a new dining table for your restaurant
        </p>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-8">

            <form action="{{ route('admin.tables.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Table Number -->
                <div>
                    <label for="table_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Table Number <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-hashtag text-gray-400"></i>
                        </div>
                        
                        <input type="text" name="table_number" id="table_number" required
                               class="block w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="e.g. 5, A-01, VIP-1" value="{{ old('table_number') }}">
                    </div>
                    @error('table_number')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Capacity (seats) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-users text-gray-400"></i>
                        </div>
                        <input type="number" name="capacity" id="capacity" required min="1" max="50"
                               class="block w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="e.g. 4, 6, 10" value="{{ old('capacity') }}">
                    </div>
                    @error('capacity')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Initial Status <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-info-circle text-gray-400"></i>
                        </div>
                        <select name="status" id="status" required
                                class="block w-full pl-11 pr-10 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition appearance-none">
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                            class="flex-1 py-3.5 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-sm transition-all transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Save Table
                    </button>

                    <a href="{{ route('admin.tables.index') }}"
                       class="flex-1 py-3.5 px-6 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-xl border border-gray-300 dark:border-gray-600 text-center transition">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection