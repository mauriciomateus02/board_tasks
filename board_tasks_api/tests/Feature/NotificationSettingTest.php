<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\NotificationSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_user_can_store_and_retrieve_setting()
    {
        $user = $this->authenticate();

        $payload = ['days_before' => 5];
        $this->postJson('/api/notification-settings', $payload)
             ->assertStatus(200)
             ->assertJsonFragment(['days_before' => 5]);

        $this->getJson('/api/notification-settings')
             ->assertStatus(200)
             ->assertJsonFragment(['days_before' => 5]);
    }

    public function test_store_validates_days_before()
    {
        $user = $this->authenticate();

        $this->postJson('/api/notification-settings', ['days_before' => 0])
             ->assertStatus(422);

        $this->postJson('/api/notification-settings', ['days_before' => 31])
             ->assertStatus(422);
    }

    public function test_user_cannot_delete_others_setting()
    {
        $user = $this->authenticate();
        $other = User::factory()->create();
        $setting = NotificationSetting::create([
            'user_id' => $other->id,
            'days_before' => 2,
        ]);

        $this->deleteJson("/api/notification-settings/{$setting->id}")
             ->assertStatus(403);
    }
}
