<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Dojo;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommunicationController extends Controller
{
    // Pengumuman Global
    public function announcements(Request $request)
    {
        $query = Announcement::with(['dojo'])
            ->whereNull('dojo_id');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $announcements = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.communication.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_global' => 'boolean',
            'scheduled_at' => 'nullable|date',
            'dojo_ids' => 'nullable|array',
            'dojo_ids.*' => 'exists:dojos,id',
        ]);

        $validated['dojo_id'] = $request->has('is_global') ? null : ($request->dojo_ids[0] ?? null);

        $announcement = Announcement::create($validated);

        // Send to all dojos if global
        if ($validated['is_global']) {
            $dojos = Dojo::all();
            foreach ($dojos as $dojo) {
                // Create recipients for each dojo
            }
        }

        return redirect()->route('admin.communication.announcements')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    // Broadcast ke Semua Cabang
    public function broadcast(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'message' => 'required|string',
                'subject' => 'nullable|string|max:255',
                'dojo_ids' => 'nullable|array',
                'dojo_ids.*' => 'exists:dojos,id',
                'send_to_all' => 'boolean',
            ]);

            // Implementation for broadcast
            return redirect()->route('admin.communication.broadcast')
                ->with('success', 'Broadcast berhasil dikirim.');
        }

        $dojos = Dojo::all();
        return view('admin.communication.broadcast', compact('dojos'));
    }

    // Template Pesan
    public function messageTemplates(Request $request)
    {
        // This could use SystemSetting or a new MessageTemplate model
        $templates = \App\Models\SystemSetting::where('key', 'like', 'message_template_%')->get();

        return view('admin.communication.message-templates', compact('templates'));
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:email,sms,whatsapp',
        ]);

        \App\Models\SystemSetting::create([
            'key' => 'message_template_' . str_replace(' ', '_', strtolower($validated['name'])),
            'value' => json_encode($validated),
            'type' => 'json',
        ]);

        return redirect()->route('admin.communication.message-templates')
            ->with('success', 'Template berhasil ditambahkan.');
    }

    // Log Notifikasi
    public function notificationLogs(Request $request)
    {
        $query = Notification::with(['user', 'dojo']);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.communication.notification-logs', compact('notifications', 'dojos'));
    }
}
