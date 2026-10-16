<?php

namespace App\Filament\Widgets;

use App\Models\Topic;
use Filament\Widgets\Widget;

class FieldsSummaryWidget extends Widget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected static string $view = 'filament.widgets.fields-summary-widget';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $fieldsConfig = config('itpec.field_weights', []); // check config/itpec.php, or empty

        $fields = [
            'strategy' => [
                'name' => 'Strategy (S)',
                'topics_count' => Topic::where('field', 'strategy')->count(),
                'active_topics' => Topic::where('field', 'strategy')->where('is_active', true)->count(),
                'weight' => $fieldsConfig['strategy'] ?? null,
            ],
            'management' => [
                'name' => 'Management (M)',
                'topics_count' => Topic::where('field', 'management')->count(),
                'active_topics' => Topic::where('field', 'management')->where('is_active', true)->count(),
                'weight' => $fieldsConfig['management'] ?? null,
            ],
            'technology' => [
                'name' => 'Technology (T)',
                'topics_count' => Topic::where('field', 'technology')->count(),
                'active_topics' => Topic::where('field', 'technology')->where('is_active', true)->count(),
                'weight' => $fieldsConfig['technology'] ?? null,
            ],
        ];

        return [
            'fields' => $fields,
        ];
    }
}
