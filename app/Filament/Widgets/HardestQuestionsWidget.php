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

    protected static ?int $sort = 3;

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
                    ->having('assessment_answers_count', '>=', 10)
                    ->orderByRaw('(CAST(correct_answers_count AS FLOAT) / assessment_answers_count) ASC')
            )
            ->columns([
                Tables\Columns\TextColumn::make('source_question_number')
                    ->label('Question')
                    ->formatStateUsing(fn ($record) => "Q{$record->source_question_number}"),

                Tables\Columns\TextColumn::make('sourcePackage.name')
                    ->label('Source')
                    ->description(fn (Question $record) => $record->sourcePackage?->exam_session_label)
                    ->default('—'),

                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Topic')
                    ->description(fn (Question $record) => ucfirst($record->topic?->field ?? ''))
                    ->default('—'),

                Tables\Columns\TextColumn::make('assessment_answers_count')
                    ->label('Attempts')
                    ->sortable(),

                Tables\Columns\TextColumn::make('correct_rate')
                    ->label('Correct rate')
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
            ->emptyStateDescription('Questions will appear here once they have at least 10 learner attempts.');
    }
}
