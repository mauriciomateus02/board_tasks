<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_user_can_create_list_and_show_tasks()
    {
        $user = $this->authenticate();

        $this->getJson('/api/tasks')->assertStatus(200)
             ->assertJson(['data' => []]);

        $payload = [
            'title' => 'Foo',
            'description' => 'Bar',
            'due_date' => now()->addDay()->toDateString(),
        ];

        $create = $this->postJson('/api/tasks', $payload);
        $create->assertStatus(200)
               ->assertJsonFragment(['title' => 'Foo']);

        $id = $create->json('id');

        $this->getJson("/api/tasks/$id")
             ->assertStatus(200)
             ->assertJsonFragment(['description' => 'Bar']);
    }

    public function test_user_can_update_and_delete_task()
    {
        $user = $this->authenticate();
        $task = $user->tasks()->create([
            'title' => 'Old',
            'description' => 'teste',
            'due_date' => now()->addDay()->toDateString(),
            'is_completed' => false,
        ]);

        $this->patchJson("/api/tasks/{$task->id}", ['title' => 'New'])
             ->assertStatus(200)
             ->assertJson(['title' => 'New']);

        $this->deleteJson("/api/tasks/{$task->id}")
             ->assertStatus(200)
             ->assertJson(['message' => 'Tarefa excluida.']);
    }

    public function test_task_creation_validation_fails_without_title()
    {
        $this->authenticate();
        $this->postJson('/api/tasks', [])
             ->assertStatus(422);
    }

    public function test_user_can_complete_task()
    {
        $user = $this->authenticate();
        $task = $user->tasks()->create([
            'title' => 'X',
            'description' => 'teste',
            'due_date' => now()->addDay()->toDateString(),
            'is_completed' => false,
        ]);

        $this->patchJson("/api/tasks/{$task->id}/complete")
             ->assertStatus(200)
             ->assertJson(['is_completed' => true]);
    }

    public function test_user_cannot_access_other_users_tasks()
    {
        $user = $this->authenticate();
        $other = User::factory()->create();
        $task = $other->tasks()->create([
            'title' => 'Secret',
            'description' => 'teste',
            'due_date' => now()->addDay()->toDateString(),
            'is_completed' => false,
        ]);

        $this->getJson("/api/tasks/{$task->id}")->assertStatus(403);
        $this->patchJson("/api/tasks/{$task->id}", ['title' => 'foo'])->assertStatus(403);
        $this->deleteJson("/api/tasks/{$task->id}")->assertStatus(403);
    }

    public function test_complete_nonexistent_task_returns_404()
    {
        $user = $this->authenticate();
        $this->patchJson('/api/tasks/999/complete')->assertStatus(404);
    }
}
