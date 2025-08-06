<?php

namespace App\Repositories\Admin;

use App\DTO\Admin\NotificationDTO;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Repositories\Admin\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function markAsRead(NotificationDTO $dto): JsonResponse
    {
        $user = Auth::user();

        if ($dto->all) {
            $user->unreadNotifications->markAsRead();

            return response()->json([
                'status' => 'success',
                'message' => __('All notifications marked as read'),
            ]);
        }

        if (!empty($dto->notification_ids)) {
            $count = $user->unreadNotifications()
                ->whereIn('id', $dto->notification_ids)
                ->update(['read_at' => now()]);

            return response()->json([
                'status' => 'success',
                'message' => __(':count notifications marked as read', ['count' => $count]),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => __('No notification IDs or "all" parameter provided'),
        ], 422);
    }
}
