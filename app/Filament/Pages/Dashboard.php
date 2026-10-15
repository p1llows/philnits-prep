<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LearnerFunnelWidget;
use App\Filament\Widgets\NeedsAttentionWidget;
use App\Filament\Widgets\OverviewStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Overview';

    protected static ?string $title = 'Overview';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'editor']);
    }

    public function getSubheading(): ?string
    {
        return 'How Paxi is doing and what needs your attention.';
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('upload_past_paper')
                ->label('Upload past paper')
                ->color('gray')
                ->outlined()
                ->url(route('filament.admin.resources.source-packages.create')),

            \Filament\Actions\Action::make('add_question')
                ->label('Add question')
                ->color('primary')
                ->url(route('filament.admin.resources.questions.create')),
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'md' => 2,
            'lg' => 2,
        ];
    }

    public function getWidgets(): array
    {
        return [
            LearnerFunnelWidget::class,
            OverviewStatsWidget::class,
            NeedsAttentionWidget::class,
            \App\Filament\Widgets\RecentActivityWidget::class,
        ];
    }
}
