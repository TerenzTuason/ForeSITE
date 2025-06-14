<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'course_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'objectives',
        'structure',
        'learning_style_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'objectives' => 'json',
        'structure' => 'json',
    ];

    /**
     * Get the learning style associated with the course.
     */
    public function learningStyle(): BelongsTo
    {
        return $this->belongsTo(LearningStyle::class, 'learning_style_id', 'style_id');
    }

    /**
     * Get the enrollments for the course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id', 'course_id');
    }

    /**
     * Get the certificates issued for the course.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'course_id', 'course_id');
    }
}
