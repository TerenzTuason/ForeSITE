<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModuleAssessment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ModuleAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $assessments = ModuleAssessment::with(['course'])->get();
        return response()->json(['data' => $assessments]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,course_id',
            'module_number' => 'required|integer',
            'assessment_title' => 'required|string',
            'assessment_objective' => 'nullable|string',
            'assessment_scenario' => 'nullable|string',
            'assessment_instructions' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $assessment = ModuleAssessment::create($request->all());
        return response()->json(['data' => $assessment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ModuleAssessment $moduleAssessment): JsonResponse
    {
        return response()->json(['data' => $moduleAssessment->load('course')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModuleAssessment $moduleAssessment): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'exists:courses,course_id',
            'module_number' => 'integer',
            'assessment_title' => 'string',
            'assessment_objective' => 'nullable|string',
            'assessment_scenario' => 'nullable|string',
            'assessment_instructions' => 'array'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $moduleAssessment->update($request->all());
        return response()->json(['data' => $moduleAssessment]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModuleAssessment $moduleAssessment): JsonResponse
    {
        $moduleAssessment->delete();
        return response()->json(null, 204);
    }

    /**
     * Get all assessments for a specific course.
     */
    public function getByCourse(int $courseId): JsonResponse
    {
        $assessments = ModuleAssessment::where('course_id', $courseId)
            ->orderBy('module_number')
            ->get();
        return response()->json(['data' => $assessments]);
    }

    /**
     * Get assessment for a specific course module.
     */
    public function getByCourseModule(int $courseId, int $moduleNumber): JsonResponse
    {
        $assessment = ModuleAssessment::where('course_id', $courseId)
            ->where('module_number', $moduleNumber)
            ->firstOrFail();
        return response()->json(['data' => $assessment]);
    }
} 