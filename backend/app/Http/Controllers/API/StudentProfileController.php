<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\LearningStyle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $profiles = StudentProfile::with(['user', 'learningStyle'])->get();
        return response()->json(['data' => $profiles], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id|unique:student_profiles,user_id',
            'dominant_learning_style_id' => 'required|exists:learning_styles,style_id',
            'course_id' => 'required|exists:courses,course_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $profile = StudentProfile::create($request->except('course_id'));

        try {
            $this->assignUserToGroup(
                $request->user_id,
                $request->course_id,
                $request->dominant_learning_style_id,
                $profile
            );
        } catch (\Exception $e) {
            Log::error('Failed to assign user to group: ' . $e->getMessage());
            // Decide if you want to fail the request or just log the error
            // For now, we'll just log it and the profile creation will still succeed.
        }
        
        // Load the relationships
        $profile->load(['user', 'learningStyle']);
        
        return response()->json(['data' => $profile], Response::HTTP_CREATED);
    }

    private function assignUserToGroup($userId, $courseId, $learningStyleId, StudentProfile $profile)
    {
        $learningStyle = LearningStyle::find($learningStyleId);
        if (!$learningStyle) {
            // This should ideally not happen due to validation, but as a safeguard.
            throw new \Exception("Learning style with ID $learningStyleId not found.");
        }

        $baseGroupName = $learningStyle->style_name;

        // Find an existing group for this course and learning style with less than 6 members
        $targetGroup = Group::where('course_id', $courseId)
            ->where('group_name', 'like', $baseGroupName . '%')
            ->withCount('members')
            ->get()
            ->firstWhere('members_count', '<', 6);

        if (!$targetGroup) {
            // No available group found, create a new one.
            $groupCount = Group::where('course_id', $courseId)
                ->where('group_name', 'like', $baseGroupName . '%')
                ->count();
            
            $newGroupName = $baseGroupName . ' Group ' . ($groupCount + 1);

            $targetGroup = Group::create([
                'course_id' => $courseId,
                'group_name' => $newGroupName,
            ]);
        }

        // Add user to the target group
        GroupMember::create([
            'group_id' => $targetGroup->group_id,
            'user_id' => $userId,
        ]);

        // Update the student's profile with the group ID
        $profile->group_id = $targetGroup->group_id;
        $profile->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $profile = StudentProfile::with(['user', 'learningStyle'])->find($id);
        
        if (!$profile) {
            return response()->json(['error' => 'Student profile not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $profile], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $profile = StudentProfile::find($id);
        
        if (!$profile) {
            return response()->json(['error' => 'Student profile not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,user_id|unique:student_profiles,user_id,' . $profile->profile_id . ',profile_id',
            'dominant_learning_style_id' => 'nullable|exists:learning_styles,style_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $profile->update($request->all());
        
        // Reload the profile with its relationships
        $profile->load(['user', 'learningStyle']);
        
        return response()->json(['data' => $profile], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $profile = StudentProfile::find($id);
        
        if (!$profile) {
            return response()->json(['error' => 'Student profile not found'], Response::HTTP_NOT_FOUND);
        }
        
        $profile->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
