<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donation;
use App\Models\Pledge;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_donors' => User::where('role', 'donor')->count(),
            'total_donations' => Donation::sum('amount'),
            'total_anonymous_donations' => Donation::where('is_anonymous', true)->sum('amount'),
            'total_pledges' => Pledge::active()->sum('monthly_amount'),
            'pending_donations' => Donation::pending()->count(),
            'approved_donations' => Donation::approved()->count(),
            'monthly_donors' => User::monthlyDonors()->count(),
            'guest_donations' => Donation::where('donor_type', 'guest')->count()
        ];

        // Recent donations for chart
        $recentDonations = Donation::approved()
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly vs Guest donations
        $donationTypes = Donation::selectRaw('donor_type, SUM(amount) as total')
            ->groupBy('donor_type')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentDonations', 'donationTypes'));
    }
}