<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Dojo;
use App\Models\InstructorCertification;
use App\Models\InstructorTeachingLog;
use App\Models\InstructorPerformanceReview;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InstructorManagementController extends Controller
{
    // Data Seluruh Coach
    public function index(Request $request)
    {
        $query = Instructor::with(['dojo', 'user']);

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $instructors = $query->orderBy('name')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.instructors.index', compact('instructors', 'dojos'));
    }

    // Riwayat Mengajar Coach
    public function history(Request $request)
    {
        $query = InstructorTeachingLog::with(['instructor.dojo', 'classSchedule.dojoclass']);

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('dojo_id')) {
            $query->whereHas('instructor', function($q) use ($request) {
                $q->where('dojo_id', $request->dojo_id);
            });
        }

        $logs = $query->orderBy('teaching_date', 'desc')->paginate(20);
        $instructors = Instructor::with('dojo')->get();
        $dojos = Dojo::all();

        return view('admin.instructors.history', compact('logs', 'instructors', 'dojos'));
    }

    // Sertifikasi Coach
    public function certifications(Request $request)
    {
        $query = InstructorCertification::with(['instructor.dojo']);

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('dojo_id')) {
            $query->whereHas('instructor', function($q) use ($request) {
                $q->where('dojo_id', $request->dojo_id);
            });
        }

        $certifications = $query->orderBy('issued_date', 'desc')->paginate(20);
        $instructors = Instructor::with('dojo')->get();
        $dojos = Dojo::all();

        return view('admin.instructors.certifications', compact('certifications', 'instructors', 'dojos'));
    }

    public function storeCertification(Request $request)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'certification_name' => 'required|string|max:255',
            'issued_by' => 'nullable|string|max:255',
            'issued_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issued_date',
            'certificate_document_path' => 'nullable|string',
        ]);

        InstructorCertification::create($validated);

        return redirect()->route('admin.instructors.certifications')
            ->with('success', 'Sertifikasi berhasil ditambahkan.');
    }

    // Masa Berlaku Sertifikasi
    public function certificationExpiry(Request $request)
    {
        $days = $request->get('days', 30);
        $query = InstructorCertification::with(['instructor.dojo'])
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', Carbon::now())
            ->where('expiry_date', '<=', Carbon::now()->addDays($days));

        $expiringCertifications = $query->orderBy('expiry_date', 'asc')->paginate(20);

        return view('admin.instructors.certification-expiry', compact('expiringCertifications', 'days'));
    }

    // Performance Coach (Global)
    public function performance(Request $request)
    {
        $query = InstructorPerformanceReview::with(['instructor.dojo', 'reviewedBy']);

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('dojo_id')) {
            $query->whereHas('instructor', function($q) use ($request) {
                $q->where('dojo_id', $request->dojo_id);
            });
        }

        $reviews = $query->orderBy('review_date', 'desc')->paginate(20);
        $instructors = Instructor::with('dojo')->get();
        $dojos = Dojo::all();

        return view('admin.instructors.performance', compact('reviews', 'instructors', 'dojos'));
    }
}
