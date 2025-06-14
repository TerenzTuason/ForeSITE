<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonScreenProgress extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lesson_screen_progress';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'screen_progress_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_progress_id',
        'lesson_screen_id',
        'course_module_number',
        'status',
        'progress_percentage'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'progress_percentage' => 'integer',
    ];

    /**
     * Get the module progress that owns the lesson screen progress.
     */
    public function moduleProgress(): BelongsTo
    {
        return $this->belongsTo(ModuleProgress::class, 'module_progress_id', 'progress_id');
    }

    /**
     * Get the lesson screen associated with this progress.
     */
    public function lessonScreen(): BelongsTo
    {
        return $this->belongsTo(LessonScreen::class, 'lesson_screen_id', 'lesson_screen_id');
    }
}
