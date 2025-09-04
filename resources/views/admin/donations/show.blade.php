@extends('layouts.admin')

@section('title', 'Donation Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.donations.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Donations
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Donation Details -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Donation Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Donation ID</label>
                    <p class="text-lg text-gray-900">#{{ $donation->id }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Amount</label>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($donation->amount, 2) }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Donor Name</label>
                    <p class="text-lg text-gray-900">
                        {{ $donation->is_anonymous ? 'Anonymous Donor' : $donation->donor_name }}
                    </p>
                </div>
                
                @unless($donation->is_anonymous)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="text-lg text-gray-900">{{ $donation->donor_email }}</p>
                    </div>
                    
                    @if($donation->donor_phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Phone</label>
                            <p class="text-lg text-gray-900">{{ $donation->donor_phone }}</p>
                        </div>
                    @endif
                @endunless
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Payment Method</label>
                    <p class="text-lg text-gray-900 capitalize">{{ str_replace('_', ' ', $donation->payment_method) }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Donor Type</label>
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        {{ $donation->donor_type === 'monthly' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($donation->donor_type) }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Status</label>
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        {{ $donation->status === 'approved' ? 'bg-green-100 text-green-800' : 
                           ($donation->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($donation->status) }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Submitted Date</label>
                    <p class="text-lg text-gray-900">{{ $donation->created_at->format('M d, Y g:i A') }}</p>
                </div>
                
                @if($donation->approved_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Approved Date</label>
                        <p class="text-lg text-gray-900">{{ $donation->approved_at->format('M d, Y g:i A') }}</p>
                    </div>
                    
                    @if($donation->approver)
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Approved By</label>
                            <p class="text-lg text-gray-900">{{ $donation->approver->name }}</p>
                        </div>
                    @endif
                @endif
                
                @if($donation->admin_notes)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600">Admin Notes</label>
                        <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $donation->admin_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Screenshot -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Screenshot</h3>
            
            @if($donation->payment_screenshot)
                <div class="text-center">
                    <img src="{{ Storage::url($donation->payment_screenshot) }}" 
                         alt="Payment Screenshot" 
                         class="max-w-full h-auto rounded-lg shadow-md cursor-pointer"
                         onclick="openImageModal(this.src)">
                    <a href="{{ Storage::url($donation->payment_screenshot) }}" 
                       target="_blank" 
                       class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-external-link-alt mr-1"></i>Open in new tab
                    </a>
                </div>
            @else
                <p class="text-gray-500 text-center">No payment screenshot uploaded</p>
            @endif
            
            <!-- Actions -->
            @if($donation->status === 'pending')
                <div class="mt-6 space-y-2">
                    <button onclick="showApprovalModal({{ $donation->id }}, 'approve')" 
                            class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>Approve Donation
                    </button>
                    <button onclick="showApprovalModal({{ $donation->id }}, 'reject')" 
                            class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        <i class="fas fa-times mr-2"></i>Reject Donation
                    </button>
                </div>
            @endif
        </div>
    </div>
    
    <!-- User's Donation History (if registered user) -->
    @if($donation->user)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Donor's Donation History</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($donation->user->donations()->latest()->limit(5)->get() as $userDonation)
                            <tr class="{{ $userDonation->id === $donation->id ? 'bg-blue-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($userDonation->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $userDonation->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($userDonation->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($userDonation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $userDonation->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50" onclick="closeImageModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <img id="modalImage" src="" alt="Payment Screenshot" class="max-w-full max-h-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-40">
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
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

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