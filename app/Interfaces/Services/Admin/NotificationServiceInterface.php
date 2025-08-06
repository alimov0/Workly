<?php

namespace App\Interfaces\Services\Admin;

use App\DTO\Admin\NotificationDTO;
use Illuminate\Http\JsonResponse;

interface NotificationServiceInterface
{
    public function markAsRead(NotificationDTO $dto): JsonResponse;
}
