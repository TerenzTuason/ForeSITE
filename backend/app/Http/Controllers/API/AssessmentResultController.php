<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssessmentResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AssessmentResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $results = AssessmentResult::with(['user', 'course'])->get();
        
        // Decode JSON fields
        foreach ($results as $result) {
            $result->answers = json_decode($result->answers);
            $result->result = json_decode($result->result);
        }
        
        return response()->json(['data' => $results], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:200',
            'last_name' => 'required|string|max:200',
            'department' => 'required|string|max:200',
            'user_id' => 'required|exists:users,user_id',
            'course_id' => 'required|exists:courses,course_id',
            'answers' => 'required|array',
            'answers.answers' => 'required|array',
            'answers.answers.*' => 'required|integer|in:0,1',
            'result' => 'required|array',
            'result.confidence' => 'required|numeric',
            'result.individual_votes' => 'required|array',
            'result.individual_votes.bayesian_network' => 'required|string',
            'result.individual_votes.decision_tree' => 'required|string',
            'result.individual_votes.random_forest' => 'required|string',
            'result.individual_votes.support_vector_machine' => 'required|string',
            'result.learning_style' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $result = AssessmentResult::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'department' => $request->department,
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
            'answers' => json_encode($request->answers),
            'result' => json_encode($request->result),
        ]);

        // Decode JSON fields for response
        $result->answers = json_decode($result->answers);
        $result->result = json_decode($result->result);

        return response()->json(['data' => $result], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $result = AssessmentResult::with(['user', 'course'])->find($id);
        
        if (!$result) {
            return response()->json(['error' => 'Assessment result not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Decode JSON fields
        $result->answers = json_decode($result->answers);
        $result->result = json_decode($result->result);
        
        return response()->json(['data' => $result], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $result = AssessmentResult::find($id);
        
        if (!$result) {
            return response()->json(['error' => 'Assessment result not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:200',
            'last_name' => 'sometimes|required|string|max:200',
            'department' => 'sometimes|required|string|max:200',
            'user_id' => 'sometimes|required|exists:users,user_id',
            'course_id' => 'sometimes|required|exists:courses,course_id',
            'answers' => 'sometimes|required|array',
            'answers.answers' => 'required|array',
            'answers.answers.*' => 'required|integer|in:0,1',
            'result' => 'sometimes|required|array',
            'result.confidence' => 'required|numeric',
            'result.individual_votes' => 'required|array',
            'result.individual_votes.bayesian_network' => 'required|string',
            'result.individual_votes.decision_tree' => 'required|string',
            'result.individual_votes.random_forest' => 'required|string',
            'result.individual_votes.support_vector_machine' => 'required|string',
            'result.learning_style' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $updateData = [
            'first_name' => $request->first_name ?? $result->first_name,
            'last_name' => $request->last_name ?? $result->last_name,
            'department' => $request->department ?? $result->department,
            'user_id' => $request->user_id ?? $result->user_id,
            'course_id' => $request->course_id ?? $result->course_id,
        ];

        if ($request->has('answers')) {
            $updateData['answers'] = json_encode($request->answers);
        }

        if ($request->has('result')) {
            $updateData['result'] = json_encode($request->result);
        }

        $result->update($updateData);
        
        // Decode JSON fields for response
        $result->answers = json_decode($result->answers);
        $result->result = json_decode($result->result);
        
        return response()->json(['data' => $result], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $result = AssessmentResult::find($id);
        
        if (!$result) {
            return response()->json(['error' => 'Assessment result not found'], Response::HTTP_NOT_FOUND);
        }
        
        $result->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
} 