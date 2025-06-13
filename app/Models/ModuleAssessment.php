<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleAssessment extends Model
{
    protected $table = 'module_assessment';
    protected $primaryKey = 'assessment_id';
    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'module_number',
        'assessment_title',
        'assessment_objective',
        'assessment_scenario',
        'assessment_instructions'
    ];

    protected $casts = [
        'assessment_instructions' => 'json'
    ];

    /**
     * Get the course that owns the assessment.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get all progress entries for this assessment.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(ModuleAssessmentProgress::class, 'module_assessment_id');
    }
} 