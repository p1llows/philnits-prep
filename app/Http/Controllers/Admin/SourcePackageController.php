<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourcePackage;
use App\Models\Question;
use App\Models\Choice;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SourcePackageController extends Controller
{
    /**
     * Display a listing of source packages.
     */
    public function index(Request $request)
    {
        $this->authorize('adminAccess');

        $query = SourcePackage::withCount('questions')->with(['createdBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('source_name', 'LIKE', '%' . $request->search . '%');
            });
        }

        $sourcePackages = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.source_packages.index', compact('sourcePackages'));
    }

    /**
     * Show form for creating a new source package.
     */
    public function create()
    {
        $this->authorize('adminAccess');

        return view('admin.source_packages.create');
    }

    /**
     * Store a newly created source package in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('adminAccess');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'source_name' => 'nullable|string|max:255',
            'source_date' => 'nullable|string|max:100',
            'attribution_note' => 'nullable|string',
            'status' => 'required|string|in:draft,importing,imported,validating,validation_failed,pending_review,approved,published,archived',
        ]);

        $validated['created_by'] = Auth::id();

        $sourcePackage = SourcePackage::create($validated);

        return redirect()->route('admin.source-packages.show', $sourcePackage)
            ->with('success', 'Source package created successfully.');
    }

    /**
     * Display the specified source package.
     */
    public function show(SourcePackage $sourcePackage)
    {
        $this->authorize('adminAccess');

        $sourcePackage->load(['createdBy', 'questions.topic', 'questions.choices']);
        $topics = Topic::active()->ordered()->get();

        return view('admin.source_packages.show', compact('sourcePackage', 'topics'));
    }

    /**
     * Show form to edit source package.
     */
    public function edit(SourcePackage $sourcePackage)
    {
        $this->authorize('adminAccess');

        return view('admin.source_packages.edit', compact('sourcePackage'));
    }

    /**
     * Update source package metadata.
     */
    public function update(Request $request, SourcePackage $sourcePackage)
    {
        $this->authorize('adminAccess');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'source_name' => 'nullable|string|max:255',
            'source_date' => 'nullable|string|max:100',
            'attribution_note' => 'nullable|string',
            'status' => 'required|string|in:draft,importing,imported,validating,validation_failed,pending_review,approved,published,archived',
        ]);

        $sourcePackage->update($validated);

        return redirect()->route('admin.source-packages.show', $sourcePackage)
            ->with('success', 'Source package updated successfully.');
    }

    /**
     * Delete source package.
     */
    public function destroy(SourcePackage $sourcePackage)
    {
        $this->authorize('adminAccess');

        // Detach questions from source package or delete
        $sourcePackage->questions()->update(['source_package_id' => null]);
        $sourcePackage->delete();

        return redirect()->route('admin.source-packages.index')
            ->with('success', 'Source package deleted successfully.');
    }

    /**
     * Batch import questions into this source package from JSON format.
     */
    public function importQuestions(Request $request, SourcePackage $sourcePackage)
    {
        $this->authorize('adminAccess');

        $request->validate([
            'json_data' => 'required|string',
            'topic_id' => 'required|exists:topics,id',
            'status' => 'required|string|in:draft,validated,published',
        ]);

        $data = json_decode($request->json_data, true);

        if (!is_array($data)) {
            return back()->with('error', 'Invalid JSON input format. Please check syntax.');
        }

        // Support array of questions or single question
        $questionsList = isset($data[0]) ? $data : [$data];
        $importedCount = 0;

        DB::transaction(function () use ($questionsList, $sourcePackage, $request, &$importedCount) {
            foreach ($questionsList as $item) {
                if (empty($item['question_text']) || empty($item['correct_answer_code'])) {
                    continue;
                }

                $nextNumber = Question::where('topic_id', $request->topic_id)->max('source_question_number') + 1;

                $question = Question::create([
                    'topic_id' => $request->topic_id,
                    'source_package_id' => $sourcePackage->id,
                    'source_question_number' => $item['source_question_number'] ?? $nextNumber,
                    'question_text' => $item['question_text'],
                    'correct_answer_code' => strtoupper(trim($item['correct_answer_code'])),
                    'explanation' => $item['explanation'] ?? null,
                    'explanation_type' => !empty($item['explanation']) ? 'imported' : null,
                    'explanation_approved' => true,
                    'difficulty' => $item['difficulty'] ?? 'medium',
                    'status' => $request->status,
                    'created_by' => Auth::id(),
                    'published_by' => $request->status === 'published' ? Auth::id() : null,
                    'published_at' => $request->status === 'published' ? now() : null,
                ]);

                $choicesData = $item['choices'] ?? [];
                foreach (['A', 'B', 'C', 'D'] as $index => $code) {
                    $choiceText = $choicesData[$code] ?? $choicesData[strtolower($code)] ?? ("Option " . $code);
                    Choice::create([
                        'question_id' => $question->id,
                        'code' => $code,
                        'text' => $choiceText,
                        'display_order' => $index + 1,
                    ]);
                }

                $importedCount++;
            }

            // Update package counts & status
            $sourcePackage->update([
                'question_count' => $sourcePackage->questions()->count(),
                'published_count' => $sourcePackage->questions()->where('status', 'published')->count(),
                'status' => 'published',
            ]);
        });

        return back()->with('success', "Successfully imported {$importedCount} questions into {$sourcePackage->name}.");
    }
}
