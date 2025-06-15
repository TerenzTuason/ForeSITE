<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Log the request data
            Log::info('Registration attempt with data:', $request->all());
            
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed during registration:', ['errors' => $validator->errors()]);
                return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

            // Get default student role_id (1)
            $userData = $request->all();
            $userData['role_id'] = 1; // Assuming 1 is student role
            $userData['is_active'] = true;
            $userData['password'] = Hash::make($request->password);
            
            // Log user data being created (exclude password for security)
            $loggableData = $userData;
            unset($loggableData['password']);
            Log::info('Creating user with data:', $loggableData);
            
            // Create the user
            $user = User::create($userData);
            
            // Log success
            Log::info('User created successfully with ID: ' . $user->user_id);
            
            return response()->json([
                'data' => [
                    'user' => $user,
                    'message' => 'Registration successful'
                ]
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error during user registration: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'Registration failed: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Login user and return user details
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Log login attempt (exclude password)
            $loggableData = $request->all();
            unset($loggableData['password']);
            Log::info('Login attempt:', $loggableData);
            
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed during login:', ['errors' => $validator->errors()]);
                return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

            // Check if user exists
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                Log::warning('Login failed: User not found', ['email' => $request->email]);
                return response()->json([
                    'error' => 'Invalid credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }
            
            if (!Hash::check($request->password, $user->password)) {
                Log::warning('Login failed: Invalid password', ['user_id' => $user->user_id]);
                return response()->json([
                    'error' => 'Invalid credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Update last login time
            $user->last_login = now();
            $user->save();
            
            Log::info('User logged in successfully', ['user_id' => $user->user_id]);

            return response()->json([
                'data' => [
                    'user' => $user->load('role', 'studentProfile.learningStyle'),
                    'message' => 'Login successful'
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error during user login: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->except(['password'])
            ]);
            
            return response()->json([
                'error' => 'Login failed: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
} 