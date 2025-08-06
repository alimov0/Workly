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
        return $this->notificationRepository->markAsRead($dto);
    }
}
