<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotificationMail;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with('user');

        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('message', 'LIKE', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($subQ) use ($request) {
                        $subQ->where('name', 'LIKE', '%' . $request->search . '%');
                    });
            });
        }

        $notifications = $query->latest()->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::where('role', 'donor')->where('is_active', true)->get();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'type' => 'required|in:donation_approved,donation_rejected,monthly_reminder,general',
        'user_ids' => 'required|array',
        'user_ids.*' => 'exists:users,id'
    ]);

    foreach ($request->user_ids as $userId) {
        $user = User::find($userId);

        // 1️⃣ Save notification in DB
        Notification::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type
        ]);

        // 2️⃣ Determine which Blade template to use
        switch ($request->type) {
            case 'donation_approved':
                $view = 'emails.donation_approved';
                $data = [
                    'subject' => $request->title,
                    'name' => $user->name,
                    'amount' => $request->message
                ];
                break;

            case 'donation_rejected':
                $view = 'emails.donation_rejected';
                $data = [
                    'subject' => $request->title,
                    'name' => $user->name,
                    'amount' => $request->message
                ];
                break;

            case 'monthly_reminder':
                $view = 'emails.monthly_reminder';
                $data = [
                    'subject' => $request->title,
                    'name' => $user->name,
                    'monthly_amount' => '$50',
                    'due_date' => '2025-09-15',
                    'last_donation_date' => '2025-08-15',
                    'total_donated' => '$500',
                    'donation_count' => 10,
                    'impact_stats' => '50 lives impacted'
                ];
                break;

            default:
                $view = null;
                $data = [];
        }

        // 3️⃣ Send email if template exists
        if ($view) {
            $body = view($view, $data)->render();
            Mail::to($user->email)->send(new UserNotificationMail($request->title, $body));
        }
    }

    return redirect()->route('admin.notifications.index')
        ->with('success', 'Notifications stored & emails sent successfully!');
}
}
