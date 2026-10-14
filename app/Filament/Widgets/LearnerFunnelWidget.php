<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\User;
use Filament\Widgets\Widget;

class LearnerFunnelWidget extends Widget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected static string $view = 'filament.widgets.learner-funnel-widget';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $totalRegistered = User::count();
        $verifiedEmail = User::whereNotNull('email_verified_at')->count();

        // Step 3: Started pre-test
        $startedPretestUserIds = Assessment::where('assessment_type', 'initial')
            ->distinct('user_id')
            ->pluck('user_id')
            ->toArray();
        $startedPretest = count($startedPretestUserIds);

        // Step 4: Finished pre-test
        $finishedPretestUserIds = Assessment::where('assessment_type', 'initial')
            ->whereIn('status', ['submitted', 'graded'])
            ->distinct('user_id')
            ->pluck('user_id')
            ->toArray();
        $finishedPretest = count($finishedPretestUserIds);

        // Step 5: Studying (finished pre-test and has any practice attempt in last 30 days)
        $studyingCount = 0;
        if (!empty($finishedPretestUserIds)) {
            $studyingCount = Assessment::whereIn('user_id', $finishedPretestUserIds)
                ->where('created_at', '>=', now()->subDays(30))
                ->where(function ($q) {
                    $q->where('assessment_type', '!=', 'initial')
                      ->orWhere('status', 'in_progress');
                })
                ->distinct('user_id')
                ->count('user_id');
        }

        $steps = [
            [
                'key' => 'registered',
                'name' => 'Registered',
                'count' => $totalRegistered,
                'description' => 'All accounts created',
            ],
            [
                'key' => 'verified',
                'name' => 'Verified email',
                'count' => $verifiedEmail,
                'description' => 'OTP verified users',
            ],
            [
                'key' => 'started_pretest',
                'name' => 'Started pre-test',
                'count' => $startedPretest,
                'description' => 'Began diagnostic past paper',
            ],
            [
                'key' => 'finished_pretest',
                'name' => 'Finished pre-test',
                'count' => $finishedPretest,
                'description' => 'Completed and scored',
            ],
            [
                'key' => 'studying',
                'name' => 'Studying',
                'count' => $studyingCount,
                'description' => 'Active practice in last 30 days',
            ],
        ];

        // Compute percentages of registered
        foreach ($steps as &$step) {
            $step['percent_of_registered'] = $totalRegistered > 0
                ? round(($step['count'] / $totalRegistered) * 100, 1)
                : 0.0;
        }

        // Calculate biggest drop between consecutive steps
        $biggestDrop = null;
        $maxDropCount = -1;

        for ($i = 0; $i < count($steps) - 1; $i++) {
            $drop = $steps[$i]['count'] - $steps[$i + 1]['count'];
            if ($drop > $maxDropCount && $drop > 0) {
                $maxDropCount = $drop;
                $pctLoss = $steps[$i]['count'] > 0 ? round(($drop / $steps[$i]['count']) * 100, 1) : 0;
                $biggestDrop = [
                    'from' => $steps[$i]['name'],
                    'to' => $steps[$i + 1]['name'],
                    'lost_count' => $drop,
                    'pct_lost' => $pctLoss,
                ];
            }
        }

        return [
            'steps' => $steps,
            'totalRegistered' => $totalRegistered,
            'biggestDrop' => $biggestDrop,
        ];
    }
}
