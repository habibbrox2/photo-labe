<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Admin notifications list with read/unread filter and pagination
     */
    public function index(Request $request)
    {
        $query = auth()->user()->notifications();

        if ($request->input('filter') === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();
        $unreadCount = auth()->user()->unreadNotifications()->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a single notification as read and open its target
     */
    public function open(DatabaseNotification $notification)
    {
        if ($notification->notifiable_id !== auth()->id() || $notification->notifiable_type !== User::class) {
            abort(403);
        }

        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('admin.notifications.index'));
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}