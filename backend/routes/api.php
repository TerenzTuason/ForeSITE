<?php

 use App\Http\Controllers\Api\LearningStyleController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StudentProfileController;
use App\Http\Controllers\Api\UserController;
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
    // Roles routes
    Route::apiResource('roles', RoleController::class);
    
    // Users routes
    Route::apiResource('users', UserController::class);
    
    // Learning styles routes
    Route::apiResource('learning-styles', LearningStyleController::class);
    
    // Student profiles routes
    Route::apiResource('student-profiles', StudentProfileController::class);
});
