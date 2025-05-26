<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\LearningStyle;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Get chat rooms for a specific learning style
     */
    public function getChatRoomByLearningStyle($styleId)
    {
        try {
            $chatRoom = ChatRoom::where('learning_style_id', $styleId)->first();
            
            if (!$chatRoom) {
                // If room doesn't exist, create it
                $learningStyle = LearningStyle::find($styleId);
                if (!$learningStyle) {
                    return response()->json([
                        'error' => 'Learning style not found'
                    ], 404);
                }
                
                $chatRoom = ChatRoom::create([
                    'learning_style_id' => $styleId,
                    'room_name' => $learningStyle->style_name . ' Learning Chat'
                ]);
            }
            
            return response()->json([
                'data' => $chatRoom
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get chat room: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get chat room for a specific user based on their learning style
     */
    public function getUserChatRoom($userId)
    {
        try {
            // Get the user's learning style from their profile
            $profile = StudentProfile::where('user_id', $userId)->first();
            
            if (!$profile || !$profile->dominant_learning_style_id) {
                return response()->json([
                    'error' => 'No learning style found for this user'
                ], 404);
            }
            
            // Get or create the chat room for this learning style
            $chatRoom = ChatRoom::where('learning_style_id', $profile->dominant_learning_style_id)->first();
            
            if (!$chatRoom) {
                $learningStyle = LearningStyle::find($profile->dominant_learning_style_id);
                $chatRoom = ChatRoom::create([
                    'learning_style_id' => $profile->dominant_learning_style_id,
                    'room_name' => $learningStyle->style_name . ' Learning Chat'
                ]);
            }
            
            return response()->json([
                'data' => $chatRoom
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get user chat room: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get messages for a specific chat room
     */
    public function getChatMessages($roomId)
    {
        try {
            $messages = ChatMessage::where('room_id', $roomId)
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
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'room_id' => 'required|exists:chat_rooms,room_id',
                'user_id' => 'required|exists:users,user_id',
                'message' => 'required|string|max:1000'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ], 422);
            }
            
            // Create the message
            $message = ChatMessage::create([
                'room_id' => $request->room_id,
                'user_id' => $request->user_id,
                'message' => $request->message
            ]);
            
            // Load the user data
            $message->load('user:user_id,first_name,last_name');
            
            return response()->json([
                'data' => $message
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get users with the same learning style
     */
    public function getUsersWithSameLearningStyle($styleId)
    {
        try {
            $users = User::whereHas('studentProfile', function($query) use ($styleId) {
                $query->where('dominant_learning_style_id', $styleId);
            })->get(['user_id', 'first_name', 'last_name']);
            
            return response()->json([
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get users: ' . $e->getMessage()
            ], 500);
        }
    }
}
