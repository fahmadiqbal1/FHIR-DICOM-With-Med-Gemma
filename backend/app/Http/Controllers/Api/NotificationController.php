<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\RoleHelper;

class NotificationController extends Controller
{
    /**
     * Get notifications for current user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        $unreadOnly = $request->boolean('unread_only');

        $query = Notification::forUser($user->id)
            ->orderBy('created_at', 'desc');

        if ($unreadOnly) {
            $query->unread();
        }

        $notifications = $query->limit($limit)->get();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'priority' => $notification->priority,
                    'is_read' => $notification->isRead(),
                    'time_ago' => $notification->getTimeAgo(),
                    'created_at' => $notification->created_at->toISOString()
                ];
            }),
            'unread_count' => Notification::forUser($user->id)->unread()->count()
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        // First check if notification exists
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found'
            ], 404);
        }
        
        // Check if notification belongs to current user
        if ($notification->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
            'notification' => $notification
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get notification counts by type for current user
     */
    public function counts()
    {
        $user = Auth::user();
        
        $counts = [
            'total' => Notification::forUser($user->id)->count(),
            'unread' => Notification::forUser($user->id)->unread()->count(),
            'urgent' => Notification::forUser($user->id)->unread()->where('priority', 'urgent')->count(),
        ];

        // Add role-specific counts
        if (RoleHelper::isLabTechnician($user)) {
            $counts['lab_requests'] = Notification::forUser($user->id)
                ->unread()
                ->where('type', 'lab_request')
                ->count();
        }

        if (RoleHelper::isRadiologist($user)) {
            $counts['imaging_requests'] = Notification::forUser($user->id)
                ->unread()
                ->where('type', 'imaging_request')
                ->count();
        }

        $counts['work_orders'] = Notification::forUser($user->id)
            ->unread()
            ->where('type', 'work_order')
            ->count();

        return response()->json($counts);
    }

    /**
     * Create a test notification (for development)
     */
    public function createTest()
    {
        $user = Auth::user();
        
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'test',
            'title' => 'Test Notification',
            'message' => 'This is a test notification created at ' . now()->format('Y-m-d H:i:s'),
            'priority' => 'normal',
            'data' => [
                'test' => true,
                'timestamp' => now()->toISOString()
            ]
        ]);

        return response()->json([
            'message' => 'Test notification created',
            'notification' => $notification
        ]);
    }

    /**
     * Get notification counts by type for current user
     */
    public function getCounts()
    {
        return $this->counts();
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        // First check if notification exists
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found'
            ], 404);
        }
        
        // Check if notification belongs to current user
        if ($notification->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted'
        ]);
    }
}
