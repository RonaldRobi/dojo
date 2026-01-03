<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Announcement;

class NotificationService
{
    public function createNotification(User $user, array $data): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'link' => $data['link'] ?? null,
            'priority' => $data['priority'] ?? 'normal',
        ]);
    }

    public function markAsRead(Notification $notification): void
    {
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }
    }

    public function markAllAsRead(User $user): void
    {
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function notifyAnnouncement(Announcement $announcement, array $userIds): void
    {
        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => 'announcement',
                'title' => $announcement->title,
                'message' => substr($announcement->content, 0, 200),
                'link' => route('announcements.show', $announcement->id),
                'priority' => $announcement->priority,
            ]);
        }
    }
}

