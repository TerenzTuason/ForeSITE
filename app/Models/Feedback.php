<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'feedback_id';
    public $timestamps = false;

    protected $fillable = [
        'faculty_id',
        'feedback',
        'module_assessment_progress_id'
    ];

    /**
     * Get the faculty that gave the feedback.
     */
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id', 'user_id');
    }

    /**
     * Get the module assessment progress that received the feedback.
     */
    public function moduleAssessmentProgress()
    {
        return $this->belongsTo(ModuleAssessmentProgress::class, 'module_assessment_progress_id', 'assessment_progress_id');
    }
}
