<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    /**
     * Display all topics (Admin only).
     */
    public function index(Request $request)
    {
        $this->authorize('adminAccess'); // Admin-only authorization
        
        $topics = Topic::withCount('questions')
            ->orderBy('code', 'asc')
            ->paginate(50);

        return view('admin.topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new topic.
     */
    public function create()
    {
        $this->authorize('adminAccess');
        
        return view('admin.topics.create');
    }

    /**
     * Store a newly created topic in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('adminAccess');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:topics,code',
            'description' => 'nullable|string',
            'color' => 'required|string|size:6|pattern:/^[0-9a-fA-F]{6}$/',
            'is_active' => 'boolean',
        ]);

        Topic::create($validated);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic created successfully.');
    }

    /**
     * Display the specified topic.
     */
    public function show(Topic $topic)
    {
        $this->authorize('adminAccess');
        
        $topic->load(['questions.published']);

        return view('admin.topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified topic.
     */
    public function edit(Topic $topic)
    {
        $this->authorize('adminAccess');
        
        return view('admin.topics.edit', compact('topic'));
    }

    /**
     * Update the specified topic in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        $this->authorize('adminAccess');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:topics,code,' . $topic->id,
            'description' => 'nullable|string',
            'color' => 'required|string|size:6|pattern:/^[0-9a-fA-F]{6}$/',
            'is_active' => 'boolean',
        ]);

        $topic->update($validated);

        return redirect()->route('admin.topics.show', $topic)
            ->with('success', 'Topic updated successfully.');
    }

    /**
     * Remove the specified topic from storage.
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('adminAccess');

        if ($topic->questions_count > 0) {
            return redirect()->route('admin.topics.show', $topic)
                ->with('error', 'Cannot delete topic with existing questions.');
        }

        $topic->delete();

        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic deleted successfully.');
    }

    /**
     * Toggle topic active status.
     */
    public function toggleStatus(Topic $topic)
    {
        $this->authorize('adminAccess');

        $topic->toggleActive();

        return back()->with('success', 'Topic status updated.');
    }
}
