<?php
// app/Http/Controllers/Admin/DonationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Mail\DonationApprovedMail;
use App\Mail\DonationRejectedMail;
use Illuminate\Support\Facades\Mail;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with(['user', 'approver']);

        // Apply filters
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        if ($request->has('donor_type') && $request->donor_type !== '') {
            $query->where('donor_type', $request->donor_type);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('donor_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('donor_email', 'LIKE', '%' . $request->search . '%');
            });
        }

        $donations = $query->latest()->paginate(20);

        return view('admin.donations.index', compact('donations'));
    }

    public function show(Donation $donation)
    {
        $donation->load(['user', 'approver']);
        return view('admin.donations.show', compact('donation'));
    }

    // public function approve(Request $request, Donation $donation)
    // {
    //     $donation->update([
    //         'status' => 'approved',
    //         'approved_at' => now(),
    //         'approved_by' => auth()->id(),
    //         'admin_notes' => $request->admin_notes
    //     ]);

    //     // Send notification to user if they exist
    //     if ($donation->user_id) {
    //         Notification::create([
    //             'user_id' => $donation->user_id,
    //             'title' => 'Donation Approved',
    //             'message' => "Your donation of $" . number_format($donation->amount, 2) . " has been approved. Thank you for your generosity!",
    //             'type' => 'donation_approved',
    //             'data' => ['donation_id' => $donation->id]
    //         ]);
    //     }

    //     return redirect()->route('admin.donations.index')
    //         ->with('success', 'Donation approved successfully');
    // }

    // public function reject(Request $request, Donation $donation)
    // {
    //     $request->validate([
    //         'admin_notes' => 'required|string|min:10'
    //     ]);

    //     $donation->update([
    //         'status' => 'rejected',
    //         'approved_by' => auth()->id(),
    //         'admin_notes' => $request->admin_notes
    //     ]);

    //     // Send notification to user if they exist
    //     if ($donation->user_id) {
    //         Notification::create([
    //             'user_id' => $donation->user_id,
    //             'title' => 'Donation Rejected',
    //             'message' => "Your donation of $" . number_format($donation->amount, 2) . " has been rejected. Please contact support for more information.",
    //             'type' => 'donation_rejected',
    //             'data' => ['donation_id' => $donation->id, 'reason' => $request->admin_notes]
    //         ]);
    //     }

    //     return redirect()->route('admin.donations.index')
    //         ->with('success', 'Donation rejected successfully');
    // }
    public function approve(Request $request, Donation $donation)
    {
        $donation->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'admin_notes' => $request->admin_notes
        ]);

        if ($donation->user_id && $donation->user->email) {
            Mail::to($donation->user->email)->send(
                new DonationApprovedMail($donation->user->name, $donation->amount)
            );
        }

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation approved successfully');
    }

    public function reject(Request $request, Donation $donation)
    {
        $request->validate([
            'admin_notes' => 'required|string|min:10'
        ]);

        $donation->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'admin_notes' => $request->admin_notes
        ]);

        if ($donation->user_id && $donation->user->email) {
            Mail::to($donation->user->email)->send(
                new DonationRejectedMail($donation->user->name, $donation->amount)
            );
        }

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation rejected successfully');
    }
}
