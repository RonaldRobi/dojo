<?php

namespace App\Services;

use App\Models\ClassSchedule;
use App\Models\ClassEnrollment;
use App\Models\DojoClass;
use Carbon\Carbon;

class ScheduleService
{
    public function checkCapacity(ClassSchedule $schedule): array
    {
        $class = $schedule->dojoClass;
        $enrolledCount = $schedule->enrollments()->where('status', 'active')->count();
        $availableSlots = $class->capacity - $enrolledCount;

        return [
            'capacity' => $class->capacity,
            'enrolled' => $enrolledCount,
            'available' => max(0, $availableSlots),
            'is_full' => $enrolledCount >= $class->capacity,
        ];
    }

    public function checkConflict(ClassSchedule $schedule, int $memberId): bool
    {
        // Check if member already enrolled in another class at the same time
        $conflicting = ClassEnrollment::where('member_id', $memberId)
            ->where('status', 'active')
            ->whereHas('classSchedule', function($q) use ($schedule) {
                $q->where('day_of_week', $schedule->day_of_week)
                  ->where(function($query) use ($schedule) {
                      $query->whereBetween('start_time', [$schedule->start_time, $schedule->end_time])
                            ->orWhereBetween('end_time', [$schedule->start_time, $schedule->end_time])
                            ->orWhere(function($q) use ($schedule) {
                                $q->where('start_time', '<=', $schedule->start_time)
                                  ->where('end_time', '>=', $schedule->end_time);
                            });
                  });
            })
            ->exists();

        return $conflicting;
    }

    public function enrollMember(ClassSchedule $schedule, int $memberId): ClassEnrollment
    {
        $capacity = $this->checkCapacity($schedule);
        
        if ($capacity['is_full']) {
            throw new \Exception('Class is full');
        }

        if ($this->checkConflict($schedule, $memberId)) {
            throw new \Exception('Schedule conflict detected');
        }

        return ClassEnrollment::create([
            'member_id' => $memberId,
            'class_schedule_id' => $schedule->id,
            'enrolled_at' => now(),
            'status' => 'active',
        ]);
    }
}

