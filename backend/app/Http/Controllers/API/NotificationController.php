<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->user_id)
            ->with(['user', 'role', 'faculty'])
            ->orderBy('created_at', 'desc')->get();
            
        return response()->json(['data' => $notifications]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'message' => 'required|string',
            'role_id' => 'required|exists:roles,role_id',
            'faculty_id' => 'nullable|exists:users,user_id',
        ]);

        $notification = Notification::create($request->all());
        $notification->load(['user', 'role', 'faculty']);

        return response()->json(['data' => $notification], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Notification $notification)
    {
        $notification->load(['user', 'role', 'faculty']);
        return response()->json(['data' => $notification]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'is_read' => 'required|boolean',
        ]);

        $notification->update($request->all());
        $notification->load(['user', 'role', 'faculty']);

        return response()->json(['data' => $notification]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->json(null, 204);
    }

    /**
     * Mark all notifications for a user as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        Notification::where('user_id', $user->user_id)->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }

    /**
     * Get the number of unread notifications for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount(Request $request)
    {
        $user = $request->user();
        $count = Notification::where('user_id', $user->user_id)->where('is_read', false)->count();

        return response()->json(['data' => ['count' => $count]]);
    }

    /**
     * Get notifications for a specific faculty member.
     *
     * @param  \App\Models\User  $faculty
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationsByFaculty(User $faculty)
    {
        // Ensure the user is a faculty member
        if ($faculty->role->role_name !== 'faculty') {
            return response()->json(['message' => 'User is not a faculty member.'], 403);
        }

        $notifications = Notification::where('faculty_id', $faculty->user_id)
            ->with(['user', 'role', 'faculty'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $notifications]);
    }

     /**
     * Get notifications for a specific role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationsByRole(Role $role)
    {
        $notifications = Notification::where('role_id', $role->role_id)
            ->with(['user', 'role', 'faculty'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $notifications]);
    }
} 