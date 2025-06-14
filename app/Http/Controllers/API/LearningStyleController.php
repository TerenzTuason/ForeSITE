<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LearningStyle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class LearningStyleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $learningStyles = LearningStyle::all();
        return response()->json(['data' => $learningStyles], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'style_name' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $learningStyle = LearningStyle::create($request->all());
        return response()->json(['data' => $learningStyle], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $learningStyle = LearningStyle::find($id);
        
        if (!$learningStyle) {
            return response()->json(['error' => 'Learning style not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $learningStyle], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $learningStyle = LearningStyle::find($id);
        
        if (!$learningStyle) {
            return response()->json(['error' => 'Learning style not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'style_name' => 'sometimes|required|string|max:50',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $learningStyle->update($request->all());
        return response()->json(['data' => $learningStyle], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $learningStyle = LearningStyle::find($id);
        
        if (!$learningStyle) {
            return response()->json(['error' => 'Learning style not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Check if learning style is in use
        if ($learningStyle->studentProfiles->count() > 0) {
            return response()->json(['error' => 'Cannot delete learning style that is being used by students'], Response::HTTP_BAD_REQUEST);
        }
        
        $learningStyle->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
