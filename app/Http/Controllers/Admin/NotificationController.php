<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MarkNotificationAsReadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

    // Faqat tanlangan xabarlarni yoki hammasini o‘qilgan deb belgilash
    public function markAsRead(MarkNotificationAsReadRequest $request)
    {
        $user = Auth::user();

        if ($request->input('all')) {
            $user->unreadNotifications->markAsRead();
        } elseif ($request->has('notification_ids')) {
            $user->unreadNotifications()
                ->whereIn('id', $request->notification_ids)
                ->update(['read_at' => now()]);
        }

        return response()->json(['message' => 'Notifications marked as read']);
    }

    // Barcha xabarlarni o‘qilgan deb belgilash
    public function markAllAsRead(Request $request)
    {
        $admin = Auth::user();
        $admin->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read']);
    }
}
