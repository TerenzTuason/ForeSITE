<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ModuleAssessmentProgress;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ModuleAssessmentProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $progress = ModuleAssessmentProgress::with(['assessment', 'user', 'moduleProgress'])->get();
        return response()->json(['data' => $progress]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'module_assessment_id' => 'required|exists:module_assessment,assessment_id',
            'user_id' => 'required|exists:users,user_id',
            'module_progress_id' => 'required|exists:module_progress,progress_id',
            'status' => 'required|in:not_started,in_progress,completed',
            'file_url' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $progress = ModuleAssessmentProgress::create($request->all());
        return response()->json(['data' => $progress], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ModuleAssessmentProgress $moduleAssessmentProgress): JsonResponse
    {
        return response()->json([
            'data' => $moduleAssessmentProgress->load(['assessment', 'user', 'moduleProgress'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModuleAssessmentProgress $moduleAssessmentProgress): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'module_assessment_id' => 'exists:module_assessment,assessment_id',
            'user_id' => 'exists:users,user_id',
            'module_progress_id' => 'exists:module_progress,progress_id',
            'status' => 'in:not_started,in_progress,completed',
            'file_url' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $moduleAssessmentProgress->update($request->all());
        return response()->json(['data' => $moduleAssessmentProgress]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModuleAssessmentProgress $moduleAssessmentProgress): JsonResponse
    {
        $moduleAssessmentProgress->delete();
        return response()->json(null, 204);
    }

    /**
     * Get all assessment progress for a specific user.
     */
    public function getByUser(int $userId): JsonResponse
    {
        $progress = ModuleAssessmentProgress::with(['assessment', 'moduleProgress'])
            ->where('user_id', $userId)
            ->get();
        return response()->json(['data' => $progress]);
    }

    /**
     * Get all progress entries for a specific assessment.
     */
    public function getByAssessment(int $assessmentId): JsonResponse
    {
        $progress = ModuleAssessmentProgress::with(['user', 'moduleProgress'])
            ->where('module_assessment_id', $assessmentId)
            ->get();
        return response()->json(['data' => $progress]);
    }

    /**
     * Get all progress entries for a specific module progress.
     */
    public function getByModuleProgress(int $moduleProgressId): JsonResponse
    {
        $progress = ModuleAssessmentProgress::with(['assessment', 'user'])
            ->where('module_progress_id', $moduleProgressId)
            ->get();
        return response()->json(['data' => $progress]);
    }
} 