<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleProgress extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'module_progress';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'progress_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'module_number',
        'module_title',
        'module_focus',
        'status',
        'progress_percentage',
        'started_at',
        'completed_at',
        'time_spent_minutes',
        'score'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'integer',
        'time_spent_minutes' => 'integer',
        'score' => 'integer',
    ];

    /**
     * Get the user that owns the module progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the course that owns the module progress.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    /**
     * Get the module contents for the module progress.
     */
    public function moduleContents(): HasMany
    {
        return $this->hasMany(ModuleContent::class, 'module_progress_id', 'progress_id');
    }
}
