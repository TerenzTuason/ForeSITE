<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    protected $primaryKey = 'group_id';

    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'group_name',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class, 'group_id');
    }
} 