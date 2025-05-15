<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentResult extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assessment_results';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'result_id';
    
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
        'first_name',
        'last_name',
        'department',
        'user_id',
        'answers',
        'result',
        'course_details'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'answers' => 'json',
        'result' => 'json',
        'course_details' => 'json'
    ];
    
    /**
     * Get the user that owns this assessment result.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
} 