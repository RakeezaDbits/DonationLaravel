@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.users.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Users
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-1 bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <div class="mx-auto h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center mb-4">
                    <i class="fas fa-user text-3xl text-gray-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">
                    {{ $user->is_anonymous ? 'Anonymous User' : $user->name }}
                </h3>
                <p class="text-gray-600">{{ $user->email }}</p>
                @if($user->phone)
                    <p class="text-gray-600">{{ $user->phone }}</p>
                @endif
            </div>
            
            <div class="mt-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">User ID</span>
                    <span class="text-sm font-medium">#{{ $user->id }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Donor Type</span>
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $user->donor_type === 'monthly' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $user->donor_type ? ucfirst(str_replace('_', ' ', $user->donor_type)) : 'N/A' }}
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Status</span>
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Anonymous</span>
                    <span class="text-sm">{{ $user->is_anonymous ? 'Yes' : 'No' }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Joined</span>
                    <span class="text-sm">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Donations</span>
                    <span class="text-sm font-medium text-green-600">
                        ${{ number_format($user->donations()->approved()->sum('amount'), 2) }}
                    </span>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-6 space-y-2">
                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                    @csrf
                    <button type="submit" 
                            class="w-full {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-md">
                        {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Donation History -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Donation History</h3>
                <p class="text-sm text-gray-600">Total: {{ $user->donations->count() }} donations</p>
            </div>
            
            @if($user->donations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($user->donations as $donation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${{ number_format($donation->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                        {{ str_replace('_', ' ', $donation->payment_method) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $donation->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($donation->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $donation->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.donations.show', $donation) }}" 
                                           class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center text-gray-500">
                    No donations found for this user.
                </div>
            @endif
        </div>
    </div>

    <!-- Monthly Pledge Information -->
    @if($user->donor_type === 'monthly' && $user->pledge)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Pledge Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Monthly Amount</label>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($user->pledge->monthly_amount, 2) }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Pledge Status</label>
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        {{ $user->pledge->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->pledge->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Next Reminder</label>
                    <p class="text-lg text-gray-900">
                        {{ $user->pledge->next_reminder_date ? $user->pledge->next_reminder_date->format('M d, Y') : 'Not set' }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection