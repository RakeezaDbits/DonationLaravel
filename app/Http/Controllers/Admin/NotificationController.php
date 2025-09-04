<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with('user');

        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('message', 'LIKE', '%' . $request->search . '%')
                  ->orWhereHas('user', function($subQ) use ($request) {
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
            Notification::create([
                'user_id' => $userId,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type
            ]);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifications sent successfully');
    }
}