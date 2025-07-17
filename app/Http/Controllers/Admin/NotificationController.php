<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MarkNotificationAsReadRequest;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

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
}