<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Widgets\ChartWidget;

class FieldAverageScoreChart extends ChartWidget
{
    protected static ?string $heading = 'Average score by field';

    protected static ?string $description = 'Average performance across Strategy, Management, and Technology';

    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected static ?string $maxHeight = '240px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'lg' => 1,
    ];

    protected static ?string $pollingInterval = null;

    public function render(): \Illuminate\Contracts\View\View
    {
        $hasData = Assessment::whereNull('archived_at')
            ->whereIn('assessment_type', ['pretest', 'initial'])
            ->whereIn('status', ['submitted', 'graded'])
            ->whereNotNull('field_performance')
            ->exists();

        if (! $hasData) {
            return view('filament.widgets.chart-empty-card', [
                'heading' => $this->getHeading(),
                'description' => 'No finished pre-tests yet',
            ]);
        }

        return parent::render();
    }

    protected function getData(): array
    {
        // Calculate average scores from completed pre-tests
        $assessments = Assessment::whereNull('archived_at')
            ->whereIn('assessment_type', ['pretest', 'initial'])
            ->whereIn('status', ['submitted', 'graded'])
            ->whereNotNull('field_performance')
            ->get();

        if ($assessments->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Average score (%)',
                        'data' => [0, 0, 0],
                        'backgroundColor' => ['#CBD7E7', '#CBD7E7', '#CBD7E7'],
                    ],
                ],
                'labels' => ['Strategy', 'Management', 'Technology'],
            ];
        }

        $fieldTotals = [
            'strategy' => ['sum' => 0, 'count' => 0],
            'management' => ['sum' => 0, 'count' => 0],
            'technology' => ['sum' => 0, 'count' => 0],
        ];

        foreach ($assessments as $a) {
            $perf = $a->field_performance ?? [];
            foreach (['strategy', 'management', 'technology'] as $field) {
                if (isset($perf[$field]['percentage'])) {
                    $fieldTotals[$field]['sum'] += $perf[$field]['percentage'];
                    $fieldTotals[$field]['count']++;
                }
            }
        }

        $strategyAvg = $fieldTotals['strategy']['count'] > 0
            ? round($fieldTotals['strategy']['sum'] / $fieldTotals['strategy']['count'], 1)
            : 0;

        $managementAvg = $fieldTotals['management']['count'] > 0
            ? round($fieldTotals['management']['sum'] / $fieldTotals['management']['count'], 1)
            : 0;

        $technologyAvg = $fieldTotals['technology']['count'] > 0
            ? round($fieldTotals['technology']['sum'] / $fieldTotals['technology']['count'], 1)
            : 0;

        return [
            'datasets' => [
                [
                    'label' => 'Average score (%)',
                    'data' => [$strategyAvg, $managementAvg, $technologyAvg],
                    'backgroundColor' => ['#1F3A5F', '#3A5F92', '#6F8DB8'],
                    'borderRadius' => 6,
                ],
            ],
            'labels' => ['Strategy', 'Management', 'Technology'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'min' => 0,
                    'max' => 100,
                    'ticks' => [
                        'stepSize' => 20,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
