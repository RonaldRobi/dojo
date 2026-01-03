<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ClassMessage;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request, ClassSchedule $classSchedule)
    {
        $messages = ClassMessage::where('class_schedule_id', $classSchedule->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function store(Request $request, ClassSchedule $classSchedule)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'attachment_path' => 'nullable|string',
        ]);

        $message = ClassMessage::create([
            'class_schedule_id' => $classSchedule->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'attachment_path' => $validated['attachment_path'] ?? null,
        ]);

        return response()->json($message, 201);
    }

    public function destroy(ClassMessage $classMessage)
    {
        // Only allow deleting own messages (or admin/owner)
        if ($classMessage->user_id !== auth()->id() && !hasRole(['super_admin', 'owner'])) {
            abort(403, 'You can only delete your own messages');
        }

        $classMessage->delete();
        return response()->json(['message' => 'Message deleted successfully']);
    }
}
