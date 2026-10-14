<?php

namespace Tests\Unit;

use App\Services\AppSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AppSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected AppSettings $settings;

    protected function setUp(): void
    {
        parent::setUp();
        $this->settings = app(AppSettings::class);
    }

    public function test_it_returns_default_settings(): void
    {
        $this->assertTrue($this->settings->requirePretest());
        $this->assertFalse($this->settings->skipPretestGate());
        $this->assertNull($this->settings->pretestSourcePackageId());
        $this->assertSame(100, $this->settings->pretestQuestionCount());
        $this->assertSame(120, $this->settings->timeLimitMinutes());
        $this->assertTrue($this->settings->allowPauseResume());
        $this->assertSame(7, $this->settings->resumeWindowDays());
        $this->assertFalse($this->settings->shuffleChoices());
        $this->assertSame(60, $this->settings->passMarkPercent());
        $this->assertSame(30, $this->settings->fieldMinimumPercent());
        $this->assertSame('unlimited', $this->settings->retakePolicy());
        $this->assertSame(50, $this->settings->weakTopicThresholdPercent());
        $this->assertSame('weakest_field_first', $this->settings->roadmapOrder());
    }

    public function test_it_persists_and_retrieves_overridden_settings(): void
    {
        $this->settings->set('pass_mark_percent', 70);
        $this->settings->set('field_minimum_percent', 35);
        $this->settings->set('require_pretest', false);

        $this->assertSame(70, $this->settings->passMarkPercent());
        $this->assertSame(35, $this->settings->fieldMinimumPercent());
        $this->assertFalse($this->settings->requirePretest());

        $this->assertDatabaseHas('app_settings', [
            'key' => 'pass_mark_percent',
            'value' => '70',
        ]);
    }

    public function test_pass_rule_boundary_cases(): void
    {
        // Default: 60 overall AND 30 per field
        $passingFields = [
            'strategy' => ['correct' => 10, 'total' => 20, 'percentage' => 50.0],
            'management' => ['correct' => 8, 'total' => 20, 'percentage' => 40.0],
            'technology' => ['correct' => 45, 'total' => 60, 'percentage' => 75.0],
        ];

        // Case 1: Exactly 60% overall, all fields above 30% -> PASS
        $this->assertTrue($this->settings->checkPassRule(60.0, $passingFields));

        // Case 2: 59.9% overall, all fields pass -> FAIL
        $this->assertFalse($this->settings->checkPassRule(59.9, $passingFields));

        // Case 3: High overall score (80%), but one field is exactly 30% -> PASS
        $borderlineFields = [
            'strategy' => ['correct' => 6, 'total' => 20, 'percentage' => 30.0],
            'management' => ['correct' => 15, 'total' => 20, 'percentage' => 75.0],
            'technology' => ['correct' => 50, 'total' => 60, 'percentage' => 83.3],
        ];
        $this->assertTrue($this->settings->checkPassRule(80.0, $borderlineFields));

        // Case 4: High overall score (85%), but one field is 29.9% -> FAIL
        $failingField = [
            'strategy' => ['correct' => 5, 'total' => 20, 'percentage' => 25.0],
            'management' => ['correct' => 18, 'total' => 20, 'percentage' => 90.0],
            'technology' => ['correct' => 58, 'total' => 60, 'percentage' => 96.6],
        ];
        $this->assertFalse($this->settings->checkPassRule(85.0, $failingField));

        // Case 5: Custom override in settings (e.g. 70 overall and 40 field min)
        $this->settings->set('pass_mark_percent', 70);
        $this->settings->set('field_minimum_percent', 40);

        // Score 65% should fail now
        $this->assertFalse($this->settings->checkPassRule(65.0, $passingFields));
        // Score 72% with field below 40% should fail
        $borderlineUnder40 = [
            'strategy' => ['correct' => 7, 'total' => 20, 'percentage' => 35.0],
            'management' => ['correct' => 16, 'total' => 20, 'percentage' => 80.0],
            'technology' => ['correct' => 50, 'total' => 60, 'percentage' => 83.3],
        ];
        $this->assertFalse($this->settings->checkPassRule(72.0, $borderlineUnder40));
    }
}
