{{-- resources/views/admin/settings/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <!-- Payment Methods Configuration -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Payment Methods</h3>
        
        <div class="space-y-6">
            @foreach($paymentMethods as $method)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-md font-medium text-gray-900">{{ $method->name }}</h4>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" {{ $method->is_active ? 'checked' : '' }} 
                                   onchange="togglePaymentMethod({{ $method->id }}, this.checked)"
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.settings.payment-methods.update', $method) }}">
                        @csrf
                        @method('PUT')
                        
                        @if($method->name === 'PayPal')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">PayPal Email</label>
                                    <input type="email" name="details[email]" 
                                           value="{{ $method->details['email'] ?? '' }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Client ID</label>
                                    <input type="text" name="details[client_id]" 
                                           value="{{ $method->details['client_id'] ?? '' }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                                    <input type="text" name="details[bank_name]" 
                                           value="{{ $method->details['bank_name'] ?? '' }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Account Number</label>
                                    <input type="text" name="details[account_number]" 
                                           value="{{ $method->details['account_number'] ?? '' }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Account Name</label>
                                    <input type="text" name="details[account_name]" 
                                           value="{{ $method->details['account_name'] ?? '' }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">SWIFT Code</label>
                                    <input type="text" name="details[swift_code]" 
                                           value="{{ $method->details['swift_code'] ?? '' }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        @endif
                        <div class="mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Changes</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection