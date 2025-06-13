<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'group_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'course_id',
        'group_name',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the course that the group belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    /**
     * Get the members of the group.
     */
    public function groupMembers(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'group_id', 'group_id');
    }

    /**
     * Get the student members of the group.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'user_id');
    }

    /**
     * Get the faculty assigned to the group.
     */
    public function assignedFaculty(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'faculty_assigned_groups', 'group_id', 'faculty_id');
    }

    /**
     * Get the messages for the group.
     */
    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'group_id', 'group_id');
    }
} 