<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MarkNotificationAsReadRequest;
use App\Interfaces\Services\Admin\NotificationServiceInterface;
use App\DTO\Admin\NotificationDTO;

class NotificationController extends Controller
{
    protected NotificationServiceInterface $notificationService;

    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function markAsRead(MarkNotificationAsReadRequest $request): JsonResponse
    {
        try {
            $dto = new NotificationDTO($request->validated());
            return $this->notificationService->markAsRead($dto);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('Something went wrong'),
            ], 500);
        }
    }
}
