<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModuleProgress;
use App\Models\LessonScreenProgress;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ModuleProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $moduleProgresses = ModuleProgress::with(['user', 'course'])->get();
        return response()->json(['data' => $moduleProgresses], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     * Can handle single or multiple module progress entries
     */
    public function store(Request $request): JsonResponse
    {
        // Check if we're handling a single record or multiple records
        $isBulk = is_array($request->input('data'));
        
        if ($isBulk) {
            return $this->bulkStore($request);
        }
        
        // Single record validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id',
            'course_id' => 'required|exists:courses,course_id',
            'module_number' => 'required|integer',
            'module_title' => 'required|string',
            'module_focus' => 'nullable|string',
            'status' => 'required|in:not_started,in_progress,completed',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'time_spent_minutes' => 'nullable|integer|min:0',
            'score' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Check for existing module progress
        $existingProgress = ModuleProgress::where('user_id', $request->user_id)
            ->where('course_id', $request->course_id)
            ->where('module_number', $request->module_number)
            ->first();
            
        if ($existingProgress) {
            return response()->json([
                'error' => 'Module progress already exists for this user, course, and module number'
            ], Response::HTTP_CONFLICT);
        }

        $moduleProgress = ModuleProgress::create($request->all());
        return response()->json(['data' => $moduleProgress], Response::HTTP_CREATED);
    }
    
    /**
     * Store multiple module progress entries at once
     */
    private function bulkStore(Request $request): JsonResponse
    {
        $data = $request->input('data');
        
        // Validate each record in the array
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'data.*.user_id' => 'required|exists:users,user_id',
            'data.*.course_id' => 'required|exists:courses,course_id',
            'data.*.module_number' => 'required|integer',
            'data.*.module_title' => 'required|string',
            'data.*.module_focus' => 'nullable|string',
            'data.*.status' => 'required|in:not_started,in_progress,completed',
            'data.*.progress_percentage' => 'nullable|integer|min:0|max:100',
            'data.*.started_at' => 'nullable|date',
            'data.*.completed_at' => 'nullable|date',
            'data.*.time_spent_minutes' => 'nullable|integer|min:0',
            'data.*.score' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        
        // Process each record
        $created = [];
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($data as $index => $progressData) {
                // Check for existing module progress
                $existingProgress = ModuleProgress::where('user_id', $progressData['user_id'])
                    ->where('course_id', $progressData['course_id'])
                    ->where('module_number', $progressData['module_number'])
                    ->first();
                    
                if ($existingProgress) {
                    $errors[$index] = 'Module progress already exists for this user, course, and module number';
                    continue;
                }
                
                $moduleProgress = ModuleProgress::create($progressData);
                $created[] = $moduleProgress;
            }
            
            if (!empty($errors)) {
                DB::rollBack();
                return response()->json(['error' => $errors], Response::HTTP_CONFLICT);
            }
            
            DB::commit();
            return response()->json(['data' => $created], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $moduleProgress = ModuleProgress::with(['user', 'course', 'moduleContents'])->find($id);
        
        if (!$moduleProgress) {
            return response()->json(['error' => 'Module progress not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $moduleProgress], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $moduleProgress = ModuleProgress::find($id);
        
        if (!$moduleProgress) {
            return response()->json(['error' => 'Module progress not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,user_id',
            'course_id' => 'sometimes|required|exists:courses,course_id',
            'module_number' => 'sometimes|required|integer',
            'module_title' => 'sometimes|required|string',
            'module_focus' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|in:not_started,in_progress,completed',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'time_spent_minutes' => 'nullable|integer|min:0',
            'score' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // If user_id, course_id, or module_number is changing, check for conflicts
        if (($request->has('user_id') && $request->user_id != $moduleProgress->user_id) ||
            ($request->has('course_id') && $request->course_id != $moduleProgress->course_id) ||
            ($request->has('module_number') && $request->module_number != $moduleProgress->module_number)) {
            
            $existingProgress = ModuleProgress::where('user_id', $request->input('user_id', $moduleProgress->user_id))
                ->where('course_id', $request->input('course_id', $moduleProgress->course_id))
                ->where('module_number', $request->input('module_number', $moduleProgress->module_number))
                ->where('progress_id', '!=', $id)
                ->first();
                
            if ($existingProgress) {
                return response()->json([
                    'error' => 'Module progress already exists for this user, course, and module number'
                ], Response::HTTP_CONFLICT);
            }
        }

        $moduleProgress->update($request->all());
        return response()->json(['data' => $moduleProgress], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $moduleProgress = ModuleProgress::find($id);
        
        if (!$moduleProgress) {
            return response()->json(['error' => 'Module progress not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Check if module progress has related module contents before deleting
        if ($moduleProgress->moduleContents()->count() > 0) {
            return response()->json([
                'error' => 'Cannot delete module progress with existing module contents'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $moduleProgress->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
    /**
     * Get module progress by user.
     */
    public function getProgressByUser(string $userId): JsonResponse
    {
        $moduleProgresses = ModuleProgress::with(['course'])
            ->where('user_id', $userId)
            ->orderBy('course_id')
            ->orderBy('module_number')
            ->get();
            
        return response()->json(['data' => $moduleProgresses], Response::HTTP_OK);
    }
    
    /**
     * Get module progress by course.
     */
    public function getProgressByCourse(string $courseId): JsonResponse
    {
        $moduleProgresses = ModuleProgress::with(['user'])
            ->where('course_id', $courseId)
            ->orderBy('module_number')
            ->get();
            
        return response()->json(['data' => $moduleProgresses], Response::HTTP_OK);
    }
    
    /**
     * Get all module progress and lesson screen progress for a user.
     * 
     * This endpoint provides a comprehensive view of a user's progress across all modules
     * and their associated lesson screens, organized by course and module.
     * 
     * @param string $userId The user ID to get progress for
     * @return JsonResponse
     */
    public function getAllUserProgress(string $userId): JsonResponse
    {
        // Check if user exists and has any module progress
        $moduleProgresses = ModuleProgress::where('user_id', $userId)
            ->orderBy('course_id')
            ->orderBy('module_number')
            ->get();
            
        if ($moduleProgresses->isEmpty()) {
            return response()->json([
                'data' => [],
                'message' => 'No progress found for this user'
            ], Response::HTTP_OK);
        }
        
        // Get all related course IDs to include course details
        $courseIds = $moduleProgresses->pluck('course_id')->unique();
        $courses = Course::whereIn('course_id', $courseIds)->get()
            ->keyBy('course_id');
        
        // Get all module progress IDs to fetch related lesson screen progress
        $moduleProgressIds = $moduleProgresses->pluck('progress_id');
        
        // Fetch all lesson screen progress entries for these modules
        $lessonScreenProgress = LessonScreenProgress::with('lessonScreen')
            ->whereIn('module_progress_id', $moduleProgressIds)
            ->get()
            ->groupBy('module_progress_id');
        
        // Organize data by course and module
        $result = [];
        
        foreach ($moduleProgresses as $moduleProgress) {
            $courseId = $moduleProgress->course_id;
            
            // Create course entry if it doesn't exist in result
            if (!isset($result[$courseId])) {
                $result[$courseId] = [
                    'course_id' => $courseId,
                    'course_name' => $courses[$courseId]->name ?? 'Unknown Course',
                    'modules' => []
                ];
            }
            
            // Add module progress with its lesson screen progress
            $moduleData = $moduleProgress->toArray();
            $moduleData['lesson_screens'] = [];
            
            // Add lesson screen progress for this module if any exist
            if (isset($lessonScreenProgress[$moduleProgress->progress_id])) {
                foreach ($lessonScreenProgress[$moduleProgress->progress_id] as $screenProgress) {
                    $screenData = $screenProgress->toArray();
                    
                    // Include lesson screen details if available
                    if ($screenProgress->lessonScreen) {
                        $screenData['screen_details'] = $screenProgress->lessonScreen->toArray();
                    }
                    
                    $moduleData['lesson_screens'][] = $screenData;
                }
                
                // Sort lesson screens by course_module_number and then by screen_number if available
                usort($moduleData['lesson_screens'], function($a, $b) {
                    // First sort by course_module_number
                    if ($a['course_module_number'] !== $b['course_module_number']) {
                        return $a['course_module_number'] - $b['course_module_number'];
                    }
                    
                    // Then by screen_number if available in screen_details
                    if (isset($a['screen_details']) && isset($b['screen_details'])) {
                        return strnatcmp(
                            $a['screen_details']['screen_number'] ?? '', 
                            $b['screen_details']['screen_number'] ?? ''
                        );
                    }
                    
                    return 0;
                });
            }
            
            $result[$courseId]['modules'][] = $moduleData;
        }
        
        // Convert associative array to indexed array for consistent JSON response
        $result = array_values($result);
        
        return response()->json([
            'data' => $result
        ], Response::HTTP_OK);
    }
}