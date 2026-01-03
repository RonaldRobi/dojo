<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\AnnouncementRecipient;
use App\Models\Dojo;
use App\Services\NotificationService;

class AnnouncementService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function publish(Announcement $announcement): void
    {
        $announcement->update(['is_published' => true]);

        // Create recipients based on target audience
        $userIds = $this->getTargetUserIds($announcement);
        
        foreach ($userIds as $userId) {
            AnnouncementRecipient::create([
                'announcement_id' => $announcement->id,
                'user_id' => $userId,
                'notification_sent_at' => now(),
            ]);
        }

        // Send notifications
        $this->notificationService->notifyAnnouncement($announcement, $userIds);
    }

    protected function getTargetUserIds(Announcement $announcement): array
    {
        $dojo = Dojo::find($announcement->dojo_id);
        
        $query = \App\Models\User::whereHas('roles', function($q) use ($announcement, $dojo) {
            $q->wherePivot('dojo_id', $dojo->id);
            
            switch ($announcement->target_audience) {
                case 'students':
                    $q->where('name', 'student');
                    break;
                case 'parents':
                    $q->where('name', 'parent');
                    break;
                case 'instructors':
                    $q->where('name', 'coach');
                    break;
                case 'all':
                default:
                    // All roles
                    break;
            }
        });

        return $query->pluck('id')->toArray();
    }
}

