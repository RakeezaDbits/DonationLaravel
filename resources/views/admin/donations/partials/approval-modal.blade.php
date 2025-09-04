<!-- Approval Modal -->
<div x-data="{ open: false }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div @click.away="open = false" class="bg-white rounded-lg shadow-lg w-96 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Approve Donation</h2>
        <p class="text-sm text-gray-600 mb-4">Are you sure you want to approve this donation?</p>
        
        <form method="POST" action="#" id="approvalForm">
            @csrf
            <input type="hidden" name="donation_id" id="donation_id" value="">
            <div class="flex justify-end space-x-3">
                <button type="button" @click="open = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Approve</button>
            </div>
        </form>
    </div>
</div>
