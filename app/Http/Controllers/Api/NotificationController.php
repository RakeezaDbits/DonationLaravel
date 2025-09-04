<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Notification::where('user_id', $user->id);
        
        // Filter by read status
        if ($request->has('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->latest()
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $user = auth()->user();
        
        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = auth()->user();
        
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    public function sendReminder(Request $request)
    {
        $monthlyUsers = User::monthlyDonors()->get();
        
        foreach ($monthlyUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Monthly Donation Reminder',
                'message' => 'This is a friendly reminder to make your monthly donation to HARF.',
                'type' => 'monthly_reminder'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reminders sent to all monthly donors',
            'count' => $monthlyUsers->count()
        ]);
    }
}