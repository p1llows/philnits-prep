<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AppSettings
{
    protected const CACHE_PREFIX = 'app_setting:';
    protected const CACHE_TTL_SECONDS = 300; // 5-minute short cache

    /**
     * Get a setting by key with a default from config/itpec.php.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember(self::CACHE_PREFIX . $key, self::CACHE_TTL_SECONDS, function () use ($key, $default) {
            $record = DB::table('app_settings')->where('key', $key)->first();
            if ($record && $record->value !== null) {
                $decoded = json_decode($record->value, true);
                return $decoded !== null ? $decoded : $record->value;
            }

            return $default ?? config("itpec.{$key}");
        });
    }

    /**
     * Set a setting value and invalidate the cache.
     */
    public function set(string $key, mixed $value): void
    {
        DB::table('app_settings')->updateOrInsert(
            ['key' => $key],
            [
                'value' => json_encode($value),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        Cache::forget(self::CACHE_PREFIX . $key);
    }

    /**
     * Set multiple settings at once.
     */
    public function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
    }

    // Typed getters

    public function requirePretest(): bool
    {
        return (bool) $this->get('require_pretest', true);
    }

    public function skipPretestGate(): bool
    {
        return (bool) $this->get('skip_pretest_gate', false);
    }

    public function pretestSourcePackageId(): ?int
    {
        $id = $this->get('pretest_source_package_id', null);
        return $id !== null && $id !== '' ? (int) $id : null;
    }

    public function pretestQuestionCount(): int
    {
        return (int) $this->get('pretest_question_count', 100);
    }

    public function timeLimitMinutes(): int
    {
        return (int) $this->get('time_limit_minutes', 120);
    }

    public function allowPauseResume(): bool
    {
        return (bool) $this->get('allow_pause_resume', true);
    }

    public function resumeWindowDays(): int
    {
        return (int) $this->get('resume_window_days', 7);
    }

    public function shuffleChoices(): bool
    {
        return (bool) $this->get('shuffle_choices', false);
    }

    public function passMarkPercent(): int
    {
        return (int) $this->get('pass_mark_percent', 60);
    }

    public function fieldMinimumPercent(): int
    {
        return (int) $this->get('field_minimum_percent', 30);
    }

    public function retakePolicy(): string
    {
        return (string) $this->get('retake_policy', 'unlimited');
    }

    public function weakTopicThresholdPercent(): int
    {
        return (int) $this->get('weak_topic_threshold_percent', 50);
    }

    public function roadmapOrder(): string
    {
        return (string) $this->get('roadmap_order', 'weakest_field_first');
    }

    /**
     * Brand & layout helpers with strict fallbacks.
     */
    public function appName(): string
    {
        return (string) $this->get('app_name', config('app.name', 'Paxi'));
    }

    public function appTagline(): string
    {
        return (string) $this->get('app_tagline', 'IT Passport Study Companion');
    }

    public function logoUrl(): string
    {
        $path = $this->get('app_logo_path');
        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        return '/paxi-mark.svg';
    }

    public function faviconUrl(): string
    {
        $path = $this->get('app_favicon_path');
        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        return '/favicon.svg';
    }

    public function announcementBanner(): array
    {
        return [
            'enabled' => (bool) $this->get('announcement_enabled', false),
            'text' => (string) $this->get('announcement_text', ''),
        ];
    }

    /**
     * Check if a given overall score and field breakdown meet the passing rule.
     * Rule: overall >= pass_mark_percent AND each individual field >= field_minimum_percent.
     */
    public function checkPassRule(float $overallScore, array $fieldPerformance = []): bool
    {
        if ($overallScore < $this->passMarkPercent()) {
            return false;
        }

        if (!empty($fieldPerformance)) {
            $fieldMin = $this->fieldMinimumPercent();
            foreach ($fieldPerformance as $field) {
                if (isset($field['total']) && $field['total'] > 0) {
                    $pct = $field['percentage'] ?? 0;
                    if ($pct < $fieldMin) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
