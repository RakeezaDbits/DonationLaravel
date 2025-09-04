@extends('layouts.admin')

@section('title', 'Donations Management')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Donor Type</label>
                <select name="donor_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="monthly" {{ request('donor_type') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="guest" {{ request('donor_type') === 'guest' ? 'selected' : '' }}>Guest</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Name or Email"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div class="md:col-span-5 flex justify-end space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.donations.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Donations Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($donations as $donation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $donation->is_anonymous ? 'Anonymous' : $donation->donor_name }}
                                        </div>
                                        @unless($donation->is_anonymous)
                                            <div class="text-sm text-gray-500">{{ $donation->donor_email }}</div>
                                        @endunless
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($donation->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $donation->payment_method) }}</div>
                                @if($donation->payment_screenshot)
                                    <a href="{{ Storage::url($donation->payment_screenshot) }}" target="_blank" 
                                       class="text-xs text-blue-600 hover:text-blue-800">View Screenshot</a>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $donation->donor_type === 'monthly' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($donation->donor_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $donation->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($donation->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $donation->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.donations.show', $donation) }}" 
                                   class="text-blue-600 hover:text-blue-900">View</a>
                                   
                                @if($donation->status === 'pending')
                                    <button onclick="showApprovalModal({{ $donation->id }}, 'approve')" 
                                            class="text-green-600 hover:text-green-900">Approve</button>
                                    <button onclick="showApprovalModal({{ $donation->id }}, 'reject')" 
                                            class="text-red-600 hover:text-red-900">Reject</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No donations found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($donations->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $donations->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" x-data="{ show: false }" x-show="show">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 mb-4"></h3>
            <form id="approvalForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="admin_notes" name="admin_notes" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Add your notes here..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideApprovalModal()" 
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Cancel</button>
                    <button type="submit" id="modalSubmitBtn" 
                            class="px-4 py-2 rounded-md text-white">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showApprovalModal(donationId, action) {
    const modal = document.getElementById('approvalModal');
    const form = document.getElementById('approvalForm');
    const title = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('modalSubmitBtn');
    const notesField = document.getElementById('admin_notes');
    
    if (action === 'approve') {
        title.textContent = 'Approve Donation';
        submitBtn.textContent = 'Approve';
        submitBtn.className = 'px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700';
        form.action = `/admin/donations/${donationId}/approve`;
        notesField.required = false;
    } else {
        title.textContent = 'Reject Donation';
        submitBtn.textContent = 'Reject';
        submitBtn.className = 'px-4 py-2 rounded-md text-white bg-red-600 hover:bg-red-700';
        form.action = `/admin/donations/${donationId}/reject`;
        notesField.required = true;
    }
    
    modal.classList.remove('hidden');
}

function hideApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
    document.getElementById('admin_notes').value = '';
}
</script>
@endpush
@endsection