<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LessonScreen;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LessonScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $lessonScreens = LessonScreen::with('course')->get();
        return response()->json(['data' => $lessonScreens], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,course_id',
            'course_module_number' => 'required|integer|min:1',
            'screen_number' => 'required|integer|min:1',
            'screen_title' => 'required|string|max:255',
            'screen_description' => 'required|string',
            'screen_content' => 'required|array',
            'screen_url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Check if a lesson screen with the same course_id, course_module_number, and screen_number already exists
        $existingScreen = LessonScreen::where('course_id', $request->course_id)
            ->where('course_module_number', $request->course_module_number)
            ->where('screen_number', $request->screen_number)
            ->first();
            
        if ($existingScreen) {
            return response()->json([
                'error' => 'A lesson screen with this course ID, module number, and screen number already exists'
            ], Response::HTTP_CONFLICT);
        }

        // Create the lesson screen
        $lessonScreen = LessonScreen::create([
            'course_id' => $request->course_id,
            'course_module_number' => $request->course_module_number,
            'screen_number' => $request->screen_number,
            'screen_title' => $request->screen_title,
            'screen_description' => $request->screen_description,
            'screen_content' => $request->screen_content,
            'screen_url' => $request->screen_url,
        ]);

        return response()->json(['data' => $lessonScreen], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $lessonScreen = LessonScreen::with('course')->find($id);
        
        if (!$lessonScreen) {
            return response()->json(['error' => 'Lesson screen not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $lessonScreen], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $lessonScreen = LessonScreen::find($id);
        
        if (!$lessonScreen) {
            return response()->json(['error' => 'Lesson screen not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Validate request
        $validator = Validator::make($request->all(), [
            'course_id' => 'sometimes|required|exists:courses,course_id',
            'course_module_number' => 'sometimes|required|integer|min:1',
            'screen_number' => 'sometimes|required|integer|min:1',
            'screen_title' => 'sometimes|required|string|max:255',
            'screen_description' => 'sometimes|required|string',
            'screen_content' => 'sometimes|required|array',
            'screen_url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Check for uniqueness if course_id, course_module_number, or screen_number is being updated
        if (($request->has('course_id') && $request->course_id != $lessonScreen->course_id) ||
            ($request->has('course_module_number') && $request->course_module_number != $lessonScreen->course_module_number) ||
            ($request->has('screen_number') && $request->screen_number != $lessonScreen->screen_number)) {
            
            $existingScreen = LessonScreen::where('course_id', $request->input('course_id', $lessonScreen->course_id))
                ->where('course_module_number', $request->input('course_module_number', $lessonScreen->course_module_number))
                ->where('screen_number', $request->input('screen_number', $lessonScreen->screen_number))
                ->where('lesson_screen_id', '!=', $id)
                ->first();
                
            if ($existingScreen) {
                return response()->json([
                    'error' => 'A lesson screen with this course ID, module number, and screen number already exists'
                ], Response::HTTP_CONFLICT);
            }
        }

        // Prepare update data
        $updateData = $request->only([
            'course_id',
            'course_module_number',
            'screen_number',
            'screen_title',
            'screen_description',
            'screen_url',
        ]);
        
        // Handle screen_content separately
        if ($request->has('screen_content')) {
            $updateData['screen_content'] = $request->screen_content;
        }

        $lessonScreen->update($updateData);
        return response()->json(['data' => $lessonScreen], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $lessonScreen = LessonScreen::find($id);
        
        if (!$lessonScreen) {
            return response()->json(['error' => 'Lesson screen not found'], Response::HTTP_NOT_FOUND);
        }
        
        $lessonScreen->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get lesson screens by course ID and module number.
     */
    public function getByCourseModule(string $courseId, string $moduleNumber): JsonResponse
    {
        // First, verify course exists
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Verify the module number exists in the course structure
        $courseStructure = json_decode($course->structure, true);
        $moduleExists = false;
        
        foreach ($courseStructure as $module) {
            if (isset($module['module']) && $module['module'] == $moduleNumber) {
                $moduleExists = true;
                break;
            }
        }
        
        if (!$moduleExists) {
            return response()->json(['error' => 'Module number not found in course structure'], Response::HTTP_NOT_FOUND);
        }
        
        $lessonScreens = LessonScreen::where('course_id', $courseId)
            ->where('course_module_number', $moduleNumber)
            ->orderBy('screen_number')
            ->get();
            
        return response()->json(['data' => $lessonScreens], Response::HTTP_OK);
    }
    
    /**
     * Get all lesson screens for a course.
     */
    public function getByCourse(string $courseId): JsonResponse
    {
        // First, verify course exists
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        $lessonScreens = LessonScreen::where('course_id', $courseId)
            ->orderBy('course_module_number')
            ->orderBy('screen_number')
            ->get();
            
        return response()->json(['data' => $lessonScreens], Response::HTTP_OK);
    }
}
