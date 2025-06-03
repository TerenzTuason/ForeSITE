<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleAssessmentProgress extends Model
{
    protected $table = 'module_assessment_progress';
    protected $primaryKey = 'assessment_progress_id';
    public $timestamps = false;

    protected $fillable = [
        'module_assessment_id',
        'user_id',
        'module_progress_id',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Get the assessment that owns this progress entry.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(ModuleAssessment::class, 'module_assessment_id');
    }

    /**
     * Get the user that owns this progress entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the module progress that owns this assessment progress.
     */
    public function moduleProgress(): BelongsTo
    {
        return $this->belongsTo(ModuleProgress::class, 'module_progress_id');
    }
} 