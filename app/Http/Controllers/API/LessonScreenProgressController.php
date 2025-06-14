<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LessonScreenProgress;
use App\Models\ModuleProgress;
use App\Models\LessonScreen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class LessonScreenProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $moduleId): JsonResponse
    {
        $moduleProgress = ModuleProgress::find($moduleId);
        
        if (!$moduleProgress) {
            return response()->json(['error' => 'Module progress not found'], Response::HTTP_NOT_FOUND);
        }
        
        $screenProgress = LessonScreenProgress::with('lessonScreen')
            ->where('module_progress_id', $moduleId)
            ->get();
            
        return response()->json(['data' => $screenProgress], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $moduleId): JsonResponse
    {
        $moduleProgress = ModuleProgress::find($moduleId);
        
        if (!$moduleProgress) {
            return response()->json(['error' => 'Module progress not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'lesson_screen_id' => 'required|exists:lesson_screens,lesson_screen_id',
            'course_module_number' => 'required|integer|min:1',
            'status' => 'required|in:not_started,in_progress,completed',
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Check if a progress entry already exists for this lesson screen
        $existingProgress = LessonScreenProgress::where('module_progress_id', $moduleId)
            ->where('lesson_screen_id', $request->lesson_screen_id)
            ->first();
            
        if ($existingProgress) {
            return response()->json([
                'error' => 'Progress entry already exists for this lesson screen'
            ], Response::HTTP_CONFLICT);
        }

        $screenProgress = LessonScreenProgress::create([
            'module_progress_id' => $moduleId,
            'lesson_screen_id' => $request->lesson_screen_id,
            'course_module_number' => $request->course_module_number,
            'status' => $request->status,
            'progress_percentage' => $request->progress_percentage,
        ]);
        
        return response()->json(['data' => $screenProgress], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $screenProgress = LessonScreenProgress::with(['moduleProgress', 'lessonScreen'])
            ->find($id);
            
        if (!$screenProgress) {
            return response()->json(['error' => 'Lesson screen progress not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $screenProgress], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $screenProgress = LessonScreenProgress::find($id);
        
        if (!$screenProgress) {
            return response()->json(['error' => 'Lesson screen progress not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'course_module_number' => 'sometimes|required|integer|min:1',
            'status' => 'sometimes|required|in:not_started,in_progress,completed',
            'progress_percentage' => 'sometimes|required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $screenProgress->update($request->all());
        return response()->json(['data' => $screenProgress], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $screenProgress = LessonScreenProgress::find($id);
        
        if (!$screenProgress) {
            return response()->json(['error' => 'Lesson screen progress not found'], Response::HTTP_NOT_FOUND);
        }
        
        $screenProgress->delete();
            
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get all progress entries for a specific lesson screen
     */
    public function getByLessonScreen(string $lessonScreenId): JsonResponse
    {
        $lessonScreen = LessonScreen::find($lessonScreenId);
        
        if (!$lessonScreen) {
            return response()->json(['error' => 'Lesson screen not found'], Response::HTTP_NOT_FOUND);
        }
        
        $progressEntries = LessonScreenProgress::with('moduleProgress')
            ->where('lesson_screen_id', $lessonScreenId)
            ->get();
            
        return response()->json(['data' => $progressEntries], Response::HTTP_OK);
    }
}
