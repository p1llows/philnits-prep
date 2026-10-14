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
