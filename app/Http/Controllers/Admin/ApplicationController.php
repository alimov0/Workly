<?php
namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ApplicationResource;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index(Request $request)
    {
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

        return response()->json([
            'message' => __('Applications retrieved successfully'),
            'data' => ApplicationResource::collection($query->paginate($request->per_page ?? 15))
        ]);
}
}