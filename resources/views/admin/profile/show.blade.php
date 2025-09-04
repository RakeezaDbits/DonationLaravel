@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center space-x-6 mb-6">
            <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center">
                <i class="fas fa-user text-3xl text-gray-600"></i>
            </div>
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">{{ auth()->user()->name }}</h2>
                <p class="text-gray-600">{{ auth()->user()->email }}</p>
                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="mt-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" id="password" name="password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Account Statistics -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Statistics</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">
                    {{ auth()->user()->created_at->diffInDays() }}
                </div>
                <div class="text-sm text-gray-600">Days as Admin</div>
            </div>
            
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">
                    {{ \App\Models\Donation::where('approved_by', auth()->id())->count() }}
                </div>
                <div class="text-sm text-gray-600">Donations Approved</div>
            </div>
            
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">
                    {{ auth()->user()->created_at->format('M d, Y') }}
                </div>
                <div class="text-sm text-gray-600">Joined Date</div>
            </div>
        </div>
    </div>
</div>
@endsection