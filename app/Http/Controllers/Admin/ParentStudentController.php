<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentStudent;
use App\Models\User;
use App\Models\Member;
use App\Services\AuditService;
use Illuminate\Http\Request;

class ParentStudentController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function link(Request $request)
    {
        $validated = $request->validate([
            'parent_user_id' => 'required|exists:users,id',
            'member_id' => 'required|exists:members,id',
            'dojo_id' => 'required|exists:dojos,id',
        ]);

        $link = ParentStudent::create([
            'parent_user_id' => $validated['parent_user_id'],
            'member_id' => $validated['member_id'],
            'dojo_id' => $validated['dojo_id'],
            'linked_by_user_id' => auth()->id(),
            'linked_at' => now(),
        ]);

        $this->auditService->logCreate($link, $validated);

        return response()->json($link, 201);
    }

    public function unlink(ParentStudent $parentStudent)
    {
        $this->auditService->logDelete($parentStudent, $parentStudent->toArray());
        $parentStudent->delete();

        return response()->json(['message' => 'Link removed successfully']);
    }

    public function getParentStudents(User $parent)
    {
        $students = $parent->students()->with('dojo')->get();
        return response()->json($students);
    }

    public function getStudentParents(Member $member)
    {
        $parents = $member->parents()->with('dojo')->get();
        return response()->json($parents);
    }
}
