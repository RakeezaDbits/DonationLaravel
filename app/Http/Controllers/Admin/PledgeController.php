<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pledge;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class PledgeController extends Controller
{
    public function index(Request $request)
    {
        $query = Pledge::with('user');

        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        $pledges = $query->latest()->paginate(20);

        return view('admin.pledges.index', compact('pledges'));
    }

    public function sendReminders(Request $request)
    {
        $monthlyUsers = User::monthlyDonors()->get();
        $count = 0;
        
        foreach ($monthlyUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Monthly Donation Reminder',
                'message' => 'This is a friendly reminder to make your monthly donation to HARF. Your continued support makes a difference!',
                'type' => 'monthly_reminder'
            ]);
            $count++;
        }

        return redirect()->route('admin.pledges.index')
            ->with('success', "Reminders sent to {$count} monthly donors");
    }

    public function toggleStatus(Pledge $pledge)
    {
        $pledge->update(['is_active' => !$pledge->is_active]);
        
        $status = $pledge->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.pledges.index')
            ->with('success', "Pledge {$status} successfully");
    }
}