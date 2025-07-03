<?php

namespace App\Http\Controllers\API;

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
        $lessonScreens = LessonScreen::all();
        return response()->json(['data' => $lessonScreens], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'screen_number' => 'required|string|max:50',
            'screen_title' => 'nullable|string|max:255',
            'screen_description' => 'nullable|string',
            'screen_content' => 'nullable|array',
            'screen_url' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value && !str_contains($value, 'res.cloudinary.com/diugfyxou')) {
                        $fail('The screen URL must be a valid Cloudinary URL.');
                    }
                },
            ],
            'screen_duration' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Check if a lesson screen with the same screen_number already exists
        $existingScreen = LessonScreen::where('screen_number', $request->screen_number)
            ->first();
            
        if ($existingScreen) {
            return response()->json([
                'error' => 'A lesson screen with this screen number already exists'
            ], Response::HTTP_CONFLICT);
        }

        // Create the lesson screen
        $lessonScreen = LessonScreen::create([
            'screen_number' => $request->screen_number,
            'screen_title' => $request->screen_title,
            'screen_description' => $request->screen_description,
            'screen_content' => $request->screen_content,
            'screen_url' => $request->screen_url,
            'screen_duration' => $request->screen_duration
        ]);

        return response()->json(['data' => $lessonScreen], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $lessonScreen = LessonScreen::find($id);
        
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
            'screen_number' => 'sometimes|required|string|max:50',
            'screen_title' => 'nullable|string|max:255',
            'screen_description' => 'nullable|string',
            'screen_content' => 'nullable|array',
            'screen_url' => 'nullable|string|max:255',
            'screen_duration' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Check for uniqueness if screen_number is being updated
        if ($request->has('screen_number') && $request->screen_number != $lessonScreen->screen_number) {
            
            $existingScreen = LessonScreen::where('screen_number', $request->screen_number)
                ->where('lesson_screen_id', '!=', $id)
                ->first();
                
            if ($existingScreen) {
                return response()->json([
                    'error' => 'A lesson screen with this screen number already exists'
                ], Response::HTTP_CONFLICT);
            }
        }

        // Prepare update data
        $updateData = $request->only([
            'screen_number',
            'screen_title',
            'screen_description',
            'screen_url',
            'screen_duration'
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
        
        // Get lesson screens for this course module by joining with the screen progress table
        // Using natural sorting for alphanumeric screen numbers
        $lessonScreens = DB::table('lesson_screens')
            ->join('lesson_screen_progress', 'lesson_screens.lesson_screen_id', '=', 'lesson_screen_progress.lesson_screen_id')
            ->join('module_progress', 'lesson_screen_progress.module_progress_id', '=', 'module_progress.progress_id')
            ->where('module_progress.course_id', $courseId)
            ->where('lesson_screen_progress.course_module_number', $moduleNumber)
            ->select('lesson_screens.*')
            ->distinct()
            ->orderBy(DB::raw('LENGTH(lesson_screens.screen_number)'))
            ->orderBy('lesson_screens.screen_number')
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
        
        // Get lesson screens for this course by joining with the screen progress and module_progress tables
        // Using natural sorting for alphanumeric screen numbers
        $lessonScreens = DB::table('lesson_screens')
            ->join('lesson_screen_progress', 'lesson_screens.lesson_screen_id', '=', 'lesson_screen_progress.lesson_screen_id')
            ->join('module_progress', 'lesson_screen_progress.module_progress_id', '=', 'module_progress.progress_id')
            ->where('module_progress.course_id', $courseId)
            ->select('lesson_screens.*')
            ->distinct()
            ->orderBy('lesson_screen_progress.course_module_number')
            ->orderBy(DB::raw('LENGTH(lesson_screens.screen_number)'))
            ->orderBy('lesson_screens.screen_number')
            ->get();
            
        return response()->json(['data' => $lessonScreens], Response::HTTP_OK);
    }
}
