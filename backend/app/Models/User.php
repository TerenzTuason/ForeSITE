<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'email',
        'password',
        'first_name',
        'last_name',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the student profile associated with the user.
     */
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    /**
     * Get the courses created by the user.
     */
    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    /**
     * Get the course enrollments for the user.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    /**
     * Get all the enrolled courses for the user.
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id')
                    ->withPivot(['enrollment_date', 'completion_status', 'completion_date']);
    }

    /**
     * Get the module progress records for the user.
     */
    public function moduleProgress()
    {
        return $this->hasMany(ModuleProgress::class, 'user_id');
    }

    /**
     * Get the assessment attempts for the user.
     */
    public function assessmentAttempts()
    {
        return $this->hasMany(AssessmentAttempt::class, 'user_id');
    }

    /**
     * Get the certificates earned by the user.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'user_id');
    }

    /**
     * Get the faculty feedback given by the user.
     */
    public function givenFeedback()
    {
        return $this->hasMany(Feedback::class, 'faculty_id');
    }

    /**
     * Get the feedback received by the user.
     */
    public function receivedFeedback()
    {
        return $this->hasMany(Feedback::class, 'student_id');
    }

    /**
     * Check if user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role->role_name === 'admin';
    }

    /**
     * Check if user is a faculty member.
     *
     * @return bool
     */
    public function isFaculty()
    {
        return $this->role->role_name === 'faculty';
    }

    /**
     * Check if user is a student.
     *
     * @return bool
     */
    public function isStudent()
    {
        return $this->role->role_name === 'student';
    }
}
