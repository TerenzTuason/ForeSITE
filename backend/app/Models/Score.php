<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'scores';
    protected $primaryKey = 'score_id';
    public $timestamps = false;

    protected $fillable = [
        'faculty_id',
        'score',
        'module_assessment_progress_id'
    ];

    /**
     * Get the faculty that gave the score.
     */
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id', 'user_id');
    }

    /**
     * Get the module assessment progress that was scored.
     */
    public function moduleAssessmentProgress()
    {
        return $this->belongsTo(ModuleAssessmentProgress::class, 'module_assessment_progress_id', 'assessment_progress_id');
    }
} 