<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Services\AppSettings;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $settings = app(AppSettings::class);
        $passMark = $settings->passMarkPercent();

        $finishedPretests = Assessment::whereNull('archived_at')
            ->whereIn('assessment_type', ['pretest', 'initial'])
            ->whereIn('status', ['submitted', 'graded'])
            ->whereNotNull('score_percentage')
            ->get();

        $finishedCount = $finishedPretests->count();
        $totalAnswers = AssessmentAnswer::count();

        if ($finishedCount > 0) {
            $passedCount = $finishedPretests->filter(function ($a) use ($settings) {
                return $settings->checkPassRule((float) $a->score_percentage, (array) ($a->field_performance ?? []));
            })->count();

            $passRate = round(($passedCount / $finishedCount) * 100, 1);
            $avgScore = round($finishedPretests->avg('score_percentage'), 1);

            return [
                Stat::make('Completed pre-tests', number_format($finishedCount))
                    ->description('Finished diagnostic assessments')
                    ->color('primary'),

                Stat::make('Examination pass rate', "{$passRate}%")
                    ->description("{$passedCount} of {$finishedCount} passed")
                    ->color($passRate >= $passMark ? 'success' : 'warning'),

                Stat::make('Average score', "{$avgScore}%")
                    ->description("Passing threshold: {$passMark}%")
                    ->color($avgScore >= $passMark ? 'success' : 'warning'),

                Stat::make('Practice questions answered', number_format($totalAnswers))
                    ->description('Total learner attempts recorded')
                    ->color('info'),
            ];
        }

        return [
            Stat::make('Completed pre-tests', '0')
                ->description('No assessments finished yet')
                ->color('gray'),

            Stat::make('Examination pass rate', '—')
                ->description('Awaiting diagnostic results')
                ->color('gray'),

            Stat::make('Average score', '—')
                ->description("Passing threshold: {$passMark}%")
                ->color('gray'),

            Stat::make('Practice questions answered', number_format($totalAnswers))
                ->description('Total learner attempts recorded')
                ->color('info'),
        ];
    }
}
