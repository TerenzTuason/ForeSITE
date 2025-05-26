<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonScreen extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lesson_screens';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'lesson_screen_id';

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
        'screen_number',
        'screen_title',
        'screen_description',
        'screen_content',
        'screen_url'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'screen_content' => 'array',
    ];

    // Removed course relationship as there's no direct foreign key anymore
}
