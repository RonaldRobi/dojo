<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\Dojo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MemberManagementController extends Controller
{
    // Data Seluruh Siswa
    public function index(Request $request)
    {
        $query = Member::with(['dojo', 'user']);

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->orderBy('name')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.members.index', compact('members', 'dojos'));
    }

    // Riwayat Kehadiran Global
    public function attendanceGlobal(Request $request)
    {
        $query = Attendance::with(['member.dojo', 'classSchedule']);

        if ($request->has('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->has('dojo_id')) {
            $query->whereHas('member', function($q) use ($request) {
                $q->where('dojo_id', $request->dojo_id);
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('attendance_date', '>=', $request->date_from);
        } else {
            // Default to last 30 days
            $query->whereDate('attendance_date', '>=', Carbon::now()->subDays(30));
        }

        if ($request->has('date_to')) {
            $query->whereDate('attendance_date', '<=', $request->date_to);
        }

        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(20);
        $members = Member::with('dojo')->get();
        $dojos = Dojo::all();

        return view('admin.members.attendance-global', compact('attendances', 'members', 'dojos'));
    }

    // Status Keaktifan Siswa
    public function status(Request $request)
    {
        $query = Member::with(['dojo']);

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
    }

        // Group by status
        $stats = [
            'active' => Member::where('status', 'active')->count(),
            'inactive' => Member::where('status', 'inactive')->count(),
            'leave' => Member::where('status', 'leave')->count(),
            'total' => Member::count(),
        ];

        $members = $query->orderBy('name')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.members.status', compact('members', 'dojos', 'stats'));
    }

    // Medical Notes & Waiver
    public function medicalNotes(Request $request)
    {
        $query = Member::with(['dojo'])
            ->where(function($q) {
                $q->whereNotNull('medical_notes')
                  ->orWhereNotNull('waiver_signed_at');
            });

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('has_waiver')) {
            if ($request->has_waiver == 'yes') {
                $query->whereNotNull('waiver_signed_at');
            } else {
                $query->whereNull('waiver_signed_at');
            }
        }

        $members = $query->orderBy('name')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.members.medical-notes', compact('members', 'dojos'));
    }
}
