<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningStyle extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'learning_styles';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'style_id';
    
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
        'style_name',
        'description',
    ];
    
    /**
     * Get the student profiles that use this learning style.
     */
    public function studentProfiles(): HasMany
    {
        return $this->hasMany(StudentProfile::class, 'dominant_learning_style_id', 'style_id');
    }
}
