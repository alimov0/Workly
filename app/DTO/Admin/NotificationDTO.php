<?php

namespace App\DTO\Admin;

class NotificationDTO
{
    public ?bool $all;
    public ?array $notification_ids;

    public function __construct(array $data)
    {
        $this->all = $data['all'] ?? null;
        $this->notification_ids = $data['notification_ids'] ?? null;
    }
}
