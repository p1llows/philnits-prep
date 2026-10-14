<?php

namespace App\Filament\Widgets;

use App\Models\Question;
use App\Models\Topic;
use Filament\Widgets\Widget;

class NeedsAttentionWidget extends Widget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    protected static string $view = 'filament.widgets.needs-attention-widget';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        // 1. Questions in review: pending review or unapproved explanation
        $questionsInReview = Question::where(function ($q) {
            $q->where('status', '!=', 'published')
              ->orWhere('explanation_approved', false)
              ->orWhereNull('topic_id');
        })->count();

        // 2. Topics with no questions
        $topicsWithNoQuestions = Topic::doesntHave('questions')->count();

        // 3. Questions with no explanation
        $questionsWithNoExplanation = Question::where(function ($q) {
            $q->whereNull('explanation')
              ->orWhere('explanation', '');
        })->count();

        $items = [
            [
                'title' => 'Questions in review',
                'description' => 'Unpublished questions, pending explanation approval, or missing topic assignments',
                'count' => $questionsInReview,
                'link' => route('filament.admin.resources.questions.index'),
                'link_text' => 'Review questions',
                'severity' => $questionsInReview > 0 ? 'warning' : 'ok',
            ],
            [
                'title' => 'Topics with no questions',
                'description' => 'Syllabus topics that currently have 0 questions in the question bank',
                'count' => $topicsWithNoQuestions,
                'link' => route('filament.admin.resources.topics.index'),
                'link_text' => 'View topics',
                'severity' => $topicsWithNoQuestions > 0 ? 'danger' : 'ok',
            ],
            [
                'title' => 'Questions with no explanation',
                'description' => 'Questions lacking detailed rationales for learner mistake reviews',
                'count' => $questionsWithNoExplanation,
                'link' => route('filament.admin.resources.questions.index'),
                'link_text' => 'Add explanations',
                'severity' => $questionsWithNoExplanation > 0 ? 'warning' : 'ok',
            ],
        ];

        return [
            'items' => $items,
        ];
    }
}
