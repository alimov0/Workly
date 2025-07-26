<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ApplicationResource;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class ApplicationController extends Controller
{
    /**
     * Admin – Barcha arizalarni ko‘rish
     */
    public function index(Request $request)
    {
        try {
            $query = Application::with(['user', 'vacancy']);

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('vacancy_id')) {
                $query->where('vacancy_id', $request->vacancy_id);
            }

            $applications = $query->paginate($request->per_page ?? 15);

            return response()->json([
                'status' => 'success',
                'message' => __('Applications retrieved successfully'),
                'data'    => ApplicationResource::collection($applications)
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('Failed to load applications'),
                
            ]);
        }
    }
}
