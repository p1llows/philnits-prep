<?php

namespace App\Filament\Widgets;

use App\Models\Question;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class HardestQuestionsWidget extends BaseWidget
{
    protected static ?string $heading = 'Hardest questions';

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 4;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Question::query()
                    ->with(['sourcePackage', 'topic'])
                    ->withCount('assessmentAnswers')
                    ->withCount([
                        'assessmentAnswers as correct_answers_count' => function ($query) {
                            $query->where('is_correct', true);
                        },
                    ])
                    ->has('assessmentAnswers', '>=', 5)
                    ->orderByRaw('CASE WHEN (SELECT count(*) FROM assessment_answers WHERE assessment_answers.question_id = questions.id) > 0 THEN (CAST((SELECT count(*) FROM assessment_answers WHERE assessment_answers.question_id = questions.id AND assessment_answers.is_correct = 1) AS FLOAT) / (SELECT count(*) FROM assessment_answers WHERE assessment_answers.question_id = questions.id)) ELSE 0 END ASC')
            )
            ->columns([
                Tables\Columns\TextColumn::make('source_question_number')
                    ->label('Question')
                    ->formatStateUsing(fn ($record) => "Q{$record->source_question_number}")
                    ->width('80px'),

                Tables\Columns\TextColumn::make('sourcePackage.name')
                    ->label('Source')
                    ->description(fn (Question $record) => $record->sourcePackage?->exam_session_label)
                    ->default('—')
                    ->wrap(),

                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Topic')
                    ->description(fn (Question $record) => ucfirst($record->topic?->field ?? ''))
                    ->default('—')
                    ->wrap(),

                Tables\Columns\TextColumn::make('assessment_answers_count')
                    ->label('Attempts')
                    ->alignCenter()
                    ->width('90px')
                    ->sortable(),

                Tables\Columns\TextColumn::make('correct_rate')
                    ->label('Correct rate')
                    ->alignCenter()
                    ->width('110px')
                    ->badge()
                    ->color(function (Question $record) {
                        $total = $record->assessment_answers_count;
                        if ($total == 0) return 'gray';
                        $rate = ($record->correct_answers_count / $total) * 100;
                        return $rate < 30 ? 'danger' : 'gray';
                    })
                    ->getStateUsing(function (Question $record) {
                        $total = $record->assessment_answers_count;
                        if ($total == 0) return '0%';
                        $rate = round(($record->correct_answers_count / $total) * 100, 1);
                        return "{$rate}%";
                    }),
            ])
            ->emptyStateHeading('No questions with sufficient attempts')
            ->emptyStateDescription('Questions will appear here once they have at least 5 learner attempts.')
            ->emptyStateIcon('heroicon-o-chart-bar');
    }
}
