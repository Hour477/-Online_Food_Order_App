@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="mb-10 text-center sm:text-left">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
            Edit Table #{{ $table->table_number }}
        </h1>

        <p class="mt-2 text-gray-600 dark:text-gray-400">
            Update table information and status
        </p>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-8">

            <form action="{{ route('tables.update', $table->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Table Number -->
                 @if(auth()->user()->role === 'admin')
                <div>

                   
                  
                    <label for="table_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Table Number <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-hashtag text-gray-400"></i>
                        </div>

                        <input type="text" name="table_number" id="table_number" required
                               value="{{ old('table_number', $table->table_number) }}"
                               class="block w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="e.g. 5, A-01, VIP-1">
                    </div>
                    @error('table_number')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                  @endif

                <!-- Capacity -->
                 @if(auth()->user()->role === 'admin')
                        
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Capacity (seats) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-users text-gray-400"></i>
                        </div>
                        <input type="number" name="capacity" id="capacity" required min="1" max="50"
                               value="{{ old('capacity', $table->capacity) }}"
                               class="block w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="e.g. 4, 6, 10">
                    </div>
                    @error('capacity')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Status -->
                @if(auth()->user()->role == 'waiter' || auth()->user()->role == 'admin')
                       
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Current Status <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-info-circle text-gray-400"></i>
                        </div>
                        <select name="status" id="status" required
                                class="block w-full pl-11 pr-10 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition appearance-none">
                            <option value="available" {{ old('status', $table->status) == 'available' ? 'selected' : '' }}>
                                Available
                            </option>
                            <option value="reserved" {{ old('status', $table->status) == 'reserved' ? 'selected' : '' }}>
                                Reserved
                            </option>
                            <option value="occupied" {{ old('status', $table->status) == 'occupied' ? 'selected' : '' }}>
                                Occupied
                            </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                @endif
                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                            class="flex-1 py-3.5 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-sm transition-all transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Save Table
                    </button>

                    <a href="{{ route('tables.index') }}"
                       class="flex-1 py-3.5 px-6 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-xl border border-gray-300 dark:border-gray-600 text-center transition">
                        Cancel
                    </a>
                    
                    

                </div>
            </form>
            <!-- Delete Button -->
            
            

        </div>
    </div>

    <!-- Optional: Quick Info / Preview -->
    <div class="mt-10 bg-gray-50 dark:bg-gray-900/30 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Current Table Info  
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
            <div>
                <span class="text-gray-500 dark:text-gray-400">Table Number:</span>
                <p class="font-medium text-gray-900 dark:text-white mt-1">Table {{ $table->table_number }}</p>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Capacity:</span>
                <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $table->capacity }} seats</p>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Current Status:</span>
                <p class="mt-1">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $table->status === 'available' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : '' }}
                        {{ $table->status === 'occupied' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' : '' }}
                        {{ $table->status === 'reserved' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}">
                        {{ ucfirst($table->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

</div>

@endsection