<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Models\Course;
use App\Models\AssessmentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Get the group for a user in a specific course.
     * If the user is not in a group, find an available group with the same learning style
     * or create a new one.
     */
    public function findOrCreateGroupForUser(Request $request, $courseId, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            $course = Course::findOrFail($courseId);

            // Check if the user is already in a group for this course
            $existingGroup = $user->groups()->where('course_id', $courseId)->first();
            if ($existingGroup) {
                return $this->getGroupChatInfo($existingGroup->group_id);
            }

            // Get user's learning style from the most recent assessment for the course
            $assessmentResult = AssessmentResult::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->latest('created_at')
                ->first();
            
            if (!$assessmentResult || !isset($assessmentResult->result['learning_style_id'])) {
                // As a fallback, check student profile
                $profile = $user->studentProfile;
                if (!$profile || !$profile->dominant_learning_style_id) {
                    return response()->json(['error' => 'User has no learning style assigned for this course.'], 404);
                }
                $learningStyleId = $profile->dominant_learning_style_id;
            } else {
                $learningStyleId = $assessmentResult->result['learning_style_id'];
            }

            $groupToJoin = null;

            // Find an existing group with the same learning style and less than 5 members
            $potentialGroups = Group::where('course_id', $courseId)
                ->whereHas('groupMembers.user.studentProfile', function ($query) use ($learningStyleId) {
                    $query->where('dominant_learning_style_id', $learningStyleId);
                })
                ->withCount('groupMembers')
                ->get();

            foreach ($potentialGroups as $group) {
                if ($group->group_members_count < 5) {
                    $groupToJoin = $group;
                    break;
                }
            }

            if ($groupToJoin) {
                // Add user to the found group
                GroupMember::create([
                    'group_id' => $groupToJoin->group_id,
                    'user_id' => $userId,
                ]);
                return $this->getGroupChatInfo($groupToJoin->group_id);
            } else {
                // Create a new group
                $newGroup = Group::create([
                    'course_id' => $courseId,
                    'group_name' => $course->name . ' - Group ' . (count($potentialGroups) + 1),
                ]);

                // Add user to the new group
                GroupMember::create([
                    'group_id' => $newGroup->group_id,
                    'user_id' => $userId,
                ]);
                return $this->getGroupChatInfo($newGroup->group_id);
            }
        } catch (\Exception $e) {
            Log::error('Error finding or creating group: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to find or create a group for the user.'], 500);
        }
    }

    public function getGroupChatInfo($groupId)
    {
        try {
            $group = Group::with('groupMembers.user')->findOrFail($groupId);
            $messages = ChatMessage::where('group_id', $groupId)
                ->with(['user:user_id,first_name,last_name'])
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'data' => [
                    'group' => $group,
                    'messages' => $messages,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get group chat info: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get messages for a specific group
     */
    public function getChatMessages($groupId)
    {
        try {
            $messages = ChatMessage::where('group_id', $groupId)
                ->with(['user:user_id,first_name,last_name'])
                ->orderBy('created_at', 'asc')
                ->get();
            
            return response()->json([
                'data' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get chat messages: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send a new message to a group
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,group_id',
            'user_id' => 'required|exists:users,user_id',
            'message' => 'required|string|max:1000'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Check if user is a member of the group
        $isMember = GroupMember::where('group_id', $request->group_id)
            ->where('user_id', $request->user_id)
            ->exists();

        if (!$isMember) {
            return response()->json(['error' => 'User is not a member of this group.'], 403);
        }
        
        try {
            $message = ChatMessage::create([
                'group_id' => $request->group_id,
                'user_id' => $request->user_id,
                'message' => $request->message
            ]);
            
            $message->load('user:user_id,first_name,last_name');
            
            return response()->json(['data' => $message], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send message: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get members of a specific group
     */
    public function getGroupMembers($groupId)
    {
        try {
            $group = Group::with('groupMembers.user:user_id,first_name,last_name')->findOrFail($groupId);
            
            return response()->json([
                'data' => $group->groupMembers->pluck('user')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get group members: ' . $e->getMessage()
            ], 500);
        }
    }
}
