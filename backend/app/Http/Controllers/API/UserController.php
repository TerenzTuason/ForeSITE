<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::with('role')->get();
        return response()->json(['data' => $users], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,role_id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $userData = $request->all();
        $userData['password'] = Hash::make($request->password);
        
        $user = User::create($userData);
        
        return response()->json(['data' => $user], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $user = User::with('role', 'studentProfile.learningStyle')->find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $user], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'role_id' => 'sometimes|required|exists:roles,role_id',
            'email' => 'sometimes|required|email|unique:users,email,' . $id . ',user_id',
            'password' => 'sometimes|required|min:8',
            'first_name' => 'sometimes|required|string|max:50',
            'last_name' => 'sometimes|required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $userData = $request->all();
        
        if ($request->has('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        return response()->json(['data' => $user], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Delete related records first
        if ($user->studentProfile) {
            $user->studentProfile->delete();
        }
        
        $user->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
