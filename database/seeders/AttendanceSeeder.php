<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use App\Models\ClassEnrollment;
use App\Models\Member;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Get all active schedules
        $schedules = ClassSchedule::where('is_active', true)->with('dojoClass')->get();

        if ($schedules->isEmpty()) {
            $this->command->warn('No class schedules found. Please run ClassSeeder first.');
            return;
        }

        // Generate attendance for the last 30 days
        for ($dayOffset = 0; $dayOffset < 30; $dayOffset++) {
            $attendanceDate = Carbon::now()->subDays($dayOffset);
            $dayOfWeek = $attendanceDate->dayOfWeek;

            // Find schedules for this day of week
            $daySchedules = $schedules->where('day_of_week', $dayOfWeek);

            foreach ($daySchedules as $schedule) {
                // Get enrolled members for this schedule
                $enrollments = ClassEnrollment::where('class_schedule_id', $schedule->id)
                    ->where('status', 'active')
                    ->with('member')
                    ->get();

                foreach ($enrollments as $enrollment) {
                    // Randomly mark attendance (80% attendance rate)
                    if (rand(1, 10) <= 8) {
                        $checkedInAt = $schedule->start_time->copy()->setDateFrom($attendanceDate);
                        
                        // Random variation in check-in time (up to 15 minutes late)
                        if (rand(1, 10) > 8) {
                            $checkedInAt->addMinutes(rand(1, 15));
                        }

                        Attendance::updateOrCreate(
                            [
                                'member_id' => $enrollment->member_id,
                                'class_schedule_id' => $schedule->id,
                                'attendance_date' => $attendanceDate,
                            ],
                            [
                                'status' => 'present',
                                'checked_in_at' => $checkedInAt,
                                'checked_in_method' => ['qr_code', 'manual', 'qr_code', 'manual', 'qr_code'][rand(0, 4)],
                                'notes' => null,
                            ]
                        );
                    } else {
                        // Mark as absent (optional - you might not want to create absent records)
                        // Attendance::updateOrCreate([...], ['status' => 'absent']);
                    }
                }
            }
        }

        $this->command->info('Attendance records seeded successfully!');
    }
}

