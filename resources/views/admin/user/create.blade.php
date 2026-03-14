@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Create New Staff</h2>
    
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
            <select name="role" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                <option value="waiter">Waiter</option>
                <option value="chef">Chef</option>
                <option value="manager">Manager</option>
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Save User
            </button>
        </div>
    </form>
</div>
@endsection