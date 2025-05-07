<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_profiles';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'profile_id';
    
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
        'user_id',
        'dominant_learning_style_id',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'profile_created_at' => 'datetime',
        'last_updated' => 'datetime',
    ];
    
    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    
    /**
     * Get the learning style of this profile.
     */
    public function learningStyle(): BelongsTo
    {
        return $this->belongsTo(LearningStyle::class, 'dominant_learning_style_id', 'style_id');
    }
}
