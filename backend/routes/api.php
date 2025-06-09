<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LearningStyleController;
use App\Http\Controllers\Api\AssessmentResultController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StudentProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\ModuleProgressController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\LessonScreenController;
use App\Http\Controllers\Api\LessonScreenProgressController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Api\ModuleAssessmentController;
use App\Http\Controllers\Api\ModuleAssessmentProgressController;
use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\GroupMemberController;
use App\Http\Controllers\FacultyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API Routes
Route::prefix('v1')->group(function () {
    // Diagnostic route to check database connection
    Route::get('diagnostic/db-check', function () {
        try {
            // Check database connection
            \DB::connection()->getPdo();
            
            // Check if we can select from users table
            $userCount = \DB::table('users')->count();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Database connection successful',
                'connection' => \DB::connection()->getDatabaseName(),
                'user_count' => $userCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage()
            ], 500);
        }
    });
    
    // Authentication Routes
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);
    
    // Roles
    Route::apiResource('roles', RoleController::class);
    
    // Users (fully RESTful, independent from roles)
    Route::apiResource('users', UserController::class);
    
    // User roles relationship (RESTful nested resource)
    Route::get('users/{user}/role', [UserController::class, 'getRole']);
    Route::put('users/{user}/role', [UserController::class, 'updateRole']);
    
    // Learning Styles
    Route::apiResource('learning-styles', LearningStyleController::class);
    
    // Student Profiles
    Route::apiResource('student-profiles', StudentProfileController::class);
    
    // Assessment Results
    Route::apiResource('assessment-results', AssessmentResultController::class);
    Route::get('users/{user}/assessment-results', [UserController::class, 'getAssessmentResults']);
    
    // Courses
    Route::apiResource('courses', CourseController::class);
    
    // Course Enrollments
    Route::get('courses/{course}/enrollments', [CourseController::class, 'getEnrollments']);
    Route::post('courses/{course}/enrollments', [CourseController::class, 'addEnrollment']);
    Route::delete('courses/{course}/enrollments/{enrollment}', [CourseController::class, 'removeEnrollment']);
    
    // User Enrollments
    Route::get('users/{user}/enrollments', [UserController::class, 'getEnrollments']);
    
    // Modules
    // Route::apiResource('courses.modules', ModuleController::class)->shallow();
    
    // Module Progress
    Route::apiResource('module-progress', ModuleProgressController::class);
    Route::get('users/{user}/module-progress', [ModuleProgressController::class, 'getProgressByUser']);
    Route::get('courses/{course}/module-progress', [ModuleProgressController::class, 'getProgressByCourse']);
    Route::get('users/{user}/all-progress', [ModuleProgressController::class, 'getAllUserProgress']);
    
    // Lesson Screen Progress
    Route::apiResource('module-progress.screen-progress', LessonScreenProgressController::class)->shallow();
    Route::get('lesson-screens/{lessonScreen}/progress', [LessonScreenProgressController::class, 'getByLessonScreen']);
    
    // Certificates
    Route::apiResource('certificates', CertificateController::class);
    Route::get('users/{user}/certificates', [UserController::class, 'getCertificates']);
    Route::get('courses/{course}/certificates', [CourseController::class, 'getCertificates']);
    
    // Feedback
    Route::apiResource('feedback', FeedbackController::class);
    Route::get('users/{user}/received-feedback', [UserController::class, 'getReceivedFeedback']);
    Route::get('users/{user}/given-feedback', [UserController::class, 'getGivenFeedback']);

    // Enrollments
    Route::post('/enrollments', [EnrollmentController::class, 'store']);
    
    // Lesson Screens
    Route::apiResource('lesson-screens', LessonScreenController::class);
    Route::get('courses/{course}/lesson-screens', [LessonScreenController::class, 'getByCourse']);
    Route::get('courses/{course}/modules/{module}/lesson-screens', [LessonScreenController::class, 'getByCourseModule']);
    
    // Module Assessments
    Route::apiResource('module-assessments', ModuleAssessmentController::class);
    Route::get('courses/{course}/module-assessments', [ModuleAssessmentController::class, 'getByCourse']);
    Route::get('courses/{course}/modules/{module}/assessment', [ModuleAssessmentController::class, 'getByCourseModule']);
    
    // Module Assessment Progress
    Route::apiResource('module-assessment-progress', ModuleAssessmentProgressController::class);
    Route::get('users/{user}/assessment-progress', [ModuleAssessmentProgressController::class, 'getByUser']);
    Route::get('module-assessments/{assessment}/progress', [ModuleAssessmentProgressController::class, 'getByAssessment']);
    Route::get('module-progress/{moduleProgress}/assessment-progress', [ModuleAssessmentProgressController::class, 'getByModuleProgress']);
    
    // Chat functionality
    Route::prefix('chat')->group(function () {
        Route::get('courses/{course}/users/{user}/group', [ChatController::class, 'findOrCreateGroupForUser']);
        Route::get('groups/{group}', [ChatController::class, 'getGroupChatInfo']);
        Route::get('groups/{group}/messages', [ChatController::class, 'getChatMessages']);
        Route::get('groups/{group}/members', [ChatController::class, 'getGroupMembers']);
        Route::post('messages', [ChatController::class, 'sendMessage']);
    });

    // Faculty
    Route::post('faculty/assign-group', [FacultyController::class, 'assignGroup']);
    Route::post('faculty/unassign-group', [FacultyController::class, 'unassignGroup']);

    // Groups
    Route::apiResource('groups', GroupController::class);
    Route::get('groups/{group}/members', [GroupController::class, 'getGroupMembers']);

    // Group Members
    Route::post('group-members', [GroupMemberController::class, 'store']);
    Route::delete('group-members/{group_member}', [GroupMemberController::class, 'destroy']);
    Route::post('group-members/remove', [GroupMemberController::class, 'removeMember']);

    // Scores routes
    Route::prefix('scores')->group(function () {
        Route::get('/', [ScoreController::class, 'index']);
        Route::get('/{id}', [ScoreController::class, 'show']);
        Route::post('/', [ScoreController::class, 'store']);
        Route::put('/{id}', [ScoreController::class, 'update']);
        Route::delete('/{id}', [ScoreController::class, 'destroy']);
        Route::get('/faculty/{facultyId}', [ScoreController::class, 'getFacultyScores']);
        Route::get('/assessment-progress/{progressId}', [ScoreController::class, 'getAssessmentProgressScores']);
    });

    // Feedback routes
    Route::prefix('feedback')->group(function () {
        Route::get('/', [FeedbackController::class, 'index']);
        Route::get('/{id}', [FeedbackController::class, 'show']);
        Route::post('/', [FeedbackController::class, 'store']);
        Route::put('/{id}', [FeedbackController::class, 'update']);
        Route::delete('/{id}', [FeedbackController::class, 'destroy']);
        Route::get('/faculty/{facultyId}', [FeedbackController::class, 'getFacultyFeedback']);
        Route::get('/assessment-progress/{progressId}', [FeedbackController::class, 'getAssessmentProgressFeedback']);
    });
});