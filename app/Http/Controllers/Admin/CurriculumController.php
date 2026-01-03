<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterData;
use App\Models\Rank;
use App\Models\Curriculum;
use App\Models\RankRequirement;
use App\Models\Dojo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CurriculumController extends Controller
{
    // Master Aliran / Style
    public function styles(Request $request)
    {
        $query = MasterData::ofType('style')->with('dojo');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        } else {
            // Show global styles by default
            $query->global();
        }

        $styles = $query->orderBy('order')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.curriculum.styles', compact('styles', 'dojos'));
    }

    public function storeStyle(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'dojo_id' => 'nullable|exists:dojos,id',
            'is_active' => 'boolean',
            'metadata' => 'nullable|array',
        ]);

        $validated['type'] = 'style';
        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $validated['is_active'] ?? true;

        MasterData::create($validated);

        return redirect()->route('admin.curriculum.styles')
            ->with('success', 'Style berhasil ditambahkan.');
    }

    public function updateStyle(Request $request, MasterData $style)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'dojo_id' => 'nullable|exists:dojos,id',
            'is_active' => 'boolean',
            'metadata' => 'nullable|array',
        ]);

        $style->update($validated);

        return redirect()->route('admin.curriculum.styles')
            ->with('success', 'Style berhasil diupdate.');
    }

    public function destroyStyle(MasterData $style)
    {
        $style->delete();
        return redirect()->route('admin.curriculum.styles')
            ->with('success', 'Style berhasil dihapus.');
    }

    // Master Level
    public function levels(Request $request)
    {
        $query = MasterData::ofType('level')->with('dojo');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        } else {
            $query->global();
        }

        $levels = $query->orderBy('order')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.curriculum.levels', compact('levels', 'dojos'));
    }

    public function storeLevel(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'order' => 'required|integer|min:0',
            'dojo_id' => 'nullable|exists:dojos,id',
            'is_active' => 'boolean',
            'metadata' => 'nullable|array',
        ]);

        $validated['type'] = 'level';
        $validated['is_active'] = $validated['is_active'] ?? true;

        MasterData::create($validated);

        return redirect()->route('admin.curriculum.levels')
            ->with('success', 'Level berhasil ditambahkan.');
    }

    public function updateLevel(Request $request, MasterData $level)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'order' => 'required|integer|min:0',
            'dojo_id' => 'nullable|exists:dojos,id',
            'is_active' => 'boolean',
            'metadata' => 'nullable|array',
        ]);

        $level->update($validated);

        return redirect()->route('admin.curriculum.levels')
            ->with('success', 'Level berhasil diupdate.');
    }

    public function destroyLevel(MasterData $level)
    {
        $level->delete();
        return redirect()->route('admin.curriculum.levels')
            ->with('success', 'Level berhasil dihapus.');
    }

    // Master Sabuk (Belt)
    public function belts(Request $request)
    {
        $query = MasterData::ofType('belt')->with('dojo');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        } else {
            $query->global();
        }

        $belts = $query->orderBy('order')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.curriculum.belts', compact('belts', 'dojos'));
    }

    public function storeBelt(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'order' => 'required|integer|min:0',
            'dojo_id' => 'nullable|exists:dojos,id',
            'is_active' => 'boolean',
            'metadata' => 'nullable|array',
            'metadata.color' => 'nullable|string|max:50',
        ]);

        $validated['type'] = 'belt';
        $validated['is_active'] = $validated['is_active'] ?? true;

        MasterData::create($validated);

        return redirect()->route('admin.curriculum.belts')
            ->with('success', 'Sabuk berhasil ditambahkan.');
    }

    public function updateBelt(Request $request, MasterData $belt)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'order' => 'required|integer|min:0',
            'dojo_id' => 'nullable|exists:dojos,id',
            'is_active' => 'boolean',
            'metadata' => 'nullable|array',
            'metadata.color' => 'nullable|string|max:50',
        ]);

        $belt->update($validated);

        return redirect()->route('admin.curriculum.belts')
            ->with('success', 'Sabuk berhasil diupdate.');
    }

    public function destroyBelt(MasterData $belt)
    {
        $belt->delete();
        return redirect()->route('admin.curriculum.belts')
            ->with('success', 'Sabuk berhasil dihapus.');
    }

    // Kurikulum per Level
    public function perLevel(Request $request)
    {
        $query = Curriculum::with(['rank.dojo']);

        if ($request->has('dojo_id')) {
            $query->whereHas('rank', function($q) use ($request) {
                $q->where('dojo_id', $request->dojo_id);
            });
        }

        if ($request->has('rank_id')) {
            $query->where('rank_id', $request->rank_id);
        }

        $curriculums = $query->orderBy('order')->paginate(20);
        $dojos = Dojo::with('ranks')->get();
        $ranks = Rank::with('dojo')->orderBy('level')->get();

        return view('admin.curriculum.per-level', compact('curriculums', 'dojos', 'ranks'));
    }

    public function storeCurriculum(Request $request)
    {
        $validated = $request->validate([
            'rank_id' => 'required|exists:ranks,id',
            'skill_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_required' => 'boolean',
        ]);

        $validated['is_required'] = $validated['is_required'] ?? true;

        Curriculum::create($validated);

        return redirect()->route('admin.curriculum.per-level')
            ->with('success', 'Kurikulum berhasil ditambahkan.');
    }

    public function updateCurriculum(Request $request, Curriculum $curriculum)
    {
        $validated = $request->validate([
            'rank_id' => 'required|exists:ranks,id',
            'skill_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_required' => 'boolean',
        ]);

        $curriculum->update($validated);

        return redirect()->route('admin.curriculum.per-level')
            ->with('success', 'Kurikulum berhasil diupdate.');
    }

    public function destroyCurriculum(Curriculum $curriculum)
    {
        $curriculum->delete();
        return redirect()->route('admin.curriculum.per-level')
            ->with('success', 'Kurikulum berhasil dihapus.');
    }

    // Syarat Kenaikan Tingkat
    public function promotionRequirements(Request $request)
    {
        $query = RankRequirement::with(['rank.dojo']);

        if ($request->has('dojo_id')) {
            $query->whereHas('rank', function($q) use ($request) {
                $q->where('dojo_id', $request->dojo_id);
            });
        }

        if ($request->has('rank_id')) {
            $query->where('rank_id', $request->rank_id);
        }

        $requirements = $query->paginate(20);
        $dojos = Dojo::with('ranks')->get();
        $ranks = Rank::with('dojo')->orderBy('level')->get();

        return view('admin.curriculum.promotion-requirements', compact('requirements', 'dojos', 'ranks'));
    }

    public function storeRequirement(Request $request)
    {
        $validated = $request->validate([
            'rank_id' => 'required|exists:ranks,id',
            'requirement_type' => 'required|in:attendance_min,exam_required,recommendation_required',
            'requirement_value' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        RankRequirement::create($validated);

        return redirect()->route('admin.curriculum.promotion-requirements')
            ->with('success', 'Syarat kenaikan tingkat berhasil ditambahkan.');
    }

    public function updateRequirement(Request $request, RankRequirement $requirement)
    {
        $validated = $request->validate([
            'rank_id' => 'required|exists:ranks,id',
            'requirement_type' => 'required|in:attendance_min,exam_required,recommendation_required',
            'requirement_value' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $requirement->update($validated);

        return redirect()->route('admin.curriculum.promotion-requirements')
            ->with('success', 'Syarat kenaikan tingkat berhasil diupdate.');
    }

    public function destroyRequirement(RankRequirement $requirement)
    {
        $requirement->delete();
        return redirect()->route('admin.curriculum.promotion-requirements')
            ->with('success', 'Syarat kenaikan tingkat berhasil dihapus.');
    }

    // Standar Grading Nasional
    public function nationalGrading(Request $request)
    {
        // Show all ranks with their requirements
        $query = Rank::with(['curriculums', 'requirements', 'dojo'])
            ->where(function($q) {
                $q->whereNull('dojo_id');
            });

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $ranks = $query->orderBy('level')->paginate(20);

        return view('admin.curriculum.national-grading', compact('ranks'));
    }
}

