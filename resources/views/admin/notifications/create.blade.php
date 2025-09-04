@extends('layouts.admin')

@section('title', 'Send Notification')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.notifications.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Notifications
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.notifications.store') }}" x-data="notificationForm()">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="lg:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div class="lg:col-span-2">
                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea id="message" name="message" rows="4" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('message') }}</textarea>
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select id="type" name="type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Type</option>
                        <option value="donation_approved" {{ old('type') === 'donation_approved' ? 'selected' : '' }}>Donation Approved</option>
                        <option value="donation_rejected" {{ old('type') === 'donation_rejected' ? 'selected' : '' }}>Donation Rejected</option>
                        <option value="monthly_reminder" {{ old('type') === 'monthly_reminder' ? 'selected' : '' }}>Monthly Reminder</option>
                        <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Recipients</label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="recipient_type" value="all" x-model="recipientType" class="form-radio">
                            <span class="ml-2">All Active Donors</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="recipient_type" value="monthly" x-model="recipientType" class="form-radio">
                            <span class="ml-2">Monthly Donors Only</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="recipient_type" value="custom" x-model="recipientType" class="form-radio">
                            <span class="ml-2">Select Specific Users</span>
                        </label>
                    </div>
                </div>
                
                <div x-show="recipientType === 'custom'" class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Select Users</label>
                    <div class="mt-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3 space-y-2">
                        @foreach($users as $user)
                            <label class="inline-flex items-center w-full">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-checkbox">
                                <span class="ml-2 text-sm">{{ $user->name }} ({{ $user->email }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.notifications.index') }}" 
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-paper-plane mr-2"></i>Send Notification
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function notificationForm() {
    return {
        recipientType: 'all',
        init() {
            // Auto-select user IDs based on recipient type
            this.$watch('recipientType', (value) => {
                const userCheckboxes = document.querySelectorAll('input[name="user_ids[]"]');
                
                if (value === 'all') {
                    userCheckboxes.forEach(cb => cb.checked = true);
                } else if (value === 'monthly') {
                    userCheckboxes.forEach(cb => {
                        const userDiv = cb.closest('label');
                        cb.checked = userDiv.textContent.includes('monthly') || userDiv.textContent.includes('Monthly');
                    });
                } else {
                    userCheckboxes.forEach(cb => cb.checked = false);
                }
            });
        }
    }
}
</script>
@endpush
@endsection