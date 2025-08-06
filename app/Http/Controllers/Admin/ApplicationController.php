<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\DTO\Admin\ApplicationDTO;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\ApplicationResource;
use App\Services\Admin\Interfaces\ApplicationServiceInterface;

class ApplicationController extends Controller
{
    protected ApplicationServiceInterface $applicationService;

    public function __construct(ApplicationServiceInterface $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * Admin — Barcha arizalarni ko‘rish
     */
    public function index(Request $request)
    {
        try {
            // DTO orqali filter, search, paginate va h.k.
            $dto = ApplicationDTO::fromRequest($request);

            // Servisdan foydalanib ma'lumot olish
            $applications = $this->applicationService->getAllApplications($dto);

            return response()->json([
                'status' => 'success',
                'message' => __('Applications retrieved successfully'),
                'data' => ApplicationResource::collection($applications)
            ]);
        } catch (\Throwable $e) {
            // Exceptionlarni professional tutish
            return response()->json([
                'status' => 'error',
                'message' => __('Failed to load applications'),
                'error' => $e->getMessage()
            ]);
        }
    }
}
