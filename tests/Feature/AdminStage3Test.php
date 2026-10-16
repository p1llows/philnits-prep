<?php

namespace Tests\Feature;

use App\Filament\Pages\Analytics;
use App\Filament\Pages\ConfigureAppSettings;
use App\Filament\Pages\PreTestAndRules;
use App\Filament\Pages\ReviewQueue;
use App\Filament\Resources\AuditLogResource;
use App\Filament\Resources\QuestionResource;
use App\Filament\Resources\SourcePackageResource;
use App\Filament\Resources\TopicResource;
use App\Filament\Resources\UserResource;
use App\Models\Assessment;
use App\Models\AuditLog;
use App\Models\Question;
use App\Models\SourcePackage;
use App\Models\Topic;
use App\Models\User;
use App\Services\AppSettings;
use App\Services\Audit;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AdminStage3Test extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $editor;
    protected User $learner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->learner = User::factory()->create([
            'role' => 'learner',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    public function test_editor_can_only_access_content_resources(): void
    {
        // Allowed for editor
        $this->actingAs($this->editor)->get(SourcePackageResource::getUrl('index'))->assertSuccessful();
        $this->actingAs($this->editor)->get(QuestionResource::getUrl('index'))->assertSuccessful();
        $this->actingAs($this->editor)->get(TopicResource::getUrl('index'))->assertSuccessful();
        $this->actingAs($this->editor)->get(ReviewQueue::getUrl())->assertSuccessful();

        // Forbidden for editor
        $this->actingAs($this->editor)->get(UserResource::getUrl('index'))->assertForbidden();
        $this->actingAs($this->editor)->get(AuditLogResource::getUrl('index'))->assertForbidden();
        $this->actingAs($this->editor)->get(Analytics::getUrl())->assertForbidden();
        $this->actingAs($this->editor)->get(ConfigureAppSettings::getUrl())->assertForbidden();
        $this->actingAs($this->editor)->get(PreTestAndRules::getUrl())->assertForbidden();
    }

    public function test_admin_can_access_all_panel_resources_and_pages(): void
    {
        $this->actingAs($this->admin)->get(SourcePackageResource::getUrl('index'))->assertSuccessful();
        $this->actingAs($this->admin)->get(QuestionResource::getUrl('index'))->assertSuccessful();
        $this->actingAs($this->admin)->get(TopicResource::getUrl('index'))->assertSuccessful();
        $this->actingAs($this->admin)->get(ReviewQueue::getUrl())->assertSuccessful();
        $this->actingAs($this->admin)->get(UserResource::getUrl('index'))->assertSuccessful();
        $this->actingAs($this->admin)->get(AuditLogResource::getUrl('index'))->assertSuccessful();
        $this->actingAs($this->admin)->get(Analytics::getUrl())->assertSuccessful();
        $this->actingAs($this->admin)->get(ConfigureAppSettings::getUrl())->assertSuccessful();
        $this->actingAs($this->admin)->get(PreTestAndRules::getUrl())->assertSuccessful();
    }

    public function test_deactivated_user_cannot_sign_in(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'Your account has been deactivated. Please contact an administrator.']);
        $this->assertGuest();
    }

    public function test_self_demotion_and_last_admin_protection(): void
    {
        // 1. Single admin cannot demote self via resource action logic
        Livewire::actingAs($this->admin)
            ->test(UserResource\Pages\ListUsers::class)
            ->callTableAction('change_role', $this->admin, data: [
                'role' => 'editor',
            ])
            ->assertHasNoTableActionErrors();

        // Role should still be admin because action blocked self-demotion
        $this->assertEquals('admin', $this->admin->fresh()->role);

        // 2. Add second admin
        $admin2 = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        // Demoting admin2 should succeed because another active admin exists
        Livewire::actingAs($this->admin)
            ->test(UserResource\Pages\ListUsers::class)
            ->callTableAction('change_role', $admin2, data: [
                'role' => 'editor',
            ]);

        $this->assertEquals('editor', $admin2->fresh()->role);

        // 3. Now only $this->admin is active admin. Deactivating self is blocked
        Livewire::actingAs($this->admin)
            ->test(UserResource\Pages\ListUsers::class)
            ->callTableAction('deactivate', $this->admin);

        $this->assertTrue((bool) $this->admin->fresh()->is_active);
    }

    public function test_audit_log_written_for_role_and_rule_changes_and_contains_no_secrets(): void
    {
        $learner = User::factory()->create(['role' => 'learner', 'is_active' => true]);

        // Trigger role change
        Livewire::actingAs($this->admin)
            ->test(UserResource\Pages\ListUsers::class)
            ->callTableAction('change_role', $learner, data: [
                'role' => 'editor',
            ]);

        $log = AuditLog::where('action', 'role.changed')->latest()->first();
        $this->assertNotNull($log);
        $this->assertEquals($learner->id, $log->target_id);
        $this->assertEquals('learner', $log->detail['old_role']);
        $this->assertEquals('editor', $log->detail['new_role']);

        // Check helper automatically redacts secret fields
        Audit::log('test.action', $learner, [
            'password' => 'secret123',
            'code' => '999999',
            'token' => 'abcde',
            'public_info' => 'visible',
        ]);

        $secretLog = AuditLog::where('action', 'test.action')->latest()->first();
        $this->assertNotNull($secretLog);
        $this->assertEquals('[REDACTED]', $secretLog->detail['password']);
        $this->assertEquals('[REDACTED]', $secretLog->detail['code']);
        $this->assertEquals('[REDACTED]', $secretLog->detail['token']);
        $this->assertEquals('visible', $secretLog->detail['public_info']);
    }

    public function test_otp_service_reads_settings_and_defaults_to_ten(): void
    {
        $otpService = app(OtpService::class);
        $settings = app(AppSettings::class);

        // Default should be 10 minutes
        $this->assertEquals(10, $otpService->getExpirationMinutes());

        // Update setting to 20 minutes
        $settings->set('otp_expiration_minutes', 20);
        $this->assertEquals(20, $otpService->getExpirationMinutes());

        // Generate OTP and check stored expires_at is roughly 20 mins ahead
        $result = $otpService->generate('test@example.com');
        $this->assertTrue($result['success']);

        $stored = \App\Models\EmailOtp::where('email', 'test@example.com')->first();
        $this->assertNotNull($stored);
        $this->assertGreaterThanOrEqual(19 * 60, now()->diffInSeconds($stored->expires_at, false));
    }

    public function test_settings_fallbacks_keep_existing_behavior(): void
    {
        $settings = app(AppSettings::class);

        $this->assertEquals('Paxi', $settings->appName());
        $this->assertEquals('IT Passport Study Companion', $settings->appTagline());
        $this->assertEquals('/paxi-mark.svg', $settings->logoUrl());
        $this->assertEquals('/favicon.svg', $settings->faviconUrl());

        $banner = $settings->announcementBanner();
        $this->assertFalse($banner['enabled']);
        $this->assertEquals('', $banner['text']);

        $this->assertEquals(60, $settings->passMarkPercent());
        $this->assertEquals(30, $settings->fieldMinimumPercent());
    }

    public function test_reset_pretest_archives_attempts_with_timestamp(): void
    {
        $assessment = Assessment::create([
            'user_id' => $this->learner->id,
            'assessment_type' => 'pretest',
            'score_percentage' => 75.0,
            'status' => 'submitted',
        ]);

        $this->assertTrue($this->learner->hasCompletedInitialAssessment());

        Livewire::actingAs($this->admin)
            ->test(UserResource\Pages\ListUsers::class)
            ->callTableAction('reset_pretest', $this->learner);

        $this->assertNotNull($assessment->fresh()->archived_at);
        $this->assertFalse($this->learner->fresh()->hasCompletedInitialAssessment());

        $resetLog = AuditLog::where('action', 'pretest.reset')->latest()->first();
        $this->assertNotNull($resetLog);
        $this->assertEquals($this->learner->id, $resetLog->target_id);
    }
}
