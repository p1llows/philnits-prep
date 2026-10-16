<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\Topic;
use App\Services\AppSettings;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OverviewStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $settings = app(AppSettings::class);
        $passMark = $settings->passMarkPercent();
        $fieldMin = $settings->fieldMinimumPercent();

        $stats = [];

        // 1. Published questions
        $publishedCount = Question::published()->count();
        $inReviewCount = Question::where('status', 'in_review')->count();
        $stats[] = Stat::make('Published questions', number_format($publishedCount))
            ->description($inReviewCount > 0 ? "{$inReviewCount} pending review" : 'All questions published')
            ->descriptionIcon($inReviewCount > 0 ? 'heroicon-m-clock' : 'heroicon-m-check-circle')
            ->color($inReviewCount > 0 ? 'warning' : 'success');

        // Pre-test calculations (assessment_type = 'pretest' or 'initial')
        $finishedPretests = Assessment::whereNull('archived_at')
            ->whereIn('assessment_type', ['pretest', 'initial'])
            ->whereIn('status', ['submitted', 'graded'])
            ->whereNotNull('score_percentage')
            ->get();

        $finishedCount = $finishedPretests->count();

        // 2. Pre-test pass rate
        if ($finishedCount > 0) {
            $passedCount = $finishedPretests->filter(function ($a) use ($settings) {
                return $settings->checkPassRule((float) $a->score_percentage, (array) ($a->field_performance ?? []));
            })->count();

            $passRate = round(($passedCount / $finishedCount) * 100, 1);
            $stats[] = Stat::make('Pre-test pass rate', "{$passRate}%")
                ->description("{$passedCount} of {$finishedCount} completed")
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($passRate >= $passMark ? 'success' : 'warning');

            // 3. Average baseline score
            $avgScore = round($finishedPretests->avg('score_percentage'), 1);
            $stats[] = Stat::make('Average baseline score', "{$avgScore}%")
                ->description("Pass threshold: {$passMark}%")
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($avgScore >= $passMark ? 'success' : 'warning');
        } else {
            $stats[] = Stat::make('Pre-test pass rate', '—')
                ->description('No completed attempts')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('gray');

            $stats[] = Stat::make('Average baseline score', '—')
                ->description("Pass threshold: {$passMark}%")
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('gray');
        }

        // 4. Weakest field
        if ($finishedCount > 0) {
            $fieldTotals = [
                'strategy' => ['correct' => 0, 'total' => 0],
                'management' => ['correct' => 0, 'total' => 0],
                'technology' => ['correct' => 0, 'total' => 0],
            ];

            foreach ($finishedPretests as $pretest) {
                $perf = (array) ($pretest->field_performance ?? []);
                foreach ($fieldTotals as $fieldKey => &$data) {
                    if (isset($perf[$fieldKey]) && is_array($perf[$fieldKey])) {
                        $data['correct'] += (int) ($perf[$fieldKey]['correct'] ?? 0);
                        $data['total'] += (int) ($perf[$fieldKey]['total'] ?? 0);
                    }
                }
            }

            $fieldAverages = [];
            foreach ($fieldTotals as $fieldKey => $data) {
                if ($data['total'] > 0) {
                    $fieldAverages[$fieldKey] = round(($data['correct'] / $data['total']) * 100, 1);
                }
            }

            if (!empty($fieldAverages)) {
                asort($fieldAverages);
                $weakestKey = array_key_first($fieldAverages);
                $weakestPct = $fieldAverages[$weakestKey];
                $fieldNames = [
                    'strategy' => 'Strategy',
                    'management' => 'Management',
                    'technology' => 'Technology',
                ];

                $stats[] = Stat::make('Weakest field', $fieldNames[$weakestKey] ?? ucfirst($weakestKey))
                    ->description("Curriculum average {$weakestPct}%")
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('warning');
            } else {
                $stats[] = Stat::make('Weakest field', '—')
                    ->description('No field data yet')
                    ->color('gray');
            }
        } else {
            $stats[] = Stat::make('Weakest field', '—')
                ->description('Awaiting diagnostic results')
                ->color('gray');
        }

        return $stats;
    }
}
