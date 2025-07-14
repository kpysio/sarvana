<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->take(20)->get();
        $unreadCount = $user->unreadNotifications()->count();
        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function show(DatabaseNotification $notification)
    {
        if ($notification->notifiable_id === Auth::id()) {
            if (is_null($notification->read_at)) {
                $notification->markAsRead();
            }
            return view('admin.notifications.show', compact('notification'));
        }
        abort(403);
    }

    public function markAllRead(Request $request)
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return back()->with('status', 'All notifications marked as read.');
    }
} 