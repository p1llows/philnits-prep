<?php

namespace App\Filament\Widgets;

use App\Models\AuditLog;
use Filament\Widgets\Widget;

class RecentActivityWidget extends Widget
{
    protected static ?int $sort = 4;

    protected static bool $isLazy = false;

    protected static string $view = 'filament.widgets.recent-activity-widget';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 1,
    ];

    public function getViewData(): array
    {
        $activities = AuditLog::with('user')
            ->latest('created_at')
            ->limit(5)
            ->get();

        return [
            'activities' => $activities,
        ];
    }
}
