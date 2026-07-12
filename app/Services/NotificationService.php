<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function unreadCount(?int $userId = null): int
    {
        $userId = $userId ?? Auth::id();
        if (!$userId) return 0;
        return AppNotification::forUser($userId)->unread()->count();
    }

    public function getForUser(?int $userId = null, int $limit = 10)
    {
        $userId = $userId ?? Auth::id();
        return AppNotification::forUser($userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function markAllRead(?int $userId = null): void
    {
        $userId = $userId ?? Auth::id();
        AppNotification::forUser($userId)->unread()->update(['read_at' => now()]);
    }

    public function markRead(int $id): void
    {
        $notif = AppNotification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $notif->markAsRead();
    }

    public function create(int $userId, string $type, string $title, string $message, array $extra = []): AppNotification
    {
        return AppNotification::create(array_merge([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
        ], $extra));
    }
}
