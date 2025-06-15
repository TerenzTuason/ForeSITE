<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScoreController extends Controller
{
    /**
     * Display a listing of the scores.
     */
    public function index()
    {
        $scores = Score::with(['faculty', 'moduleAssessmentProgress'])->get();
        return response()->json(['data' => $scores]);
    }

    /**
     * Store a newly created score in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faculty_id' => 'required|exists:users,user_id',
            'score' => 'required|integer|min:1|max:5',
            'module_assessment_progress_id' => 'required|exists:module_assessment_progress,assessment_progress_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $score = Score::create($request->all());
        return response()->json(['data' => $score], 201);
    }

    /**
     * Display the specified score.
     */
    public function show($id)
    {
        $score = Score::with(['faculty', 'moduleAssessmentProgress'])->find($id);
        
        if (!$score) {
            return response()->json(['error' => 'Score not found'], 404);
        }

        return response()->json(['data' => $score]);
    }

    /**
     * Update the specified score in storage.
     */
    public function update(Request $request, $id)
    {
        $score = Score::find($id);

        if (!$score) {
            return response()->json(['error' => 'Score not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'faculty_id' => 'exists:users,user_id',
            'score' => 'integer|min:1|max:5',
            'module_assessment_progress_id' => 'exists:module_assessment_progress,assessment_progress_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $score->update($request->all());
        return response()->json(['data' => $score]);
    }

    /**
     * Remove the specified score from storage.
     */
    public function destroy($id)
    {
        $score = Score::find($id);

        if (!$score) {
            return response()->json(['error' => 'Score not found'], 404);
        }

        $score->delete();
        return response()->json(null, 204);
    }

    /**
     * Get all scores given by a specific faculty member.
     */
    public function getFacultyScores($facultyId)
    {
        $scores = Score::with(['moduleAssessmentProgress'])
            ->where('faculty_id', $facultyId)
            ->get();
        
        return response()->json(['data' => $scores]);
    }

    /**
     * Get all scores for a specific module assessment progress.
     */
    public function getAssessmentProgressScores($progressId)
    {
        $scores = Score::with(['faculty'])
            ->where('module_assessment_progress_id', $progressId)
            ->get();
        
        return response()->json(['data' => $scores]);
    }
} 