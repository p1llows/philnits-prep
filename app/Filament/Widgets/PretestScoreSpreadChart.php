<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Widgets\ChartWidget;

class PretestScoreSpreadChart extends ChartWidget
{
    protected static ?string $heading = 'Pre-test score spread';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $scores = Assessment::whereNull('archived_at')
            ->where('assessment_type', 'pretest')
            ->whereIn('status', ['submitted', 'graded'])
            ->whereNotNull('score_percentage')
            ->pluck('score_percentage');

        if ($scores->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
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
