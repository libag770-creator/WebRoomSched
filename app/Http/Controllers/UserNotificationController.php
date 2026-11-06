<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    public function latest(): JsonResponse
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->latest()
            ->limit(8)
            ->get();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? 'You have a new notification.',
                    'read' => $notification->read_at !== null,
                    'created_at' => $notification->created_at?->toISOString(),
                    'url' => route('notifications.open', $notification->id),
                ];
            })->values(),
        ]);
    }

    public function open(string $notificationId): RedirectResponse
    {
        $notification = Auth::user()
            ->notifications()
            ->whereKey($notificationId)
            ->firstOrFail();

        $notification->markAsRead();

        if (($notification->data['type'] ?? '') === 'schedule_override') {
            return redirect()->route('faculty.myschedules');
        }

        if (($notification->data['type'] ?? '') === 'schedule_merge') {
            $event = $notification->data['event'] ?? '';
            $anchor = in_array($event, ['requested', 'cancelled'], true)
                ? '#merge-requests'
                : '#my-merge-requests';

            return redirect()->to(route('chair.dashboard') . $anchor);
        }

        if (($notification->data['type'] ?? '') === 'room_swap_chair') {
            abort_unless(Auth::user()->role === 'chair', 403);
            return redirect()->route('chair.modifyschedule');
        }

        $event = $notification->data['event'] ?? '';
        $panel = $event === 'requested'
            ? '#receivedSwapPanel'
            : '#mySwapPanel';

        return redirect()->to(route('faculty.dashboard') . $panel);
    }
}
