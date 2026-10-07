<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Topic;
use App\Models\SourcePackage;
use App\Models\Choice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a paginated list of questions for admin management.
     */
    public function index(Request $request)
    {
        $this->authorize('adminAccess');

        $query = Question::with(['topic', 'choices', 'createdBy']);

        // Filter by topic
        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by question text
        if ($request->filled('search')) {
            $query->where('question_text', 'LIKE', '%' . $request->search . '%');
        }

        $questions = $query->orderBy('updated_at', 'desc')->paginate(15)->withQueryString();
        $topics = Topic::orderBy('name')->get();

        return view('admin.questions.index', compact('questions', 'topics'));
    }

    /**
     * Show form to create a new question.
     */
    public function create()
    {
        $this->authorize('adminAccess');

        $topics = Topic::active()->ordered()->get();
        $sourcePackages = SourcePackage::orderBy('name')->get();

        return view('admin.questions.create', compact('topics', 'sourcePackages'));
    }

    /**
     * Store a newly created question and its choices.
     */
    public function store(Request $request)
    {
        $this->authorize('adminAccess');

        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'source_package_id' => 'nullable|exists:source_packages,id',
            'source_question_number' => 'nullable|integer|min:1',
            'question_text' => 'required|string',
            'correct_answer_code' => 'required|string|in:A,B,C,D',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'status' => 'required|string|in:draft,validated,published',
            'choices' => 'required|array|min:4|max:4',
            'choices.A' => 'required|string',
            'choices.B' => 'required|string',
            'choices.C' => 'required|string',
            'choices.D' => 'required|string',
        ]);

        $nextNumber = $validated['source_question_number'] 
            ?? ((Question::where('topic_id', $validated['topic_id'])->max('source_question_number') ?? 0) + 1);

        DB::transaction(function () use ($validated, $nextNumber) {
            $question = Question::create([
                'topic_id' => $validated['topic_id'],
                'source_package_id' => $validated['source_package_id'] ?? null,
                'source_question_number' => $nextNumber,
                'question_text' => $validated['question_text'],
                'correct_answer_code' => $validated['correct_answer_code'],
                'explanation' => $validated['explanation'] ?? null,
                'explanation_type' => !empty($validated['explanation']) ? 'admin_created' : null,
                'explanation_approved' => !empty($validated['explanation']),
                'difficulty' => $validated['difficulty'],
                'status' => $validated['status'],
                'created_by' => Auth::id(),
                'published_by' => $validated['status'] === 'published' ? Auth::id() : null,
                'published_at' => $validated['status'] === 'published' ? now() : null,
            ]);

            $codes = ['A', 'B', 'C', 'D'];
            foreach ($codes as $index => $code) {
                Choice::create([
                    'question_id' => $question->id,
                    'code' => $code,
                    'text' => $validated['choices'][$code],
                    'display_order' => $index + 1,
                ]);
            }
        });

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question created successfully.');
    }

    /**
     * Display details of a question.
     */
    public function show(Question $question)
    {
        $this->authorize('adminAccess');

        $question->load(['topic', 'choices', 'sourcePackage', 'createdBy', 'publishedBy']);

        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show form to edit a question.
     */
    public function edit(Question $question)
    {
        $this->authorize('adminAccess');

        $question->load(['choices']);
        $topics = Topic::active()->ordered()->get();
        $sourcePackages = SourcePackage::orderBy('name')->get();

        return view('admin.questions.edit', compact('question', 'topics', 'sourcePackages'));
    }

    /**
     * Update an existing question and its choices.
     */
    public function update(Request $request, Question $question)
    {
        $this->authorize('adminAccess');

        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'source_package_id' => 'nullable|exists:source_packages,id',
            'source_question_number' => 'nullable|integer|min:1',
            'question_text' => 'required|string',
            'correct_answer_code' => 'required|string|in:A,B,C,D',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'status' => 'required|string|in:draft,validated,published',
            'choices' => 'required|array|min:4|max:4',
            'choices.A' => 'required|string',
            'choices.B' => 'required|string',
            'choices.C' => 'required|string',
            'choices.D' => 'required|string',
        ]);

        DB::transaction(function () use ($question, $validated) {
            $question->update([
                'topic_id' => $validated['topic_id'],
                'source_package_id' => $validated['source_package_id'] ?? null,
                'source_question_number' => $validated['source_question_number'] ?? $question->source_question_number ?? 1,
                'question_text' => $validated['question_text'],
                'correct_answer_code' => $validated['correct_answer_code'],
                'explanation' => $validated['explanation'] ?? null,
                'difficulty' => $validated['difficulty'],
                'status' => $validated['status'],
                'published_by' => $validated['status'] === 'published' ? Auth::id() : $question->published_by,
                'published_at' => $validated['status'] === 'published' ? ($question->published_at ?? now()) : null,
            ]);

            foreach (['A', 'B', 'C', 'D'] as $index => $code) {
                Choice::updateOrCreate(
                    ['question_id' => $question->id, 'code' => $code],
                    [
                        'text' => $validated['choices'][$code],
                        'display_order' => $index + 1,
                    ]
                );
            }
        });

        return redirect()->route('admin.questions.show', $question)
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Toggle or set publish status of a question.
     */
    public function publish(Question $question)
    {
        $this->authorize('adminAccess');

        $newStatus = ($question->status === 'published') ? 'draft' : 'published';
        
        $question->update([
            'status' => $newStatus,
            'published_by' => $newStatus === 'published' ? Auth::id() : null,
            'published_at' => $newStatus === 'published' ? now() : null,
        ]);

        return back()->with('success', 'Question status updated to ' . $newStatus . '.');
    }

    /**
     * Remove question from database.
     */
    public function destroy(Question $question)
    {
        $this->authorize('adminAccess');

        $question->choices()->delete();
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question deleted successfully.');
    }
}
