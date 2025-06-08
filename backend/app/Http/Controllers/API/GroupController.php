<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Course;
use App\Models\GroupMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $groups = Group::with('course', 'groupMembers.user');

        if ($request->has('course_id')) {
            $groups->where('course_id', $request->course_id);
        }

        return response()->json(['data' => $groups->get()], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,course_id',
            'group_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $group = Group::create($request->all());
        return response()->json(['data' => $group], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $group = Group::with('course', 'groupMembers.user')->find($id);
        
        if (!$group) {
            return response()->json(['error' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $group], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $group = Group::find($id);
        
        if (!$group) {
            return response()->json(['error' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'group_name' => 'sometimes|required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $group->update($request->all());
        return response()->json(['data' => $group], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $group = Group::find($id);
        
        if (!$group) {
            return response()->json(['error' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
        
        if ($group->groupMembers()->count() > 0) {
            return response()->json(['error' => 'Cannot delete group with members'], Response::HTTP_BAD_REQUEST);
        }
        
        $group->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get all members of a group.
     */
    public function getGroupMembers(string $id): JsonResponse
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(['error' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }

        $members = GroupMember::with('user')->where('group_id', $id)->get();

        return response()->json(['data' => $members], Response::HTTP_OK);
    }
} 