<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GroupMember;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class GroupMemberController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,group_id',
            'user_id' => 'required|exists:users,user_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Check if user is already in the group
        $existingMember = GroupMember::where('group_id', $request->group_id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existingMember) {
            return response()->json(['error' => 'User is already in this group'], Response::HTTP_CONFLICT);
        }

        $groupMember = GroupMember::create($request->all());
        return response()->json(['data' => $groupMember], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $groupMember = GroupMember::find($id);
        
        if (!$groupMember) {
            return response()->json(['error' => 'Group member not found'], Response::HTTP_NOT_FOUND);
        }
        
        $groupMember->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove a user from a group.
     */
    public function removeMember(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,group_id',
            'user_id' => 'required|exists:users,user_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $groupMember = GroupMember::where('group_id', $request->group_id)
            ->where('user_id', $request->user_id)
            ->first();

        if (!$groupMember) {
            return response()->json(['error' => 'Group member not found'], Response::HTTP_NOT_FOUND);
        }

        $groupMember->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
} 