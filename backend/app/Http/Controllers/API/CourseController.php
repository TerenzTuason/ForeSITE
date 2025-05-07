<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $courses = Course::with('creator')->get();
        return response()->json(['data' => $courses], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'created_by' => 'required|exists:users,user_id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $course = Course::create($request->all());
        return response()->json(['data' => $course], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $course = Course::with(['creator', 'modules'])->find($id);
        
        if (!$course) {
            return response()->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $course], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string',
            'created_by' => 'sometimes|required|exists:users,user_id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $course->update($request->all());
        return response()->json(['data' => $course], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Check if course has enrollments, modules, or certificates before deleting
        if ($course->enrollments()->count() > 0) {
            return response()->json(['error' => 'Cannot delete course with active enrollments'], Response::HTTP_BAD_REQUEST);
        }
        
        if ($course->modules()->count() > 0) {
            return response()->json(['error' => 'Cannot delete course with existing modules'], Response::HTTP_BAD_REQUEST);
        }
        
        if ($course->certificates()->count() > 0) {
            return response()->json(['error' => 'Cannot delete course with issued certificates'], Response::HTTP_BAD_REQUEST);
        }
        
        $course->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
    /**
     * Get all enrollments for a course.
     */
    public function getEnrollments(string $id): JsonResponse
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        $enrollments = Enrollment::with('user')->where('course_id', $id)->get();
        
        return response()->json(['data' => $enrollments], Response::HTTP_OK);
    }
    
    /**
     * Add a new enrollment to a course.
     */
    public function addEnrollment(Request $request, string $id): JsonResponse
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        
        // Check if enrollment already exists
        $existingEnrollment = Enrollment::where('user_id', $request->user_id)
            ->where('course_id', $id)
            ->first();
            
        if ($existingEnrollment) {
            return response()->json(['error' => 'User is already enrolled in this course'], Response::HTTP_BAD_REQUEST);
        }
        
        $enrollment = Enrollment::create([
            'user_id' => $request->user_id,
            'course_id' => $id,
            'completion_status' => 'not_started'
        ]);
        
        return response()->json(['data' => $enrollment], Response::HTTP_CREATED);
    }
    
    /**
     * Remove an enrollment from a course.
     */
    public function removeEnrollment(string $courseId, string $enrollmentId): JsonResponse
    {
        $enrollment = Enrollment::find($enrollmentId);
        
        if (!$enrollment) {
            return response()->json(['error' => 'Enrollment not found'], Response::HTTP_NOT_FOUND);
        }
        
        if ($enrollment->course_id != $courseId) {
            return response()->json(['error' => 'Enrollment does not belong to this course'], Response::HTTP_BAD_REQUEST);
        }
        
        $enrollment->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
    /**
     * Get certificates issued for a course.
     */
    public function getCertificates(string $id): JsonResponse
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['error' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        $certificates = Certificate::with('user')->where('course_id', $id)->get();
        
        return response()->json(['data' => $certificates], Response::HTTP_OK);
    }
}
