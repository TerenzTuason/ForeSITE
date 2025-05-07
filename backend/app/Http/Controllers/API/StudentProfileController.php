<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            'dominant_learning_style_id' => 'nullable|exists:learning_styles,style_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $profile = StudentProfile::create($request->all());
        
        // Load the relationships
        $profile->load(['user', 'learningStyle']);
        
        return response()->json(['data' => $profile], Response::HTTP_CREATED);
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
