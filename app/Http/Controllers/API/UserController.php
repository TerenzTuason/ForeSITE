<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Enrollment;
use App\Models\AssessmentAttempt;
use App\Models\Certificate;
use App\Models\Feedback;
use App\Models\AssessmentResult;
use Illuminate\Support\Facades\DB;
use App\Models\ModuleProgress;
use App\Models\Notification;
use App\Models\QuestionnaireResponse;
use App\Models\Score;
use App\Models\StudentProfile;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::with('role')->get();
        return response()->json(['data' => $users], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,role_id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $userData = $request->all();
        $userData['password'] = Hash::make($request->password);
        
        $user = User::create($userData);
        
        return response()->json(['data' => $user], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $user = User::with('role', 'studentProfile.learningStyle')->find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $user], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'role_id' => 'sometimes|required|exists:roles,role_id',
            'email' => 'sometimes|required|email|unique:users,email,' . $id . ',user_id',
            'password' => 'sometimes|required|min:8',
            'first_name' => 'sometimes|required|string|max:50',
            'last_name' => 'sometimes|required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $userData = $request->all();
        
        if ($request->has('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        return response()->json(['data' => $user], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);

            if (!$user) {
                DB::rollBack();
                return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            // Delete related records
            StudentProfile::where('user_id', $id)->delete();
            Enrollment::where('user_id', $id)->delete();
            AssessmentAttempt::where('user_id', $id)->delete();
            AssessmentResult::where('user_id', $id)->delete();
            Certificate::where('user_id', $id)->delete();
            ModuleProgress::where('user_id', $id)->delete();
            Notification::where('user_id', $id)->delete();
            QuestionnaireResponse::where('user_id', $id)->delete();

            // Handle records where the user could be a student or faculty
            Feedback::where('student_id', $id)->orWhere('faculty_id', $id)->delete();
            Score::where('student_id', $id)->orWhere('faculty_id', $id)->delete();

            // Finally, delete the user
            $user->delete();

            DB::commit();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging purposes
            \Log::error("Error deleting user with ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred while deleting the user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the role associated with the user.
     */
    public function getRole(string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $role = $user->role;
        
        if (!$role) {
            return response()->json(['error' => 'Role not found for this user'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $role], Response::HTTP_OK);
    }

    /**
     * Update the role for a user.
     */
    public function updateRole(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,role_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $user->role_id = $request->role_id;
        $user->save();
        
        return response()->json(['data' => $user->load('role')], Response::HTTP_OK);
    }
    
    /**
     * Get the enrollments for a user.
     */
    public function getEnrollments(string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $enrollments = Enrollment::with('course')->where('user_id', $id)->get();
        
        return response()->json(['data' => $enrollments], Response::HTTP_OK);
    }
    
    /**
     * Get the assessment attempts for a user.
     */
    public function getAssessmentAttempts(string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $attempts = AssessmentAttempt::with('assessment')->where('user_id', $id)->get();
        
        return response()->json(['data' => $attempts], Response::HTTP_OK);
    }
    
    /**
     * Get the certificates for a user.
     */
    public function getCertificates(string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $certificates = Certificate::with('course')->where('user_id', $id)->get();
        
        return response()->json(['data' => $certificates], Response::HTTP_OK);
    }
    
    /**
     * Get feedback received by the user.
     */
    public function getReceivedFeedback(string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $feedback = Feedback::with(['module', 'faculty'])
            ->where('student_id', $id)
            ->get();
        
        return response()->json(['data' => $feedback], Response::HTTP_OK);
    }
    
    /**
     * Get feedback given by the user (for faculty).
     */
    public function getGivenFeedback(string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $feedback = Feedback::with(['module', 'student'])
            ->where('faculty_id', $id)
            ->get();
        
        return response()->json(['data' => $feedback], Response::HTTP_OK);
    }

    /**
     * Get assessment results for a user.
     */
    public function getAssessmentResults(string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $assessmentResults = AssessmentResult::with(['course.learningStyle'])
            ->where('user_id', $id)
            ->orderBy('result_id', 'desc')
            ->get();
        
        // Decode JSON fields
        foreach ($assessmentResults as $result) {
            $result->answers = json_decode($result->answers);
            $result->result = json_decode($result->result);
        }
        
        return response()->json(['data' => $assessmentResults], Response::HTTP_OK);
    }
}
