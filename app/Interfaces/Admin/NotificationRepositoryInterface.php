<?php

namespace App\Interfaces\Repositories\Admin;

use App\DTO\Admin\NotificationDTO;
use Illuminate\Http\JsonResponse;

interface NotificationRepositoryInterface
{
    public function markAsRead(NotificationDTO $dto): int;
}
