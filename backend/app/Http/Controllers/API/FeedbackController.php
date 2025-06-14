<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\ModuleAssessmentProgress;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the feedback.
     */
    public function index()
    {
        $feedback = Feedback::with(['faculty', 'moduleAssessmentProgress'])->get();
        return response()->json(['data' => $feedback]);
    }

    /**
     * Store a newly created feedback in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faculty_id' => 'required|exists:users,user_id',
            'feedback' => 'required|string',
            'module_assessment_progress_id' => 'required|exists:module_assessment_progress,assessment_progress_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $feedback = Feedback::create($request->all());

        // Create a notification for the student
        if ($feedback) {
            $progress = ModuleAssessmentProgress::with(['user', 'assessment'])
                ->find($request->input('module_assessment_progress_id'));

            if ($progress && $progress->user) {
                $studentId = $progress->user->user_id;
                $facultyId = $request->input('faculty_id');
                $assessmentTitle = $progress->assessment ? $progress->assessment->assessment_title : 'your assessment';
                
                Notification::create([
                    'user_id' => $studentId,
                    'sender_id' => $facultyId,
                    'message' => "Your instructor has provided feedback for '{$assessmentTitle}'.",
                ]);
            }
        }

        return response()->json(['data' => $feedback], 201);
    }

    /**
     * Display the specified feedback.
     */
    public function show($id)
    {
        $feedback = Feedback::with(['faculty', 'moduleAssessmentProgress'])->find($id);
        
        if (!$feedback) {
            return response()->json(['error' => 'Feedback not found'], 404);
        }

        return response()->json(['data' => $feedback]);
    }

    /**
     * Update the specified feedback in storage.
     */
    public function update(Request $request, $id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['error' => 'Feedback not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'faculty_id' => 'exists:users,user_id',
            'feedback' => 'string',
            'module_assessment_progress_id' => 'exists:module_assessment_progress,assessment_progress_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $feedback->update($request->all());
        return response()->json(['data' => $feedback]);
    }

    /**
     * Remove the specified feedback from storage.
     */
    public function destroy($id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['error' => 'Feedback not found'], 404);
        }

        $feedback->delete();
        return response()->json(null, 204);
    }

    /**
     * Get all feedback given by a specific faculty member.
     */
    public function getFacultyFeedback($facultyId)
    {
        $feedback = Feedback::with(['moduleAssessmentProgress'])
            ->where('faculty_id', $facultyId)
            ->get();
        
        return response()->json(['data' => $feedback]);
    }

    /**
     * Get all feedback for a specific module assessment progress.
     */
    public function getAssessmentProgressFeedback($progressId)
    {
        $feedback = Feedback::with(['faculty'])
            ->where('module_assessment_progress_id', $progressId)
            ->get();
        
        return response()->json(['data' => $feedback]);
    }
}
