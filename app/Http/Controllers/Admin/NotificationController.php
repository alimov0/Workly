<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MarkNotificationAsReadRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Exception;

class NotificationController extends Controller
{

    /**
     * Faqat tanlangan yoki barcha xabarlarni o'qilgan deb belgilash
     */
    public function markAsRead(MarkNotificationAsReadRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if ($request->input('all')) {
                $user->unreadNotifications->markAsRead();

                return response()->json([
                    'success' => true,
                    'message' => __('All notifications marked as read'),
                ]);
            }

            if ($request->has('notification_ids')) {
                $count = $user->unreadNotifications()
                    ->whereIn('id', $request->notification_ids)
                    ->update(['read_at' => now()]);

                return response()->json([
                    'success' => true,
                    'message' => __(':count notifications marked as read', ['count' => $count]),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('No notification IDs or "all" parameter provided'),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Something went wrong'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
