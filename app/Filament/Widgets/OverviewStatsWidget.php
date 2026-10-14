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
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $settings = app(AppSettings::class);
        $passMark = $settings->passMarkPercent();
        $fieldMin = $settings->fieldMinimumPercent();

        $stats = [];

        // 1. Published questions
        $publishedCount = Question::published()->count();
        $totalQuestions = Question::count();
        $stats[] = Stat::make('Published questions', number_format($publishedCount))
            ->description($totalQuestions > 0 ? number_format($totalQuestions) . ' total in database' : 'No questions yet')
            ->icon('heroicon-o-document-text');

        // Pre-test calculations (assessment_type = 'initial')
        $finishedPretests = Assessment::where('assessment_type', 'initial')
            ->whereIn('status', ['submitted', 'graded'])
            ->whereNotNull('score_percentage')
            ->get();

        $finishedCount = $finishedPretests->count();

        // 2. Pre-test pass rate (only if finished pretests exist)
        if ($finishedCount > 0) {
            $passedCount = $finishedPretests->filter(function ($a) use ($settings) {
                return $settings->checkPassRule((float) $a->score_percentage, (array) ($a->field_performance ?? []));
            })->count();

            $passRate = round(($passedCount / $finishedCount) * 100, 1);
            $stats[] = Stat::make('Pre-test pass rate', "{$passRate}%")
                ->description("{$passedCount} of {$finishedCount} passed (min {$passMark}% overall, {$fieldMin}%/field)")
                ->color($passRate >= 60 ? 'success' : 'danger')
                ->icon('heroicon-o-check-badge');

            // 3. Average baseline score
            $avgScore = round($finishedPretests->avg('score_percentage'), 1);
            $stats[] = Stat::make('Average baseline score', "{$avgScore}%")
                ->description("Across {$finishedCount} finished diagnostic tests")
                ->icon('heroicon-o-chart-bar');
        }

        // 4. Weakest field
        // Computed from all finished pre-test field performances
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
                    ->description("{$weakestPct}% average cohort accuracy")
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-triangle');
            }
        }

        return $stats;
    }
}
