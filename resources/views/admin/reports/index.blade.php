@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="space-y-6">
    <!-- Report Generation Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Generate Reports</h3>
        
        <form method="POST" action="{{ route('admin.reports.generate') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                    <input type="date" id="date_from" name="date_from" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                    <input type="date" id="date_to" name="date_to" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-700">Report Type</label>
                    <select id="report_type" name="report_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Type</option>
                        <option value="donations">Donations Report</option>
                        <option value="donors">Donors Report</option>
                        <option value="pledges">Pledges Report</option>
                    </select>
                </div>
                
                <div>
                    <label for="format" class="block text-sm font-medium text-gray-700">Format</label>
                    <select id="format" name="format" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Format</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">CSV</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
            </div>
            
            <!-- Additional Filters -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status (Donations)</label>
                    <select id="status" name="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div>
                    <label for="donor_type" class="block text-sm font-medium text-gray-700">Donor Type</label>
                    <select id="donor_type" name="donor_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="monthly">Monthly</option>
                        <option value="guest">Guest</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-download mr-2"></i>Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chart-line text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        ${{ number_format(\App\Models\Donation::whereMonth('created_at', now()->month)->approved()->sum('amount'), 2) }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This Year</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        ${{ number_format(\App\Models\Donation::whereYear('created_at', now()->year)->approved()->sum('amount'), 2) }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-2xl text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">New Donors (Month)</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\User::whereMonth('created_at', now()->month)->where('role', 'donor')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-2xl text-orange-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Reviews</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\Donation::pending()->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @php
                $recentDonations = \App\Models\Donation::with('user')->latest()->limit(10)->get();
            @endphp
            
            @foreach($recentDonations as $donation)
                <div class="p-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full {{ $donation->status === 'approved' ? 'bg-green-100' : ($donation->status === 'rejected' ? 'bg-red-100' : 'bg-yellow-100') }} flex items-center justify-center">
                                <i class="fas {{ $donation->status === 'approved' ? 'fa-check text-green-600' : ($donation->status === 'rejected' ? 'fa-times text-red-600' : 'fa-clock text-yellow-600') }}"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $donation->is_anonymous ? 'Anonymous' : $donation->donor_name }} donated ${{ number_format($donation->amount, 2) }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $donation->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs rounded-full {{ $donation->status === 'approved' ? 'bg-green-100 text-green-800' : ($donation->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($donation->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection