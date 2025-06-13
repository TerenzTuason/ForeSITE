<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FacultyAssignedGroup extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faculty_assigned_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'faculty_id',
        'group_id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
} 