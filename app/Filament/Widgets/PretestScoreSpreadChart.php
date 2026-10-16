<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Widgets\ChartWidget;

class PretestScoreSpreadChart extends ChartWidget
{
    protected static ?string $heading = 'Pre-test score spread';

    protected static ?string $description = 'Distribution of overall learner baseline assessment scores';

    protected static ?int $sort = 3;

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
            ->whereNotNull('score_percentage')
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
        $scores = Assessment::whereNull('archived_at')
            ->whereIn('assessment_type', ['pretest', 'initial'])
            ->whereIn('status', ['submitted', 'graded'])
            ->whereNotNull('score_percentage')
            ->pluck('score_percentage');

        if ($scores->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Learners',
                        'data' => [0, 0, 0, 0, 0],
                        'backgroundColor' => '#CBD7E7',
                    ],
                ],
                'labels' => ['< 40%', '40-59%', '60-69%', '70-79%', '80-100%'],
            ];
        }

        $buckets = [
            '0 - 20' => 0,
            '20 - 40' => 0,
            '40 - 60' => 0,
            '60 - 80' => 0,
            '80 - 100' => 0,
        ];

        foreach ($scores as $score) {
            if ($score < 20) {
                $buckets['0 - 20']++;
            } elseif ($score < 40) {
                $buckets['20 - 40']++;
            } elseif ($score < 60) {
                $buckets['40 - 60']++;
            } elseif ($score < 80) {
                $buckets['60 - 80']++;
            } else {
                $buckets['80 - 100']++;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Learners',
                    'data' => array_values($buckets),
                    'backgroundColor' => '#1F3A5F',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => array_keys($buckets),
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
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
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
