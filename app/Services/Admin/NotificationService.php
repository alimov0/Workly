<?php

namespace App\Services\Admin;

use App\DTO\Admin\NotificationDTO;
use App\Interfaces\Repositories\Admin\NotificationRepositoryInterface;
use App\Interfaces\Services\Admin\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;

class NotificationService implements NotificationServiceInterface
{
    protected NotificationRepositoryInterface $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function markAsRead(NotificationDTO $dto): JsonResponse
    {
        $result = $this->notificationRepository->markAsRead($dto);

        if ($dto->all) {
            return response()->json([
                'status' => 'success',
                'message' => __('All notifications marked as read'),
            ]);
        }

        if (!empty($dto->notification_ids)) {
            return response()->json([
                'status' => 'success',
                'message' => __(':count notifications marked as read', ['count' => $result]),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('No notifications were selected to mark as read.'),
        ], 400);
    }
}
