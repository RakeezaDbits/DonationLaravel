@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content') 
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Donors Card -->
        <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-xl p-3 bg-blue-50">
                    <i class="fas fa-users text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Donors</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_donors'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Donations Card -->
        <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-xl p-3 bg-green-50">
                    <i class="fas fa-dollar-sign text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Donations</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['total_donations'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Anonymous Donations Card -->
        <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-xl p-3 bg-purple-50">
                    <i class="fas fa-eye-slash text-2xl text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Anonymous Donations</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['total_anonymous_donations'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Monthly Pledges Card -->
        <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-xl p-3 bg-orange-50">
                    <i class="fas fa-handshake text-2xl text-orange-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Monthly Pledges</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['total_pledges'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Donations Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Donations (Last 30 Days)</h3>
            <div class="h-80">
                <canvas id="donationsChart"></canvas>
            </div>
        </div>

        <!-- Donation Types -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Donation Breakdown</h3>
    @php
        $guestDonations = $stats['guest_donations'] ?? 0;
        $registeredDonations = ($stats['total_donations'] ?? 0) - $guestDonations;
        $guestPercentage = ($stats['total_donations'] ?? 0) ? ($guestDonations / $stats['total_donations']) * 100 : 0;
        $registeredPercentage = 100 - $guestPercentage;
    @endphp

    <div class="space-y-5">
        <div>
            <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium text-gray-700">Registered Donors</span>
                <span class="text-sm font-semibold text-gray-900">${{ number_format($registeredDonations, 2) }}</span>
            </div>
            <div class="progress-bar bg-gray-200">
                <div class="progress-fill bg-blue-500" style="width: {{ round($registeredPercentage) }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ round($registeredPercentage) }}% of total donations</p>
        </div>

        <div>
            <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium text-gray-700">Guest Donations</span>
                <span class="text-sm font-semibold text-gray-900">${{ number_format($guestDonations, 2) }}</span>
            </div>
            <div class="progress-bar bg-gray-200">
                <div class="progress-fill bg-green-500" style="width: {{ round($guestPercentage) }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ round($guestPercentage) }}% of total donations</p>
        </div>
    </div>
</div>


    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">Pending Donations</h4>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-500"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-yellow-600 mb-2">{{ $stats['pending_donations'] }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">Approved Donations</h4>
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-green-600 mb-2">{{ $stats['approved_donations'] }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">Monthly Donors</h4>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-calendar-check text-blue-500"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['monthly_donors'] }}</p>
        </div>
    </div>

</main>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- <script>
const ctx = document.getElementById('donationsChart').getContext('2d');
const donationsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($recentDonations->pluck('date')) !!},
        datasets: [{
            label: 'Donations',
            data: {!! json_encode($recentDonations->pluck('total')) !!},
            borderColor: 'rgba(59, 130, 246, 1)',
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        }
    }
});
</script> --}}
@endsection
