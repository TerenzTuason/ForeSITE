<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\AssessmentResult;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    /**
     * Store a new enrollment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,user_id',
            'course_id' => 'required|integer|exists:courses,course_id',
            'assessment_result_id' => 'required|integer|exists:assessment_results,result_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if enrollment already exists
        $existingEnrollment = Enrollment::where('user_id', $request->user_id)
            ->where('course_id', $request->course_id)
            ->first();

        if ($existingEnrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is already enrolled in this course'
            ], 409);
        }

        try {
            $enrollment = Enrollment::create([
                'user_id' => $request->user_id,
                'course_id' => $request->course_id,
                'assessment_result_id' => $request->assessment_result_id,
                'enrollment_date' => now(),
                'completion_status' => 'not_started'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Enrollment created successfully',
                'data' => $enrollment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create enrollment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 