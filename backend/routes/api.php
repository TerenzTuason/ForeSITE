<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LearningStyleController;
use App\Http\Controllers\Api\AssessmentResultController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StudentProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\ModuleContentController;
use App\Http\Controllers\Api\ModuleProgressController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\EnrollmentController;
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
    
    // Module Content
    Route::apiResource('modules.contents', ModuleContentController::class)->shallow();
    Route::apiResource('module-progress.contents', ModuleContentController::class)->shallow();
    
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
});
