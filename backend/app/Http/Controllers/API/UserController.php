<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->paginate(15);
        return UserResource::collection($users)
            ->additional(['message' => 'Users retrieved successfully']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'role_id' => 'required|exists:roles,role_id',
            'is_active' => 'boolean'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role_id' => $request->role_id,
            'is_active' => $request->is_active ?? true
        ]);

        return (new UserResource($user->load('role')))
            ->additional(['message' => 'User created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('role')->findOrFail($id);
        
        return (new UserResource($user))
            ->additional(['message' => 'User retrieved successfully']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'password' => 'sometimes|min:8',
            'first_name' => 'sometimes|string|max:50',
            'last_name' => 'sometimes|string|max:50',
            'role_id' => 'sometimes|exists:roles,role_id',
            'is_active' => 'sometimes|boolean'
        ]);

        // Only update password if it's provided
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        
        // Update other fields if they're provided
        $user->fill($request->except('password'));
        $user->save();

        return (new UserResource($user->load('role')))
            ->additional(['message' => 'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
