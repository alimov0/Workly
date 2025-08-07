<?php

namespace App\Repositories\Admin;

use App\DTO\Admin\NotificationDTO;
use App\Interfaces\Repositories\Admin\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use InternalIterator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function markAsRead(NotificationDTO $dto):int
    {
        $user = Auth::user();

        if (!$user) {
            return 0;
        }

        if ($dto->all) {
            $count = $user->unreadNotifications()->count();
            $user->unreadNotifications->markAsRead();
            return $count;
        }

        if (!empty($dto->notification_ids)) {
            return $user->unreadNotifications()
                ->whereIn('id', $dto->notification_ids)
                ->update(['read_at' => now()]);
        }

        return 0;
    }
}

