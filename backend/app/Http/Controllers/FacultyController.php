<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\FacultyAssignedGroup;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /**
     * Assign a faculty member to a group.
     */
    public function assignGroup(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:users,user_id',
            'group_id' => 'required|exists:groups,group_id',
        ]);

        $faculty = User::with('role')->find($request->faculty_id);

        if ($faculty->role->role_name !== 'faculty') {
            return response()->json(['error' => 'Only faculty members can be assigned to groups.'], 403);
        }

        try {
            FacultyAssignedGroup::create([
                'faculty_id' => $request->faculty_id,
                'group_id' => $request->group_id,
            ]);

            return response()->json(['message' => 'Faculty member assigned to group successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to assign faculty to group.'], 500);
        }
    }

    /**
     * Unassign a faculty member from a group.
     */
    public function unassignGroup(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:users,user_id',
            'group_id' => 'required|exists:groups,group_id',
        ]);

        try {
            $deleted = FacultyAssignedGroup::where('faculty_id', $request->faculty_id)
                ->where('group_id', $request->group_id)
                ->delete();

            if ($deleted) {
                return response()->json(['message' => 'Faculty member unassigned from group successfully.']);
            }

            return response()->json(['error' => 'Assignment not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to unassign faculty from group.'], 500);
        }
    }
} 