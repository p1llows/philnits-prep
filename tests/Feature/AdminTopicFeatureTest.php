<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTopicFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_topics(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        
        $response = $this->actingAs($learner)->get('/admin/topics');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_topics(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Topic::factory()->create(['name' => 'Sample Topic', 'code' => 'PH-01']);

        $response = $this->actingAs($admin)->get('/admin/topics');
        $response->assertStatus(200);
        $response->assertSee('Sample Topic');
    }

    public function test_admin_can_create_topic(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/topics', [
            'name' => 'New Database Topic',
            'code' => 'PH-02',
            'description' => 'Database fundamentals',
            'color' => '3B82F6',
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/topics');
        $this->assertDatabaseHas('topics', [
            'name' => 'New Database Topic',
            'code' => 'PH-02',
        ]);
    }
}
