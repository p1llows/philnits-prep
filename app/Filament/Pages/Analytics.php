<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AnalyticsStatsWidget;
use App\Filament\Widgets\FieldAverageScoreChart;
use App\Filament\Widgets\HardestQuestionsWidget;
use App\Filament\Widgets\PretestScoreSpreadChart;
use App\Models\Assessment;
use Filament\Actions\Action;
use Filament\Pages\Page;

class Analytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Insights';

    protected static ?string $navigationLabel = 'Analytics';

    protected static ?string $title = 'Analytics';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.analytics';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function getSubheading(): ?string
    {
        return 'Overall examination performance, field mastery comparisons, and difficulty diagnostics.';
    }

    public function getHeaderWidgetsColumns(): int | string | array
    {
        return [
            'default' => 1,
            'md' => 2,
            'lg' => 2,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_report')
                ->label('Export report')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $assessments = Assessment::with('user')
                        ->whereNull('archived_at')
                        ->whereIn('assessment_type', ['pretest', 'initial'])
                        ->whereIn('status', ['submitted', 'graded'])
                        ->latest('completed_at')
                        ->get();

                    $headers = [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => 'attachment; filename="analytics-report-' . date('Y-m-d-His') . '.csv"',
                    ];

                    $callback = function () use ($assessments) {
                        $handle = fopen('php://output', 'w');
                        fputcsv($handle, [
                            'Assessment ID',
                            'Learner Name',
                            'Learner Email',
                            'Overall Score (%)',
                            'Passed Exam',
                            'Strategy Score (%)',
                            'Management Score (%)',
                            'Technology Score (%)',
                            'Completed At',
                        ]);

                        foreach ($assessments as $a) {
                            $perf = $a->field_performance ?? [];
                            fputcsv($handle, [
                                $a->id,
                                $a->user?->name ?? 'Unknown',
                                $a->user?->email ?? 'Unknown',
                                $a->score_percentage,
                                $a->passed_exam ? 'Yes' : 'No',
                                $perf['strategy']['percentage'] ?? 'N/A',
                                $perf['management']['percentage'] ?? 'N/A',
                                $perf['technology']['percentage'] ?? 'N/A',
                                $a->completed_at?->toIso8601String(),
                            ]);
                        }

                        fclose($handle);
                    };

                    return response()->stream($callback, 200, $headers);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AnalyticsStatsWidget::class,
            FieldAverageScoreChart::class,
            PretestScoreSpreadChart::class,
            HardestQuestionsWidget::class,
        ];
    }
}
