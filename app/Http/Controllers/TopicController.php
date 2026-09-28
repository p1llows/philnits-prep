<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Question;
use App\Models\Mistake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    /**
     * Display all topics with question counts and performance.
     */
    public function index(Request $request)
    {
        $topics = Topic::with(['questions.published'])
            ->active()
            ->ordered()
            ->get()
            ->map(function ($topic) {
                return [
                    'id' => $topic->id,
                    'name' => $topic->name,
                    'code' => $topic->code,
                    'description' => $topic->description,
                    'color' => $topic->color,
                    'total_questions' => $topic->questions()->count(),
                    'published_questions' => $topic->questions()->published()->count(),
                ];
            });

        // Get user's recent activity (optional enhancement)
        $user = Auth::user();
        
        return view('topics.index', compact('topics'));
    }

    /**
     * Display a specific topic with its questions.
     */
    public function show(Topic $topic, Request $request)
    {
        $questions = Question::where('topic_id', $topic->id)
            ->published()
            ->with(['choices', 'explanationCreatedBy'])
            ->orderBy('source_question_number')
            ->get();

        // Get user's mistakes for this topic
        $mistakes = Mistake::where('user_id', Auth::id())
            ->where('is_resolved', false)
            ->whereIn('question_id', $questions->pluck('id'))
            ->get()
            ->pluck('question_id');

        // Mark which questions have been answered correctly recently
        $recentCorrect = collect($questions)->filter(function ($question) use ($mistakes) {
            // Logic to check if recently correct could go here
            return !in_array($question->id, $mistakes->toArray());
        })->pluck('id');

        return view('topics.show', [
            'topic' => $topic,
            'questions' => $questions,
            'mistakeIds' => $mistakes,
        ]);
    }

    /**
     * Start practice session for a topic.
     */
    public function practice(Topic $topic)
    {
        $questions = Question::where('topic_id', $topic->id)
            ->published()
            ->with(['choices', 'topic'])
            ->get()
            ->toArray();

        // Create practice session data
        $sessionData = [
            'topic_id' => $topic->id,
            'topic_name' => $topic->name,
            'questions' => $questions,
            'current_index' => 0,
            'start_time' => now(),
            'practice_type' => 'topic_practice',
        ];

        session(['practice_session' => $sessionData]);

        return redirect()->route('practice.start', ['topic' => $topic->id]);
    }
}
